<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\TestModel;

use App\Components\Plugin;
use App\Repositories\CreditRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\ChatRepository;
use App\Repositories\NotificationsRepository;
use Auth;

use Illuminate\Http\Request;
use App\Components\Theme;

use App\Models\Notifications;



use App\Contacts;

class NotificationsController extends Controller
{
      protected $notifRepo;

    public function __construct(NotificationsRepository $notifRepo)
    {
        $this->notifRepo = $notifRepo;
        $this->pushNotificationRepo = app('App\Plugins\NotificatinsPlugin\Repositories\PushNotificationRepository');
    }

    public function poll ($count) { 

        $auth_user = Auth::user();
        $logId = $auth_user->id;

        $endtime = time() + 60;
        $last_timestamp = date('Y-m-d H:i:s');
        
        //polling loop
        while(time() <= $endtime) {


            $newCount = Notifications::where('to_user', '=', $logId)->where('status', '=', 'unseen')->where('created_at', '>', $last_timestamp)->count();

            $notifications = Notifications::where('status', '=', 'unseen')->where('to_user', $auth_user->id)->where('created_at', '>', $last_timestamp)->orderBy('created_at', 'desc')->get();

            foreach ($notifications as $notification) {

                if ($notification->notification_hook_type == "central") {
                    $content = Plugin::fire($notification->type, ["notification" => $notification]);
                    $notification->content = isset($content[0]) ? $content[0]->render() : "";
                
                } else if ($notification->notification_hook_type == 'left_menu') {

                    $user = \App\Models\User::find($notification->from_user);

                    if ($user) {
                        $notification->other_user_detials = [
                            "name"            => $user->name,
                            "age"             => $user->age(),
                            "profile_picture" => $user->profile_pic_url()
                        ];
                    }

                } 


            }


          
            if($newCount > 0) { 


                return response()->json([

                    "status"                 => "success",
                    "new_notifications"      => "true",
                    "new_notifications_count" => $newCount,
                    "notifications"          => $notifications

                ]);

                //return response()->json(Notifications::latest_notifications($logId));
            }


          sleep(3);  

        }


        return response()->json(["status"=> "success", "new_notifications" => "false"]);
    }

    public function getNotifications()
    {
      $logId = Auth::user()->id;
      $notif = $this->notifRepo->getNotifs($logId);

        //getNearbyPeoples($logId, $gender, $ageLower, $ageUpper, $distance = 1)
        return response()->json($notif);
    }



    public function getCentralNotifications (Request $req) {

        $last_notification_timestamp = $req->last_notification_timestamp;
        $auth_user = Auth::user();
        $notifications = NotificationsRepository::getCentralNotificationsOverTimestamp($auth_user->id, $last_notification_timestamp);

        $notification_items_content = "";
        foreach ($notifications as $notification) {

            $content = Plugin::fire($notification->type, ["notification" => $notification]);
            if(isset($content[0])) {
                $notification_items_content .= $content[0]->render(); 
            }
            
        }

        return response()->json([
            "status" => "success",
            "notifications" => $notifications,
            "notification_items_content" => $notification_items_content,
            "notifications_count" => $notifications->count(),
            "last_notification_timestamp" => $notifications->last() != null ? $notifications->last()->created_at->toDateTimeString() : "",
        ]);

    }



    // public function markSeenNotification (Request $req) {
    //     $notif_id = $req->notification_id;
        
    //     $notif = NotificationsRepository::getNotificationById($notif_id);
        
    //     if ($notif) {
    //         $notif->status = 'seen';
    //         $notif->save();
    //         return response()->json(["status" => "success"]);

    //     } else {
    //         return response()->json(["status" => "error"]);
    //     }

    // }


    // public function markUnseenNotification (Request $req) {

    //     $notif_id = $req->notification_id;
        
    //     $notif = NotificationsRepository::getNotificationById($notif_id);
        
    //     if ($notif) {
    //         $notif->status = 'unseen';
    //         $notif->save();
    //         return response()->json(["status" => "success"]);

    //     } else {
    //         return response()->json(["status" => "error"]);
    //     }

    // }

    // public function markSeenAllNotification (Request $req) {
    //     $auth_user = Auth::user();
    //     NotificationsRepository::setAllNotificationsStatus($auth_user->id, 'seen');
    //     return response()->json(["status" => "success"]);
    // }

    public function markSeenAllCentralNotifications (Request $req) {
        $auth_user = Auth::user();
        NotificationsRepository::setAllCentralNotificationsStatus($auth_user->id, 'seen');
        return response()->json(["status" => "success"]);
    }








    public function pushNotification()
    {
        return Plugin::view('NotificationsPlugin/push_notification_settings', [
            'project_number'  => $this->pushNotificationRepo->getProjectNumber(),
            'web_api_key'     => $this->pushNotificationRepo->getWebApiKey(),
            'android_api_key' => $this->pushNotificationRepo->getAndroidApiKey(),
            'ios_api_key'     => $this->pushNotificationRepo->getIosApiKey(),
        ]);
    }


    public function savePushNotificationSetting(Request $req)
    {
        $this->pushNotificationRepo
                ->setProjectNumber(trim($req->project_number))
                ->setWebApiKey(trim($req->web_api_key)) 
                ->setAndroidApiKey(trim($req->android_api_key))
                ->setIosAPIKey(trim($req->ios_api_key))
                ->persist();

        return response()->json(['status' => "success"]);
    }


    public function registerDevice(Request $req)
    {
        $auth_user = $req->real_auth_user;
        $deviceID = $req->device_id;
        $deviceToken = $req->device_token;

        if(is_null($deviceToken)) {
            return response()->json([
                "status" => "error",
                "error_type" => "DEVICE_TOKEN_INVALID",
                "error_text" => 'Device token required'
            ]);
        }

        $this->pushNotificationRepo->registerDevice($auth_user->id, $deviceID, $deviceToken);

        return response()->json([
            "status" => "success",
            "success_type" => "USER_DEVICE_REGISTERED",
            "success_text" => 'User device registered successfully.'
        ]);
    }


}