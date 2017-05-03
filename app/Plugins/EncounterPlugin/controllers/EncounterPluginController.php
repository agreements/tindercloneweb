<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\TestModel;

use App\Components\Plugin;
use App\Components\Theme;
use Illuminate\Http\Request;
use App\Models\Notifications;
use App\Models\NotificationSettings;
use App\Repositories\RegisterRepository;
use App\Repositories\CreditRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\EncounterRepository;
use App\Repositories\UserRepository;
use App\Repositories\VisitorRepository;
use App\Repositories\SuperpowerRepository;
use App\Repositories\NotificationsRepository;
use Auth;
use stdClass;
use App\Models\User;
use App\Models\Settings;
use App\Models\EmailSettings;

class EncounterPluginController extends Controller
{
    protected $registerRepo;
    protected $creditRepo;
    protected $superpowerRepo;
    protected $encounterRepo;
    protected $userRepo;
    protected $visitorRepo;
    protected $profileRepo;
    protected $notifRepo;
    
    public function __construct(ProfileRepository $profileRepo, RegisterRepository $registerRepo, CreditRepository $creditRepo, EncounterRepository $encounterRepo,UserRepository $userRepo, VisitorRepository $visitorRepo, SuperpowerRepository $superpowerRepo, NotificationsRepository $notifRepo)
    {
        $this->registerRepo     = $registerRepo;
        $this->creditRepo       = $creditRepo;
        $this->profileRepo      = $profileRepo;
        $this->encounterRepo    = $encounterRepo;
        $this->userRepo         = $userRepo;
        $this->superpowerRepo   = $superpowerRepo;
        $this->visitorRepo      = $visitorRepo;
        $this->notifRepo        = $notifRepo;
        $this->peoplenearbyRepo = app('App\Repositories\PeopleNearByRepository');
        $this->utilityRepo = app('App\Repositories\Admin\UtilityRepository');
        
    }
    
    //this function render home view
    public function showHome()
    {  

        $auth_user = Auth::user();
        $like = $this->encounterRepo->getAllLikes($auth_user->id);
        $auth_user->photos_count = $auth_user->photos->count();
        $encounterUser = $this->encounterRepo->nextEncounterUser($auth_user , false);
        $already_encountered = $this->encounterRepo->getTotalEncounters($auth_user->id);
        $sections = $this->profileRepo->get_fieldsections();
        
        if ($encounterUser) {
            $likes = $this->encounterRepo->getMutualMatch($encounterUser->id, $auth_user->id);
            $encounterUser->isLiked = ($likes) ? $likes->likes : 0;    
            $encounterUser->socialAccountVerifications = $encounterUser->socialAccountVerifications();
        } 

        $custom_filter_data = new \stdClass;
        $custom_filter_data->prefered_genders  = $this->peoplenearbyRepo->getPreferedGenders($auth_user);
        $custom_filter_data->prefered_ages     = $auth_user->profile->prefer_age;
        $custom_filter_data->prefered_distance = $auth_user->profile->prefer_distance_nearby;
        
        $encounter_list = $this->encounterRepo->nextEncounterUser($auth_user, true);

        return Theme::view('plugin.EncounterPlugin.home', [
                'user'                                 => $encounterUser, 
                'sections'                             => $sections,
                'like'                                 => $like,
                'already_encountered'                  => $already_encountered,
                "page"                                 => "encounter",
                'custom_filter_data'                   => $custom_filter_data,
                'encounter_list'                       => $encounter_list,
                "filter_distance_unit"                 => $this->utilityRepo->get_setting('filter_distance_unit'),
                "filter_distance"                      => $this->utilityRepo->get_setting('filter_distance'),
                "filter_range_min"                     => $this->utilityRepo->get_setting('filter_range_min'),
                "filter_range_max"                     => $this->utilityRepo->get_setting('filter_range_max'),
                "filter_non_superpowers_range_enabled" => $this->utilityRepo->get_setting('filter_non_superpowers_range_enabled')
        ]);

    }


 //this function renders liked view
    public function liked()
    {
        $logId = Auth::user()->id;
        $visit = $this->visitorRepo->getAllVisitors($logId);
        $matches = $this->encounterRepo->getAllMatchedUsers($logId);      
        $likes = $this->encounterRepo->getAllLikes($logId);
        $whoLiked = $this->encounterRepo->whoLiked($logId);
  
        return Theme::view('plugin.EncounterPlugin.liked',   array('like' => $likes, 
                                            'logUser' => $this->userRepo->getUserById($logId),
                                            'visit' => $visit,
                                'wholiked'=>$whoLiked,
                                            'matches' => $matches));
            
    }


    /* this funciton will create an entry in encounter table for liked or disliked
       and also create a entry in matches table for finding further mathched users
    */
    public function isLiked($id, $val)
    {
        $var = null;
        
        $fromUser = Auth::user()->id;
        $toUser = $id;
        $likeOrDislike = $val;
        
        $encounter = $this->encounterRepo->createEncounter($fromUser, $toUser, $likeOrDislike);

        Plugin::fire('user_encountered', [$this->userRepo->getUserById($toUser), $likeOrDislike]);

        if($val == "1") 
        {
            
            $var = $this->encounterRepo->getMutualMatch($encounter->user2, $encounter->user1);
            
            $this->encounterRepo->insertNotif($fromUser,$toUser,'liked',$toUser);
            
            $user1 = Auth::user();
            $user2 = $this->userRepo->getUserById($toUser);

            // $set = $this->notifRepo->getNotifSettingsByType($toUser,'liked');
            // $email_set = $this->userRepo->getEmailSettings('liked');

            // if($email_set && ($set == null || $set->email == 1))
            // {
                $email_array = new stdCLass;
                $email_array->user = $user2;
                $email_array->user2 = $user1;
                $email_array->type = 'liked';
                Plugin::Fire('send_email', $email_array);

            if($var != null)
            {
                Plugin::fire('match_found',$toUser);

                    $email_array = new stdCLass;
                    $email_array->user = $user2;
                    $email_array->user2 = $user1;
                    $email_array->type = 'match';
                    Plugin::Fire('send_email', $email_array);
                 
                $this->encounterRepo->createMatch($encounter->user1, $encounter->user2);
                
                $this->encounterRepo->createMatch($encounter->user2, $encounter->user1);
            }
        }
    }


    public function doEncounter () {

        try {

            //if encounter fails anyway next encounter comes

            $users = $this->encounterRepo->nextEncounterUser(Auth::user(), true);

            if (!$users) {

                return response()->json(['status' => 'no encounter']);
            }

            $encounters_list = [];

            foreach ($users as $user) {
               
                $user->socialAccountVerifications = $user->socialAccountVerifications();
                $user->profile_pic_url = $user->thumbnail_pic_url();
                $user->age = $user->age();
                $user->profile;
                
                $user->onlineStatus = $user->onlineStatus();
                $user->is_social_verified = $user->is_social_verified();

                $photos = new \stdClass;
                
                $photos->count = count($user->photos);
                $photos->items = array();
          
                if ($photos->count > 0) {
                    
                    foreach ($user->photos as $photo) {

                        $item  = new \stdClass;

                        $item->id = $photo->id;
                        $item->url = $photo->encounter_photo_url();

                        $item->nudity = isset($photo->nudity) ? $photo->nudity : 0;
                        $item->is_checked = isset($photo->is_checked) ? $photo->is_checked : 0;

                        array_push ($photos->items, $item);

                    }

                }
                
                unset($user->photos);
          
                $liked = $this->encounterRepo->getMutualMatch($user->id, Auth::user()->id);
                $isLiked = ($liked) ? $liked->likes : 0;
          
                array_push($encounters_list, [ 'user' => $user, 'photos' => $photos, 'islikedme' => $isLiked ] );

            }
             $encounter_left = $this->encounterRepo->getEncounterCount( Auth::user()->id);

            return response()->json([ 'encounters_list' => $encounters_list,"encounters_left" =>$encounter_left]);




        } catch (\Exception $errorEncounter) {

            return response()->json(['status' => $errorEncounter->getMessage()]);

        }

    }



    //this function render match view
    public function match()
    {
        $user = Auth::user();

        $logId = $user->id;
        $visit = $this->visitorRepo->getAllVisitors($logId);
        $match = $this->encounterRepo->getAllMatchedUsers($logId);       
        $like = $this->encounterRepo->getAllLikes($logId);
        $whoLiked = $this->encounterRepo->whoLiked($logId);
        //checking whether users super power activated  or not
        $this->notifRepo->clearNotifs("match");
        if( $this->superpowerRepo->isSuperPowerActivated($user->id) )
        {
             return Theme::view('plugin.EncounterPlugin.matches', array('matches' => $match, 
                                            'activated' => true,
                                            'visit' => $visit, 
                                            'like' => $like, 
                                            'wholiked'=> $whoLiked,
                                            'logUser' => $user));
        }
        else
        {
             return Theme::view('plugin.EncounterPlugin.matches', array('matches' => $match, 
                                            'activated' => false,
                                            'visit' => $visit, 
                                            'like' => $like, 
                                            'wholiked'=>$whoLiked,
                                            'logUser' => $user));
        }
        
     
    }


    //this function shows who liked logged in user
    public function whoLiked()
    {

        $auth_user = Auth::user();
        
        $whoLiked = $this->encounterRepo->whoLiked($auth_user->id);  
        $total_count_likedme = $this->encounterRepo->countTotalLikedMe($auth_user->id);
        $canSeeLikedMe = $auth_user->isSuperPowerActivated();
        $this->notifRepo->clearNotifs("liked");


        return Theme::view('plugin.EncounterPlugin.who_liked', [
                'wholiked' => $whoLiked,
                'can_see_liked_me' => $canSeeLikedMe,
                'total_count_likedme' => $total_count_likedme 
        ]);   
        
    }
}
