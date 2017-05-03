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
use App\Models\UserSettings;
use App\Models\Transaction;
use App\Models\Fields;
use DB;
use App\Repositories\BlockUserRepository;
use App\Repositories\NotificationsRepository;
use App\Repositories\SuperpowerRepository;
use App\Repositories\Admin\UtilityRepository;

use App\Models\SuperpowerHistory;
use App\Components\Plugin;

use stdClass;

class EncounterRepository
{
    private $blockUserRepo;
    private $superpowerRepo;
    public function __construct()
    {
        $this->blockUserRepo = app("App\Repositories\BlockUserRepository");
        $this->superpowerRepo = app("App\Repositories\SuperpowerRepository");
        $this->peopleRepo = app('App\Repositories\PeopleNearByRepository');
        $this->settings = app('App\Models\Settings');
        $this->fields = app("App\Models\Fields");
        $this->profileRepo = app('App\Repositories\ProfileRepository');
        $this->user = app('App\Models\User');
    }
    
    public function insertNotif($from_user,$to_user,$type,$entity_id)
    {
        $notif = new Notifications;
        $notif->from_user = $from_user;
        $notif->to_user = $to_user;
        $notif->type = $type;
        $notif->status = "unseen";
        $notif->entity_id = $entity_id;
        $notif->save();
    }

    public function getTotalEncounters($id)
    {
        $count = Encounter::where('user1','=',$id)->where('created_at','like',date('Y-m-d').'%')->count();
        return $count;
    }


    public function defaultGenderPictures()
    {
        $default_gender_pics = [];
        
        $gender = $this->fields->getGenderField();

        foreach($gender->field_options as $option) {
            $default_gender_pics[] = $this->settings->get("default_{$option->code}");
        }

        return $default_gender_pics;
    }

    public function dobFilterRange($userPreferAge)
    {
        $preferAge = $userPreferAge;
        $arr = explode('-', $preferAge); 
        $end = (date('Y')-$arr[1]) . '-'.date('m').'-'.date('d');
        $start = (date('Y')-$arr[0]) . '-'.date('m').'-'.date('d');     

        $ageRange[] = $end; 
        $ageRange[] = $start; 

        return $ageRange;
    }

    public function perferGendersArray($userPreferGender)
    {
         return explode(',', $userPreferGender);
    }

    /*this function will return next encounter user 
    and also filter encouter users as per age and gender prefered by log user 
    */
    public function nextEncounterUser ($logUser , $flag) {

        $encounterUser = null;
        
        $user_profile = $logUser->profile;

        $blockedIds = $this->blockUserRepo->getAllBlockedUsersIds($logUser->id);
        $blockedIds[] = $logUser->id;


        $log_user_lat = $log_user_lng = 0;

        if ($user_profile->latitude && $user_profile->longitude) {
            $log_user_lat = $user_profile->latitude;
            $log_user_lng = $user_profile->longitude;
        } else if ($logUser->latitude && $logUser->longitude) {
            $log_user_lat = $logUser->latitude;
            $log_user_lng = $logUser->longitude;
        }
      
        $users = $this->peopleRepo->getUsersByRadious($log_user_lat, $log_user_lng, $user_profile->prefer_distance_nearby);
        $users = $users->whereRaw("user.id NOT IN (SELECT user2 FROM encounter WHERE user1 = {$logUser->id})")
                        ->whereNotIn('user.id', $blockedIds)
                        ->where('user.activate_user', '<>', 'deactivated') ////removing all deactivated users
                        ->whereNotIn('user.profile_pic_url', $this->defaultGenderPictures())
                        ->whereIn('user.gender', $this->perferGendersArray($user_profile->prefer_gender))
                        ->whereBetween('user.dob', $this->dobFilterRange($user_profile->prefer_age))
                        ->select('user.*');

        if($users->count() == 0) {
            $users = $this->user->whereRaw("user.id NOT IN (SELECT user2 FROM encounter WHERE user1 = {$logUser->id})")
                        ->whereNotIn('user.id', $blockedIds)
                        ->where('user.activate_user', '<>', 'deactivated') ////removing all deactivated users
                        ->whereNotIn('user.profile_pic_url', $this->defaultGenderPictures())
                        ->whereIn('user.gender', $this->perferGendersArray($user_profile->prefer_gender))
                        ->whereBetween('user.dob', $this->dobFilterRange($user_profile->prefer_age))
                        ->select('user.*');
        }

        

        /* order by common intersts count */
        $users = $users->take(10)->get();
        foreach($users as $user) {
            $user->common_interest_count = $this->profileRepo->getCommonInterestsCount($logUser->id, $user->id);
            $user->common_friends_count = $this->profileRepo->getTotalFacebookMutualFriendsCount($logUser->id, $user->id);
        }

        $users = $users->sortByDesc('common_interest_count');            
      


        return $flag ? $users : $users->first();
                            


        // sort users based on their city first 

        // $log_user_lat = $log_user_lng = 0;

        // if ($logUser->profile->latitude && $logUser->profile->longitude) {
        //     $log_user_lat = $logUser->profile->latitude;
        //     $log_user_lng = $logUser->profile->longitude;
        // } else if ($logUser->latitude && $logUser->longitude) {
        //     $log_user_lat = $logUser->latitude;
        //     $log_user_lng = $logUser->longitude;
        // }

        // $calculate_distance_query_with_profile = "(((acos(sin((".$log_user_lat."*pi()/180)) * sin((profile.latitude*pi()/180))+cos((".$log_user_lat."*pi()/180)) * cos((profile.latitude*pi()/180)) * cos(((".$log_user_lng."- profile.longitude)*pi()/180))))*180/pi())*60*1.1515*1.609344)";

        // $calculate_distance_query_with_user = "(((acos(sin((".$log_user_lat."*pi()/180)) * sin((user.latitude*pi()/180))+cos((".$log_user_lat."*pi()/180)) * cos((user.latitude*pi()/180)) * cos(((".$log_user_lng."- user.longitude)*pi()/180))))*180/pi())*60*1.1515*1.609344)";


        // $users = $users->join('profile', 'user.id', '=', 'profile.userid')
        //                 ->select(["user.*", DB::raw("(CASE WHEN profile.latitude IS NOT NULL AND profile.longitude IS NOT NULL THEN $calculate_distance_query_with_profile ELSE $calculate_distance_query_with_user END) as distance")])->orderBy('distance', 'asc')->having('distance', '>', '0');

        /* end sort users based on their city first */

        


        /* sort users based on their interests count first */

        
    }

    //this funciton will create a new encounter row
    public function createEncounter($fromUser, $toUser, $likeOrDislike)
    {
        //searching for blocked users
        $blockedIds = $this->blockUserRepo->getAllBlockedUsersIds($fromUser);
        
        if(!array_search( $toUser, $blockedIds))
        {

            $encountered = Encounter::where("user1", $fromUser)->where('user2', $toUser)->first();

            if (!$encountered) {
                $encounter = new Encounter;
                $encounter->user1 = $fromUser;
                $encounter->user2 = $toUser;
                $encounter->likes = $likeOrDislike;
                $encounter->save();
            
                return $encounter;
            }

            return null;
        }
        
        return null;
    }


    public function getEncounterCount ($id) {

        if ($this->superpowerRepo->isSuperPowerActivated($id)) { 
            return 9999; 
        } else {

            $encountered_users_count = Encounter::where('user1','=',$id)
                                                ->where('created_at','like',date('Y-m-d').'%')
                                                ->count();

            $encounter_limit = Settings::_get('limit_encounter');
            
            if ($encounter_limit == '') { return 9999; }
            
            $left = $encounter_limit - $encountered_users_count;
            $left = ($left <= 0) ? 0 : $left;
            return $left;
        }
    }

    /* This function will check $formUser has liked $toUser or not 
    .. if true then it will return that particular Encounter object row form encounter table
    */
    public function getMutualMatch($fromUser, $toUser)
    {
        $temp = Encounter::where('user1', '=', $fromUser)
                                ->where('user2', '=', $toUser)
                                ->where('likes', '=', 1)->first();
        
        return $temp;
    }
    
    
    //This function will insert a new row to match table and return the match object
    public function createMatch($fromUser, $toUser)
    {
        $mat = new Match;
        $mat->user1 = $fromUser;
        $mat->user2 = $toUser;
        $mat->save();

        $encounter_user_match_setting = (new NotificationsRepository)->getNotifSettingsByType($toUser, 'match');

        if (!$encounter_user_match_setting || $encounter_user_match_setting->browser == 1) {
            $this->insertNotif($fromUser, $toUser, 'match', $fromUser);
        }

        return $mat;
    }

    //this funciton returns all matched userd of $id passed into.
    public function getAllMatchedUsers($id)
    {
        $matches = Match::join('user', 'user.id', '=', 'matches.user2')
                    ->where('user.activate_user', '!=', 'deactivated')
                    ->where('matches.user1',"=",$id)
                    ->orderBy('matches.created_at', 'desc')
                    ->select('matches.*')
                    ->paginate(9);
        $matches->setPath('matches');

        $matches->count = count($matches);
        
        return $matches;
    }
    
    
    //this function returns all the liked  by the id passed into
    public function getAllLikes($id)
    {
        $likes = Encounter::join('user', 'user.id', '=', 'encounter.user2')
                            ->where('user.activate_user', '!=', 'deactivated')
                            ->where("encounter.user1","=", $id)
                            ->where("encounter.likes","=",1)
                            ->orderBy('encounter.created_at', 'desc')
                            ->select('encounter.*')
                            ->paginate(9);

        $likes->setPath('iliked');
        $likes->count = count($likes);
                
        return $likes;
    }

    //this function returns which users liked logged user
    public function whoLiked ($userid) {

        $likedMeUsers = User::join('encounter', 'user.id', '=', 'encounter.user1')
                        ->select('user.*')
                        ->where('encounter.user2', '=', $userid)
                        ->where('encounter.likes', '=', 1)
                        ->where('user.activate_user', '!=', 'deactivated')
                        ->orderBy('encounter.created_at', 'desc')
                        ->paginate(9);

        return $likedMeUsers;
    }

    public function countTotalLikedMe($userId)
    {
        return Encounter::join('user', 'user.id', '=', 'encounter.user1')
                        ->where('encounter.user2', '=', $userId)
                        ->where('encounter.likes', '=', 1)
                        ->where('user.activate_user', '!=', 'deactivated')
                        ->count();
    }

}
