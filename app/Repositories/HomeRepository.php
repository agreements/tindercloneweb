<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Profile;
use App\Models\Match;
use App\Models\Encounter;
use App\Models\Visitor;
use App\Models\UserInterests;
use App\Models\Settings;
use App\Models\EmailSettings;
use App\Models\Notifications;
use App\Models\NotificationSettings;
use App\Models\SuperPowerPackages;
use App\Models\UserSuperPowers;
use DB;
use App\Repositories\BlockUserRepository;
use App\Models\Transaction;
use App\Models\SuperpowerHistory;
use App\Components\Plugin;
use App\Models\UserSettings;


use stdClass;

class HomeRepository
{
    private $blockUserRepo;
    public function __construct(Encounter $encounter, User $user, NotificationSettings $notification_settings, UserSuperPowers $user_superpowers, UserSettings $user_settings, SuperPowerPackages $superpower_packages, Notifications $notifications, Transaction $transaction, SuperpowerHistory $superpower_history, Match $match, Visitor $visitor, EmailSettings $email_settings)
    {
        $this->blockUserRepo = app("App\Repositories\BlockUserRepository");
        $this->encounter = $encounter;
        $this->user = $user;
        $this->notification_settings = $notification_settings;
        $this->user_superpowers = $user_superpowers;
        $this->user_settings = $user_settings;
        $this->superpower_packages = $superpower_packages;
        $this->notifications = $notifications;
        $this->transaction = $transaction;
        $this->superpower_history = $superpower_history;
        $this->match = $match;
        $this->visitor = $visitor;
        $this->email_settings = $email_settings;
    }
    
    public function getTotalEncounters($id)
    {
        $count = $this->encounter->where('user1','=',$id)->where('created_at','like',date('Y-m-d').'%')->count();
        return $count;
    }

    public function getAllUsers()
    {
        return $this->user->all();
    }

    public function getStripePublishableKey()
    {
        $stripe_publishable_key = Settings::where('admin_key','=','stripe_publishable_key')->first();
        if($stripe_publishable_key)
            return $stripe_publishable_key->value;
        else
            return null;
    }

    public function set_notif_settings($arr,$id)
    {
        $notif = $this->notification_settings->where('userid','=', $id)->first();
        if($notif)
        {
            $notif = $this->notification_settings->where('userid','=', $id)->where('type','=','visitor')->first();
            $notif = $this->save_notif_settings($notif,$id,"visitor",$arr['browser_visitor'],$arr['email_visitor']);
            $notif->save();
            
            $notif = $this->notification_settings->where('userid','=', $id)->where('type','=','liked')->first();
            $notif = $this->save_notif_settings($notif,$id,"liked",$arr['browser_liked'],$arr['email_liked']);
            $notif->save();

            $notif = $this->notification_settings->where('userid','=', $id)->where('type','=','match')->first();
            $notif = $this->save_notif_settings($notif,$id,"match",$arr['browser_match'],$arr['email_match']);
            $notif->save();
        }
        else
        {
            $notif = clone $this->notification_settings;
            $notif = $this->save_notif_settings($notif,$id,"visitor",$arr['browser_visitor'],$arr['email_visitor']);
            $notif->save();

            $notif = clone $this->notification_settings;
            $notif = $this->save_notif_settings($notif,$id,"liked",$arr['browser_liked'],$arr['email_liked']);
            $notif->save();

            $notif = clone $this->notification_settings;
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
        $notif = $this->notification_settings->where('userid','=',$id)->get();
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
    
    public function getInvisibleSettings($id) {
        
        $invisible = array();
        
        $settings = $this->user_superpowers->where("user_id",$id)->first();
        if($settings) {
            
            $invisible["hide_visitors"] = $settings->invisible_mode;
            $invisible["hide_superpowers"] = $settings->hide_superpowers;
        } else {
            $invisible["hide_visitors"] = 0;
            $invisible["hide_superpowers"] = 0;
        }
        
        return $invisible;
    }

    public function getPrivacySettings($id) {
        
        $arr =array();
         $set_settings = array();
        $all_settings = array("online_status","show_distance");
        $settings = $this->user_settings->where("userid",$id)->get();
        foreach($settings as $setting)
        {
            $arr[$setting->key] = $setting->value;
            array_push($set_settings,$setting->key);
        }
        
        $not_set_setting = array_diff($all_settings,$set_settings);
        foreach($not_set_setting as $setting) {
            $arr[$setting] = 1;
        }
        return $arr;
        
    }


    public function getSuperpowerPackages()
    {
        return $this->superpower_packages->all();
    }

    public function getEncounterCount($id)
    {
        if($this->isSuperPowerActivated($id))
            return 9999;
        else
        {
            $count = $this->encounter->where('user1','=',$id)->where('created_at','like',date('Y-m-d').'%')->count();
            $encounter = Settings::_get('limit_encounter');
            $left = $encounter - $count;
            if($left<0)
                $left = 0;
            return $left;
        }
    }

    public function transactionDetails ($gateway, $trans_id, $packid, $status, $logId, $invisible) {
        $packObj = $this->superpower_packages->where ('id', $packid)->first();
        $trans = clone $this->transaction;

        $trans->gateway = $gateway;
        $trans->transaction_id = $trans_id;
        $trans->amount = $packObj->amount;

        if ($status == 'SuccessWithWarning' || $status == 'Success' || $status == 'succeeded') {
            $trans->status = 'success';
            $trans->save();
            $user_superpower = $this->user_superpowers->where('user_id','=',$logId)->first();
            if($user_superpower)
            {
                $user_superpower->user_id = $logId;
                $user_superpower->invisible_mode = $invisible;
                $user_superpower->hide_superpowers = 0;
                $user_superpower->expired_at = date('Y-m-d', strtotime("+{$packObj->duration} days", strtotime(date('Y-m-d'))));
            }
            else
            {
                $user_superpower = new UserSuperPowers;
                $user_superpower->user_id = $logId;
                $user_superpower->invisible_mode = $invisible;
                $user_superpower->hide_superpowers = 0;
                $user_superpower->expired_at = date('Y-m-d', strtotime("+{$packObj->duration} days", strtotime(date('Y-m-d'))));
            }
            $user_superpower->save();
            $superpower_history = clone $this->superpower_history;
            $superpower_history->user_id = $logId;
            $superpower_history->trans_table_id = $trans->id;
            $superpower_history->superpower_package_id = $packObj->id;
            $superpower_history->save();
        }
        else
        {
            $trans->status = 'failure';
            $trans->save();
        }
    }

    public function insertNotif($fromUser,$to_user)
    {
        $notif = clone $this->notifications;
        $notif->from_user = $fromUser;
        $notif->to_user = $to_user;
        $notif->type = "liked";
        $notif->status = "unseen";
        $notif->entity_id = $to_user;
        $notif->save();
    }
    // change mail 
    public function changeEmail($user,$email)
    {
        $msg = array();
        $activation_code = str_random(60) . $email;
        $user->activate_token = $activation_code;
        $user->username = $email;
        $user->activate_user = "deactivated";
        $user->save();

        $set = $this->email_settings->where('email_type','=','change_email')->first();
        if($set)
        {
            array_push($msg, $set->subject);
            array_push($msg,$this->mailBodyParser($set->content, $user));
        }
        return $msg;
    }

    // change password
    public function changePasswordMail($user)
    {
        $msg = array();
        $set = $this->email_settings->where('email_type','=','change_password')->first();
        if($set)
        {
            array_push($msg, $set->subject);
            array_push($msg,$this->mailBodyParser($set->content, $user));
        }
        return $msg;
    }

    public function mailBodyParser($body, $user)
    {
        $title = Settings::where('admin_key','=','website_title')->first();
        $symlink = array('@website_name' => $title->value,
                        '@name' => $user->name,
                        '@email' => $user->username,
                        '@link' => 'laravel5.gangcloud.com',
                        '@profilelink' => url('profile').'/'.$user->id,
                        '@activationlink' => url('sample/activate/') . '/'. $user->id . '/'. $user->activate_token);

        foreach ($symlink as $key => $value) 
        {
            $body = str_replace($key, $value, $body);   
        }

        return $body;
    }

    // this function will return user object based on id passed
    public function getUserById($id)
    {
       return $this->user->find($id);
    }
    
    public function getTitle()
    {
        $title = Settings::where('admin_key','=','website_title')->first();
        return $title->value;
    }
    
    /*this function will return next encounter user 
    and also filter encouter users as per age and gender prefered by log user 
    */

    public function nextEncounterUser($logUser , $flag)
    {
        $encounterUser = null;

        //retriving all encounters
        $encounters = $this->encounter->where('user1', '=', $logUser->id)->get();
        //declaring array for holding id's those will no come in to next encounter
        $exceptIds = array();

        foreach($encounters as $encounter)
            array_push($exceptIds, $encounter->user2);

        array_push($exceptIds, $logUser->id);

        $preferGender = $logUser->profile->prefer_gender;
        $preferAge = $logUser->profile->prefer_age;


        //retriving every users except previous encounter users
        $users = $this->user->whereNotIn('id', $exceptIds)
                    ->whereNotIn('id', $this->blockUserRepo->getAllBlockedUsersIds($logUser->id))
                    ->where('activate_user', '<>', 'deactivated') ////removing all deactivated users
                    ->where('profile_pic_url','<>','male.jpg')->where('profile_pic_url','<>','female.jpg');
        //filtering age wise
        $ageRange = array();
        //calculating the age reange with date
        $arr = explode('-', $preferAge);    

        $end = (date('Y')-$arr[1]) . '-'.date('m').'-'.date('d');
        $start = (date('Y')-$arr[0]) . '-'.date('m').'-'.date('d');     

        array_push($ageRange, $end);
        array_push($ageRange, $start);
        $users = $users->whereBetween('dob', $ageRange);
        
        if($preferGender == "M")
        {
            $users = $users->where('gender', '=', 'M');
        }
        else if($preferGender == "F")
        {
            $users = $users->where('gender', '=', 'F');
        }
        // dd($exceptIds);

        $interests = DB::select("SELECT *, count(userid) as count
                           FROM userinterests                         
                           WHERE interestid IN (SELECT interestid FROM userinterests WHERE userid = $logUser->id)
                           AND userid NOT IN (".implode(',', $exceptIds).")
                           GROUP BY userid
                           ORDER BY count DESC");
        // dd($interests);
        $ar = array();
        $encounterUser = [];
        $count = 0;
        foreach($interests as $interest)
            {
                $count++;
                if($count<=10)
                {
                    $temp = $this->user->where('gender','=',$preferGender)->where('id','=',$interest->userid)->where('profile_pic_url','<>','male.jpg')->where('profile_pic_url','<>','female.jpg')->first();
                    if($temp) {
                        array_push($ar, $temp);
                    }
                }
            }
    if(count($ar) == 0)
        $ar = $users->take(10)->get();
        if ($flag) 
        {
            foreach($ar as $a)
            $encounterUser = $ar;

        } else {
            if(count($ar) != 0)
                $encounterUser = $ar[0];
            // $encounterUser = $multipleUsers->whereIn('id',$arr)->first();
        }
        return $encounterUser;

    }
    
    
    //this funciton will create a clone $this->encounter row
    public function createEncounter($fromUser, $toUser, $likeOrDislike)
    {
        //searching for blocked users
        $blockedIds = $this->blockUserRepo->getAllBlockedUsersIds($fromUser);
        
        if(!array_search( $toUser, $blockedIds))
        {
            $encounter = clone $this->encounter;
            $encounter->user1 = $fromUser;
            $encounter->user2 = $toUser;
            $encounter->likes = $likeOrDislike;
            $encounter->save();
        
            return $encounter;
        }
        
        return null;
    }
    
    
    /* This function will check $formUser has liked $toUser or not 
    .. if true then it will return that particular Encounter object row form encounter table
    */
    public function getMutualMatch($fromUser, $toUser)
    {
        $temp = $this->encounter->where('user1', '=', $fromUser)
                                ->where('user2', '=', $toUser)
                                ->where('likes', '=', 1)->first();
        
        return $temp;
    }
    
    
    //This function will insert a new row to match table and return the match object
    public function createMatch($fromUser, $toUser)
    {
        $mat = clone $this->match;
        $mat->user1 = $fromUser;
        $mat->user2 = $toUser;
        $mat->save();
        $notif = clone $this->notifications;
        $notif->from_user = $fromUser;
        $notif->to_user = $toUser;
        $notif->type = "match";
        $notif->status = "unseen";
        $notif->entity_id = $toUser;
        $notif->save();
        // Plugin::Fire('matchFound', array($fromUser,$toUser));
        return $mat;
    }
    
    //this funciton returns all matched userd of $id passed into.
    public function getAllMatchedUsers($id)
    {
        $matches = $this->match->where('user1',"=",$id)
                          ->whereNotIn('user2', $this->blockUserRepo->getAllBlockedUsersIds($id))->orderBy('created_at', 'desc')->get();
        $matches->count = count($matches);
        
        return $matches;
    }
    
    
    //this function returns all the liked  by the id passed into
    public function getAllLikes($id)
    {
        $likes = $this->encounter->where("user1","=", $id)
                        ->whereNotIn('user2', $this->blockUserRepo->getAllBlockedUsersIds($id))
                        ->where("likes","=",1)
                        ->orderBy('created_at', 'desc')
                        ->get();
        $likes->count = count($likes);
                
        return $likes;
    }
    
    /* this function will return all visitors by the id passed into
    and also no of visitors . you can access using (visitor object)->count 
    */
    public function getAllVisitors($id)
    {
        $visitors = $this->visitor->where("user2","=",$id)
                            ->whereNotIn('user1', $this->blockUserRepo->getAllBlockedUsersIds($id))
                            ->orderBy('created_at', 'desc')
                            ->get();
        
        $visitors->count = count($visitors);
        
        return $visitors;
    }

    public function getVisitingDetails($id)
    {
        $obj = new stdClass;
        $fromDate = date("Y-m-d", strtotime("-7 days")).'%';
        $var = date("Y-m", strtotime("-30 days")).'%';
        $month = $this->notifications->where('type','=','visitor')->where('to_user','=',$id)->whereBetween('created_at', [$var, date('Y-m-d H:i:s')])->count();
        $week = $this->notifications->where('type','=','visitor')->where('to_user','=',$id)->whereBetween('created_at', [$fromDate, date('Y-m-d H:i:s').'%'])->count();
        $day = $this->notifications->where('type','=','visitor')->where('to_user','=',$id)->where('created_at', 'like', date('Y-m-d').'%')->count();
        $obj->month = $month;
        $obj->week = $week;
        $obj->day = $day;
        return $obj;
        
    }
    
    
    ///this funciton will set perfer gender and age filter for a particular user
    public function setFilter( $user, $filter)
    {
        $profile = $user->profile;

        $preferGender = '';
        
        if($filter['male'] == null && $filter['female'] == null)
        {
            if($user->gender == 'M')
                $preferGender = "F";
            else
                $preferGender = 'M';
        }
        else if($filter['male'] != null && $filter['female'] != null)
            $preferGender = "MF";
        else if($filter['female'] != null)
            $preferGender = "F";
        else if($filter['male'] != null)
            $preferGender = "M";


        $profile->prefer_age = str_replace(' ', '', $filter['age']);
        $profile->prefer_gender = $preferGender;

        if($filter['distance'] != null)
            $profile->prefer_distance_nearby = $filter['distance'];
        
        $profile->save();
    }




    //this funciton will return whether user has activated super power or not
    public function isSuperPowerActivated ($id) {
        
        return $this->user->find($id)->isSuperPowerActivated();
    }

    //this function returns which users liked logged user
    public function whoLiked($userid)
    {
        $whoLiked = $this->encounter->where('user2', '=', $userid)
                               ->whereNotIn('user1', $this->blockUserRepo->getAllBlockedUsersIds($userid) )
                               ->where('likes', '=', 1)
                               ->orderBy('created_at', 'desc')
                               ->get();

        $users = new \stdClass;
        $users->likes = array();
        foreach($whoLiked as $who)
            array_push($users->likes, $this->user->find($who->user1));

        $users->count = count($users->likes);

        return $users;
    }

    // // function that return profile percentage
    // public function profileCompletePercent ($id) {

    //     $count   = 0;
    //     $percent = 5;
    //     $user    = $this->user->find($id);


    //     if (isset($user->city) && isset($user->dob) && isset($user->hereto)) {

    //         $percent+=5;

    //     } elseif (isset($user->city) || isset($user->dob) || isset($user->hereto)) {

    //         $percent+=2;
    //     }


    //     if ($user->profile_pic_url!=null) {

    //         $percent+=20;
    //     }
       

    //     foreach ($user->profile['attributes'] as $key => $value) {

    //         $count++;

    //         if ($count<=7 || $count>20) {

    //             continue;

    //         } else {

    //             if ($value!=null) {

    //                 $percent+=5;
    //             }

    //         }
    //     }


    //     if (isset($user->userinterests->interestid)) {

    //         $percent+=5;
    //     }


    //     return $percent;

        
    // }







    //this method returns all supported languages
    public function getSupportedLanguages () {
        

        $lang_path = base_path() . "/resources/lang";

        $dirs = scandir ($lang_path);

        $langs = [];

        /*  searching for valid language dirctory
            if dirctory contains app.php file then only directory wil be
            treated as valid language directory. 
        */
        foreach ($dirs as $dir) {

            if ($dir == '.' || $dir == '..') {

                continue;
            }
            
            $lang = $lang_path . '/' . $dir;

            //pushing valid language directory into array
            if (file_exists($lang . '/app.php')) {

                array_push($langs, $dir);
            }
                 
        }

        return $langs;
 
    }

}
