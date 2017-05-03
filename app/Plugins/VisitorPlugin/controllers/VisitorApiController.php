<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\VisitorRepository;
use App\Repositories\UserRepository;
use App\Repositories\Admin\UtilityRepository;
use App\Repositories\NotificationsRepository;
use App\Repositories\ProfileRepository;


class VisitorApiController extends Controller {

    protected $userRepo;
    protected $visitorRepo;
    protected $notifRepo;
    protected $profileRepo;
    
    public function __construct() {
        $this->userRepo    = app("App\Repositories\UserRepository");
        $this->visitorRepo = new VisitorRepository;
        $this->notifRepo   = new NotificationsRepository;
        $this->profileRepo = app("App\Repositories\ProfileRepository");
        
    }
 



    public function visitors (Request $req) {

        $auth_user = $req->real_auth_user;
        $superpower_activated = ($auth_user->isSuperPowerActivated()) ? "true" : "false";

        $visitors = $this->visitorRepo->getVisitors($auth_user->id);
        $visitor_setting = UtilityRepository::get_setting('visitor_setting');

        if($visitors->count != 0) $this->notifRepo->clearNotifs("visitor");


        $formated_visitors = [];

        if ($visitor_setting == "everyone" || $superpower_activated == 'true') {


            foreach($visitors as $user) {
                
                $profile_picture = substr($user->profile_pic_url, 0);
                $profile_pic_url = [
                    "thumbnail" => $user->thumbnail_pic_url(),
                    "encounter" => $user->encounter_pic_url(),
                    "other"     => $user->others_pic_url(),
                    "original"  => $user->profile_pic_url(),
                ];
                $user->profile_picture_url = $profile_pic_url;
                $user->profile_picture_name = $profile_picture;
                unset($user->profile_pic_url);

                $user->superpower_activated = $user->isSuperPowerActivated() ? 'true' : 'false';
                $user->online_status = $user->onlineStatus() ? 'true' : 'false';
                $user->age = $user->age();

                $populatiry = $user->profile->populatiry;
                $user->populatiry = [
                    "value" => $populatiry?:"0",
                    "type" => $this->profileRepo->getPopularityType($populatiry)
                ];

                unset($user->profile);
                unset($user->credits);
                unset($user->remember_token);

                array_push($formated_visitors, $user);

            }

        }


        $no_visitor_reason = "CAN_SEE_VISITORS";
        if(!$visitors->count) {
            if($visitor_setting == "everyone") {
                $no_visitor_reason = "NORMAL_NO_VISITORS";
            } else if(($visitor_setting == "superpowers" || $visitor_setting == "") && $superpower_activated == 'false') {
                $no_visitor_reason = "SUPERPOWER_NOT_ACTIVATED";
            }
        }
            



        $paging = $this->createPagination('api/visitors', $visitors);

        return response()->json([
            "status" => "success",
            "success_data" => [
                "superpower_activated" => $superpower_activated,
                "visitors"             => $formated_visitors,
                "no_visitor_reason"    => $no_visitor_reason,
                "paging"               => $paging,
                "success_text"         => "Visitors retrived successfully."
            ]
        ]);
        
    }


    protected function createPagination ($route, $lengthAwarepaginator_obj) {
        $paging = [
            "total" => $lengthAwarepaginator_obj->total(),
            "current_page_url" => "",
            "more_pages" => $lengthAwarepaginator_obj->hasMorePages() ? "true" : "false",
            "prevous_page_url" => "",
            "next_page_url"    => "",
            "last_page_url"    => ""
        ];

        $cur_page = $lengthAwarepaginator_obj->currentPage();
        $current_page_url = $cur_page ? url($route.'?page='. $cur_page):"";
        $paging["current_page_url"] = $current_page_url;

        $last_page = $lengthAwarepaginator_obj->lastPage();

        $next_page_url = ($last_page > $cur_page) ? url($route.'?page='.($cur_page+1)) : "";
        $paging['next_page_url'] = $next_page_url;

        if ($cur_page > 1) {
            $paging['prevous_page_url'] = url($route.'?page='.($cur_page-1));
        } else {
            $paging['prevous_page_url'] = "";
        }

        $paging["last_page_url"] = url($route."?page=".$last_page);
        return $paging;
    }

}


// $arr = array();
//         $user = Auth::user();

//         $logId = $user->id;
//       
//         $visitors = $this->visitorRepo->getAllVisitors($logId);
//         foreach($visitors as $visit)
//         {
//             array_push($arr,$this->userRepo->getUserById($visit->user1));
//         }
//      //    
//   //       
//        
//         //checking whether users super power activated  or not
//         if($visitors->count != 0)
//             $this->notifRepo->clearNotifs("visitor");
//         $visitor_setting = UtilityRepository::get_setting('visitor_setting');
//         if($visitor_setting == "everyone" || $this->superpowerRepo->isSuperPowerActivated($user->id) )
//         {
//             return Theme::view('plugin.VisitorPlugin.visitor', array('activated'=> true ,'logUser'=>Auth::user(),'visit'=>$visitors,'visitor_data'=>$arr));
//         }
//         else
//         {
//             return Theme::view('plugin.VisitorPlugin.visitor', array('activated'=> false ,'logUser'=>Auth::user(),'visit'=>$visitors,'visitor_data'=>$arr));
//         }



// $auth_user = $req->real_auth_user;        
//         $superpower_activated = ($auth_user->isSuperPowerActivated()) ? "true" : "false";

//         $likes = $this->encounterRepo->whoLiked($auth_user->id);  
//         $this->notifRepo->clearNotifs("liked");

//         $liked_users = [];
//         foreach ($likes->likes as $user) {

//             $profile_picture = substr($user->profile_pic_url, 0);
//             $profile_pic_url = [
//                 "thumbnail" => $user->thumbnail_pic_url(),
//                 "encounter" => $user->encounter_pic_url(),
//                 "other"     => $user->others_pic_url(),
//                 "original"  => $user->profile_pic_url(),
//             ];
//             $user->profile_picture_url = $profile_pic_url;
//             $user->profile_picture_name = $profile_picture;
//             unset($user->profile_pic_url);

//             $user->superpower_activated = $user->isSuperPowerActivated() ? 'true' : 'false';
//             $user->online_status = $user->onlineStatus() ? 'true' : 'false';
//             $user->age = $user->age();

//             $populatiry = $user->profile->populatiry;
//             $user->populatiry = [
//                 "value" => $populatiry?:"0",
//                 "type" => $this->profileRepo->getPopularityType($populatiry)
//             ];

//             $user->credit_balance = $user->credits->balance;
//             unset($user->profile);
//             unset($user->credits);

//             array_push($liked_users, $user);
//         }

//         if ($superpower_activated === 'false') { $liked_users = []; }
//         $paging = $this->createPagination('api/likes', $likes->likes);

//         return response()->json([
//             "status" => "success",
//             "success_data" => [
//                 "superpower_activated" => $superpower_activated,
//                 "users_liked_me"        => $liked_users,
//                 "paging"               => $paging,
//                 "success_text"         => "My liked users retrived successfully."
//             ]
//         ]);