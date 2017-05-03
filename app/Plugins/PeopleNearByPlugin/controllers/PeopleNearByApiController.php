<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

//repository use
use Illuminate\Http\Request;
use App\Repositories\CreditRepository;
use App\Repositories\PeopleNearByRepository;
use App\Repositories\EncounterRepository;
use App\Repositories\UserRepository;
use App\Repositories\VisitorRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\Admin\GeneralManageRepository;
use App\Components\Plugin;
use \Illuminate\Pagination\LengthAwarePaginator as LengthAwarePaginator;

class PeopleNearByApiController extends Controller {

    protected $creditRepo;
    protected $peopleRepo;
    protected $encounterRepo;
    protected $userRepo;
    protected $visitorRepo;
    protected $profileRepo;
    protected $generalRepo;
    
    
    public function __construct () {
        $this->creditRepo    = app("App\Repositories\CreditRepository");
        $this->peopleRepo    = new PeopleNearByRepository;
        $this->encounterRepo = new EncounterRepository;
        $this->userRepo      = app("App\Repositories\UserRepository");
        $this->visitorRepo   = new VisitorRepository;
        $this->profileRepo   = app("App\Repositories\ProfileRepository");
        $this->generalRepo   = new GeneralManageRepository;
    }


    public function peoplenearby (Request $req) {
    
        $auth_user = $req->real_auth_user;

        $prefered_ages = explode('-', $auth_user->profile->prefer_age);
        $prefered_genders = explode(",", $auth_user->profile->prefer_gender);
        $prefered_distance = $auth_user->profile->prefer_distance_nearby;

        $filter_data = [
            "perfered_ages" => [
                "min" => $prefered_ages[0],
                "max" => $prefered_ages[1]
            ],
            "prefered_genders" => $prefered_genders,
            "prefered_online_status" => $auth_user->profile->prefer_online_status ?: "all",
            "perfered_distance" => [
                "value" => $prefered_distance,
                "unit" => "km"
            ]
        ];

        $nearByUsers = $this->peopleRepo->getNearbyPeoples(
            $auth_user->id, 
            $auth_user->profile->prefer_gender, 
            $prefered_ages[0], $prefered_ages[1], 
            $auth_user->profile->prefer_distance_nearby
        );
    

        $nearByUsers = $this->paginate($nearByUsers, $req->page);  

        $users = [];
        foreach ($nearByUsers as $user) {

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

            $user->raised = isset($user->riseup->updated_at) ? 'true' : 'false'; 
            unset($user->riseup);

            $populatiry = $user->profile->populatiry;
            $user->populatiry = [
                "value" => $populatiry?:"0",
                "type" => $this->profileRepo->getPopularityType($populatiry)
            ];

            $user->credit_balance = $user->credits->balance;
            unset($user->profile);
            unset($user->credits);

            array_push($users, $user);
        }


        $current_page_url = $nearByUsers->url($nearByUsers->currentPage())?url($nearByUsers->url($nearByUsers->currentPage())):"";
        $next_page_url = $nearByUsers->nextPageUrl()?url($nearByUsers->nextPageUrl()):"";
        $prevous_page_url = $nearByUsers->previousPageUrl() ?url($nearByUsers->previousPageUrl()):"";
        $last_page_url = $nearByUsers->url($nearByUsers->lastPage())?url($nearByUsers->url($nearByUsers->lastPage())):"";

        return response()->json([
            "status" => "success",
            "success_data" => [
                "riseup_credits" => $this->peopleRepo->getRiseupCredits() ?: 0,
                "filter_data" => $filter_data,
                "nearby_users" => $users,
                "paging" => [
                    "total" => $nearByUsers->total(),
                    "current_page_url" => $current_page_url,
                    "more_pages" => $nearByUsers->hasMorePages() ? "true" : "false",
                    "prevous_page_url" => $prevous_page_url,
                    "next_page_url" => $next_page_url,
                    "last_page_url" => $last_page_url
                ],
                "success_text" => "Nearby peoples retrieved successfully."
            ]
        ]);

    }

    public function paginate ($users, $curpage) {

        if ($curpage > 0) {
            $curpage -= 1;
        }
        $users = $users->get();

        $total = count($users);
        $perpage = 10;

        $users = $users->slice ( ($curpage * $perpage), $perpage);

        $users = new LengthAwarePaginator ($users, $total, $perpage);
        
        $users->setPath('api/people-nearby');
        return $users;
    }


    public function setProfileLocation (Request $req) {

        $auth_user = $req->real_auth_user;
 
        if (!$req->city || $req->city == 'undefined'
            || !$req->country 
            || !$req->lat 
            || !$req->long) {

            return response()->json([
                'status' => 'error',
                "error_data" => [
                    "error_text" => "All fields are required."
                ]
            ]); 
        }

        $auth_user->profile->latitude  = $req->lat;
        $auth_user->profile->longitude = $req->long;
        $auth_user->profile->save();

        return response()->json([
            "status" => "success",
            "success_data" => [
                "success_text" => "Profile location saved successfully."
            ]
        ]);

    
     }
    
    
    //this function pays for user's rise up money
    public function payRiseUp (Request $req) {

        $auth_user = $req->real_auth_user;
        $user_balance = $auth_user->credits->balance;

        $riseup_credits = $this->peopleRepo->getRiseupCredits();
        $riseup_credits = $riseup_credits ?: 0;

        if ($riseup_credits > $user_balance) {
            return response()->json([
                'status' => 'error',
                "error_data" => [
                    "error_text" => "User credit balance is not sufficient."
                ]
            ]); 
        }
        
        $this->peopleRepo->payRiseUp($auth_user->id, $riseup_credits);
        Plugin::fire('riseup_pay', $user_balance, $riseup_credits);

        return response()->json([
            "status" => "success",
            "success_data" => [
                "user_credit_balance" => ($user_balance - $riseup_credits),
                "success_text" => "Riseup payment successfull."
            ]
        ]);
  
    }




    public function saveSearchFilter (Request $req) {   
        
        $auth_user = $req->real_auth_user;

        $prefer_gender   = $req->prefered_genders; //gender string with (,) seperated eg. "male,female"
        $prefer_age      = $req->prefered_ages; //age string with (-) seperated eg. "18-80 without spaces"
        $prefer_distance = $req->prefered_distance; //numeric

        
        
        if (!$this->validPreferedGenders($prefer_gender)) {
            return response()->json([
                'status' => 'error',
                "error_data" => [
                    "perfered_genders" => "Check format",
                    "error_text" => "Validation error"
                ]
            ]);
        }

        if (!preg_match("/^[0-9]+[-][0-9]+$/", $prefer_age)) {
            return response()->json([
                'status' => 'error',
                "error_data" => [
                    "perfered_ages" => "Check format",
                    "error_text" => "Validation error"
                ]
            ]);
        }

        if (!preg_match("/^[0-9]+$/", $prefer_distance)) {
            return response()->json([
                'status' => 'error',
                "error_data" => [
                    "perfered_distance" => "Check format",
                    "error_text" => "Validation error"
                ]
            ]);
        }

        $this->peopleRepo->setFilter( $auth_user, [
            "prefer_gender"   => $prefer_gender,
            "prefer_age"      => $prefer_age,
            "prefer_distance" => $prefer_distance,
        ]);

        $sections = $this->profileRepo->get_fieldsections();
        $user_preferences = [];
        foreach($sections as $section) {
            foreach($section->fields as $field) {

                $code = $field->code;
                if ($field->on_search == 'yes') {

                    $user_preferences[$field->id] = '';

                    if ($field->on_search_type == 'range' 
                        && isset($req->$code)
                        && preg_match("/^\d*\.?\d*[-]\d*\.?\d*$/", $req->$code)) {

                        $user_preferences[$field->id] = $req->$code;

                    } else if ($field->on_search_type == 'dropdown' 
                                && isset($req->$code)
                                && strlen($req->$code) > 0) {
                        $user_preferences[$field->id] = $req->$code;
                    }
                    
                }
            }
        }

        $this->peopleRepo->savePreferenceFields($auth_user->id, $user_preferences);
        return response()->json([
            "status" => "success",
            "success_data" => [
                "success_text" => "Search filter saved successfully."
            ]
        ]);
    }



    public function saveSearchFilterOnlineStatus (Request $req) {
        $prefer_online_status = $req->prefered_online_status;
        $auth_user = $req->real_auth_user;

        if (!in_array($prefer_online_status, ["all", "online"])) {
            return response()->json([
                'status' => 'error',
                "error_data" => [
                    "perfered_online_status" => "Must be online or all",
                    "error_text" => "Validation error"
                ]
            ]);
        }
        
        $profile = $auth_user->profile;
        $profile->prefer_online_status = $prefer_online_status;
        $profile->save();

        return response()->json([
            "status" => "success",
            "success_data" => [
                "success_text" => "Search filter prefered online status saved successfully."
            ]
        ]);
    }
    

    protected function validPreferedGenders ($prefered_genders) {

        if (strlen($prefered_genders) < 1) {
            return false;
        }

        $gender_field     = (new GeneralManageRepository)->getGenderField();
        
        $genders = [];
        foreach ($gender_field->field_options as $option) {
            array_push($genders, $option->code);
        }

        $prefer_genders   = explode(',', $prefered_genders);
        foreach ($prefer_genders as $gender) {
            if (!in_array($gender, $genders)) {
                return false;
            }
        }
        
        return true;
    }
}
