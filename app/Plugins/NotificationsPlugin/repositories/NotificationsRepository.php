<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Profile;
use App\Models\Match;
use App\Models\Encounter;
use App\Models\Visitor;
use App\Models\Contacts;
use App\Models\ChatMessage;
use App\Models\Notifications;
use App\Models\NotificationSettings;
use App\Models\Settings;
use App\Components\Plugin;


class NotificationsRepository
{

	public function __construct()
	{
		
	}
	
    public function clearNotifs($type)
    {
        Notifications::clear($type);
    }

    public function getLastDayNotifsWithUnseenStatus($from_user, $to_user, $type)
    {
        return Notifications::whereBetween('created_at', [date("Y-m-d", strtotime("-24 hours")).'%', date('Y-m-d H:i:s')])->where('from_user','=',$from_user)->where('to_user','=',$to_user)->where('type','=',$type)->where('status','=','unseen')->first();
    }

    public function getLastDayNotifs($from_user, $to_user, $type)
    {
        return Notifications::whereBetween('created_at', [date("Y-m-d", strtotime("-24 hours")).'%', date('Y-m-d H:i:s')])->where('from_user','=',$from_user)->where('to_user','=',$to_user)->where('type','=',$type)->first();
    }

	public function getNotifs($id)
	{
		$notif = Notifications::where('to_user','=',$id)->where('status','=','unseen')->get();
		foreach($notif as $not)
		{
			$user = User::find($not->from_user);
	        $not['name'] = $user->name;
	        $not['age'] = $user->age();
	        $not['pic'] = $user->profile_pic_url();
		}
		return $notif;
	}

	public function set_notif_settings($arr,$id)
    {
        $notif = NotificationSettings::where('userid','=', $id)->first();
        if($notif)
        {
            $notif = NotificationSettings::where('userid','=', $id)->where('type','=','visitor')->first();
            $notif = $this->save_notif_settings($notif,$id,"visitor",$arr['browser_visitor'],$arr['email_visitor']);
            $notif->save();
            
            $notif = NotificationSettings::where('userid','=', $id)->where('type','=','liked')->first();
            $notif = $this->save_notif_settings($notif,$id,"liked",$arr['browser_liked'],$arr['email_liked']);
            $notif->save();

            $notif = NotificationSettings::where('userid','=', $id)->where('type','=','match')->first();
            $notif = $this->save_notif_settings($notif,$id,"match",$arr['browser_match'],$arr['email_match']);
            $notif->save();
        }
        else
        {
            $notif = new NotificationSettings;
            $notif = $this->save_notif_settings($notif,$id,"visitor",$arr['browser_visitor'],$arr['email_visitor']);
            $notif->save();

            $notif = new NotificationSettings;
            $notif = $this->save_notif_settings($notif,$id,"liked",$arr['browser_liked'],$arr['email_liked']);
            $notif->save();

            $notif = new NotificationSettings;
            $notif = $this->save_notif_settings($notif,$id,"match",$arr['browser_match'],$arr['email_match']);
            $notif->save();
        }
    }

    public function save_notif_settings($notif , $userid, $type, $browser, $email)
    {
        $notif->userid = $userid;
        $notif->type = $type;
        $notif->browser = $browser;
        $notif->email = $email;
        return $notif;
    }

    //transaction entry for superpowers
    public function getNotificationSettings($id)
    {
        $arr = array();
        $notif = NotificationSettings::where('userid','=',$id)->get();
        $set_noti = array();
        $all_noti = array("liked","visitor","match");
        foreach($notif as $not)
        {
            $arr['browser_'.$not->type] = $not->browser;
            $arr['email_'.$not->type] = $not->email;
            array_push($set_noti,$not->type);
        }
        
        $not_set_noti = array_diff($all_noti,$set_noti);
        foreach($not_set_noti as $not) {
            $arr['browser_'.$not] = 1;
            $arr['email_'.$not] = 1;
        }
        
        return $arr;
    }

    public function getNotifSettingsByType($id, $type)
    {
        return NotificationSettings::where('userid','=',$id)->where('type','=', $type)->first();
    }
    


    public static function getCentralNotifications($user_id, $no_of_notifications = 0, &$count = 0) {

        $notifications = Notifications::where('notification_hook_type', 'central')
                                        ->where('to_user', $user_id)
                                        ->orderBy('created_at', 'desc');

        $temp_notifications = clone $notifications;
        
        $count = $temp_notifications->where('status', 'unseen')->count();

        if ($no_of_notifications == 0) {
            return $notifications->get();
        } else {
            $notifications = $notifications->take($no_of_notifications)->get();
            return $notifications;
        }


    }


    public static function getCentralNotificationsOverTimestamp($user_id, $last_notification_timestamp) {

 
        $notifications = Notifications::where('notification_hook_type', 'central')
                                        ->where('to_user', $user_id)
                                        ->where('created_at', '<', date('Y-m-d H:i:s', strtotime($last_notification_timestamp)))
                                        ->orderBy('created_at', 'desc')->take(5)->get();

        return $notifications;

    }




    public static function getNotificationById ($notification_id) {
        return Notifications::find($notification_id);
    }

    public static function setAllCentralNotificationsStatus ($user_id, $status = 'unseen') {        

        Notifications::where('to_user', $user_id)->where('notification_hook_type', 'central')->update(["status" => $status]);
        return true;

    }


}

