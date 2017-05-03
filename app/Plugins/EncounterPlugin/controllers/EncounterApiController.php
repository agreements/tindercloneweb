<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Components\Plugin;
use Illuminate\Http\Request;
use App\Repositories\RegisterRepository;
use App\Repositories\CreditRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\EncounterRepository;
use App\Repositories\UserRepository;
use App\Repositories\VisitorRepository;
use App\Repositories\SuperpowerRepository;
use App\Repositories\NotificationsRepository;
use stdClass;

class EncounterApiController extends Controller
{
    protected $registerRepo;
    protected $creditRepo;
    protected $superpowerRepo;
    protected $encounterRepo;
    protected $userRepo;
    protected $visitorRepo;
    protected $profileRepo;
    protected $notifRepo;
    
    public function __construct() {
        $this->registerRepo   = app('App\Repositories\RegisterRepository');
        $this->creditRepo     = app("App\Repositories\CreditRepository");
        $this->profileRepo    = app("App\Repositories\ProfileRepository");
        $this->encounterRepo  = new EncounterRepository;
        $this->userRepo       = app("App\Repositories\UserRepository");
        $this->superpowerRepo = app('App\Repositories\SuperpowerRepository');
        $this->visitorRepo    = new VisitorRepository;
        $this->notifRepo      = new NotificationsRepository;
        
    }
    
    public function encounters (Request $req) {

        $auth_user = $req->real_auth_user;

        $users = $this->encounterRepo->nextEncounterUser($auth_user, true);

        if (!$users) {
            return response()->json([
                "status" => "error",
                "error_data" => [
                    "encounters_left" => "0",
                    "error_text" => "No encouters"
                ]
            ]);    
        }
            
        $encoutr_users = [];
        foreach ($users as $user) {

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

            $user->social_links = $user->get_social_links();
            $user->social_verified = count($user->social_links)? "true" : "false";
            unset($user->social_login_links);
            

            $photos = new \stdClass;
            $photos->count = count($user->photos);
            $photos->items = [];
    
            foreach ($user->photos as $photo) {
                $item  = new \stdClass;
                $item->id = $photo->id;
                $item->photo_name = $photo->photo_url;
                $item->encoutner_photo_url = $photo->encounter_photo_url();
                $item->thumbnail_photo_url = $photo->thumbnail_photo_url();
                $item->original_photo_url = $photo->original_photo_url();
                $item->other_photo_url = $photo->other_photo_url();
                array_push ($photos->items, $item);
            }
            unset($user->photos);
            $user->photos = $photos;
      
            $liked = $this->encounterRepo->getMutualMatch($user->id, $auth_user->id);
            $user->liked_me = ($liked) ? "1" : "0";

            array_push($encoutr_users, $user);
        }
         
        $encounter_left = $this->encounterRepo->getEncounterCount($auth_user->id);

        return response()->json([ 
            "status" => "success",
            "success_data" => [
                "encounters_left" => $encounter_left,
                "encouters" => $encoutr_users,
                "success_text" => "Encounters retrived successfully."
            ]
        ]);

    }


    public function myLikes (Request $req) {

        $auth_user = $req->real_auth_user;        
        $superpower_activated = ($auth_user->isSuperPowerActivated()) ? "true" : "false";

        $my_likes = $this->encounterRepo->getAllLikes($auth_user->id);

        $my_liked_users = [];
        foreach ($my_likes as $my_like) {
            $user = $my_like->user;

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

            $user->credit_balance = $user->credits->balance;
            unset($user->profile);
            unset($user->credits);

            array_push($my_liked_users, $user);
        }

        //if ($superpower_activated === 'false') { $my_liked_users = []; }
        $paging = $this->createPagination('api/mylikes', $my_likes);

        return response()->json([
            "status" => "success",
            "success_data" => [
                "superpower_activated" => $superpower_activated,
                "my_liked_users"        => $my_liked_users,
                "paging"               => $paging,
                "success_text"         => "User liked me retrived successfully."
            ]
        ]);

            
    }


    /* this funciton will create an entry in encounter table for liked or disliked
       and also create a entry in matches table for finding further mathched users
    */
    public function doLike(Request $req) {

        $auth_user    = $req->real_auth_user;
        $encounter_id = $req->encounter_id;
        $like         = $req->like;

        $encounter_user = $this->userRepo->getUserById($encounter_id);
        if (!$encounter_user || !preg_match("/^[0-9]+$/", $encounter_id) ) {
            return response()->json([
                "status" => "error",
                "error_data" => [
                    "encounter_id" => "User to be liked is not valid.",
                    "error_text" => "Validation error."
                ]
            ]);
        }


        if (!in_array($like, ["_like", "_dislike"])) {
            return response()->json([
                "status" => "error",
                "error_data" => [
                    "like" => "Like or dislike is required or format error",
                    "error_text" => "Validation error."
                ]
            ]);
        }
  
        $likeOrDislike = ($like == "_like") ? 1 : 0;
        $encounter = $this->encounterRepo->createEncounter($auth_user->id, $encounter_id, $likeOrDislike);

        if (!$encounter) {
            return response()->json([
                "status" => "error",
                "error_data" => [
                    "error_text" => "Some error occured or may be already encountered."
                ]
            ]);
        }

        
        Plugin::fire('user_encountered', [$encounter_user, $likeOrDislike]);

        $match_found = "false";

        if ($like == "_like") {

            $var = $this->encounterRepo->getMutualMatch($encounter->user2, $encounter->user1);            
            
            $encounter_user_like_setting = $this->notifRepo->getNotifSettingsByType($encounter_id, 'liked');
            
            if (!$encounter_user_like_setting || $encounter_user_like_setting->browser == 1) {
                $this->encounterRepo->insertNotif($auth_user->id, $encounter_id, 'liked', $encounter_id);
            }

            if (!$encounter_user_like_setting || $encounter_user_like_setting->email == 1) {
                $email_array = new \stdCLass;
                $email_array->user  = $encounter_user;
                $email_array->user2 = $auth_user;
                $email_array->type  = 'liked';
                Plugin::Fire('send_email', $email_array);
            }

            

            if ($var) { // match found

                $match_found = "true";

                $this->encounterRepo->createMatch($encounter->user1, $encounter->user2);
                $this->encounterRepo->createMatch($encounter->user2, $encounter->user1);

                //send email notificaiton to other user
                $encounter_user_match_setting = $this->notifRepo->getNotifSettingsByType($encounter_id, 'match');

                if (!$encounter_user_match_setting || $encounter_user_match_setting->email == 1) {
                    $email_array = new \stdCLass;
                    $email_array->user = $encounter_user;
                    $email_array->user2 = $auth_user;
                    $email_array->type = 'match';
                    Plugin::Fire('send_email', $email_array);
                }

                Plugin::fire('match_found', $encounter_user->id);
            }
        }


        return response()->json([
            "status" => "success",
            "success_data" => [
                "match_found" => $match_found,
                "success_text" => "User encountered successfully."
            ]
         ]);


    }


    
    public function matches(Request $req) {

        $auth_user = $req->real_auth_user;        
        $superpower_activated = ($auth_user->isSuperPowerActivated()) ? "true" : "false";

        $matches = $this->encounterRepo->getAllMatchedUsers($auth_user->id);
        $this->notifRepo->clearNotifs("match");

        $matched_users = [];
        foreach ($matches as $match) {
            $user = $match->user;

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

            $user->credit_balance = $user->credits->balance;
            unset($user->profile);
            unset($user->credits);

            array_push($matched_users, $user);
        }

        // if ($superpower_activated === 'false') { $matched_users = []; }
        
        $paging = $this->createPagination('api/matches', $matches);

        return response()->json([
            "status" => "success",
            "success_data" => [
                "superpower_activated" => $superpower_activated,
                "matched_users"        => $matched_users,
                "paging"               => $paging,
                "success_text"         => "Matched users retrived successfully."
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


    public function likes (Request $req) {

        $auth_user = $req->real_auth_user;        
        $superpower_activated = ($auth_user->isSuperPowerActivated()) ? "true" : "false";

        $likes = $this->encounterRepo->whoLiked($auth_user->id);  
        $this->notifRepo->clearNotifs("liked");

        $liked_users = [];
        foreach ($likes as $user) {

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

            $user->credit_balance = $user->credits->balance;
            unset($user->profile);
            unset($user->credits);

            array_push($liked_users, $user);
        }

        if ($superpower_activated === 'false') { $liked_users = []; }
        $paging = $this->createPagination('api/likes', $likes);

        return response()->json([
            "status" => "success",
            "success_data" => [
                "superpower_activated" => $superpower_activated,
                "users_liked_me"        => $liked_users,
                "paging"               => $paging,
                "success_text"         => "My liked users retrived successfully."
            ]
        ]);
    }
}
