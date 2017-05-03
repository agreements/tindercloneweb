<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

//repository use
use Auth;
use Illuminate\Http\Request;
use App\Repositories\CreditRepository;
use App\Repositories\PeopleNearByRepository;
use App\Repositories\EncounterRepository;
use App\Repositories\UserRepository;
use App\Repositories\VisitorRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\Admin\GeneralManageRepository;
use App\Components\Theme;
use App\Components\Plugin;

class PeopleNearByController extends Controller
{
    protected $creditRepo;
    protected $peopleRepo;
    protected $encounterRepo;
    protected $userRepo;
    protected $visitorRepo;
    protected $profileRepo;
    protected $generalRepo;
    
    
    public function __construct(CreditRepository $creditRepo, PeopleNearByRepository $peopleRepo, EncounterRepository $encounterRepo, UserRepository $userRepo, VisitorRepository $visitorRepo, ProfileRepository $profileRepo, GeneralManageRepository $generalRepo)
    {
        $this->creditRepo = $creditRepo;
        $this->peopleRepo = $peopleRepo;
        $this->encounterRepo = $encounterRepo;
        $this->userRepo = $userRepo;
        $this->visitorRepo = $visitorRepo;
        $this->profileRepo = $profileRepo;
        $this->generalRepo = $generalRepo;
        $this->utilityRepo = app('App\Repositories\Admin\UtilityRepository');
    }


    public function showPeople(Request $request) {
    
        $auth_user = Auth::user();
        $arr = explode('-', $auth_user->profile->prefer_age);




	/*	$nearByUsers =  \Cache::remember('user_' . $auth_user->id . '_peoplenearby', 10*60, function() use($auth_user, $arr)
    {
	     $nearByUsers = $this->peopleRepo->getNearbyPeoples(
            $auth_user->id, 
            $auth_user->profile->prefer_gender, 
            $arr[0], $arr[1], 
            $auth_user->profile->prefer_distance_nearby
        );
        
        return serialize($nearByUsers);
        
    }); */
    
    //$nearByUsers = unserialize($nearByUsers);
    
    

 $nearByUsers = $this->peopleRepo->getNearbyPeoples(
            $auth_user->id, 
            $auth_user->profile->prefer_gender, 
            $arr[0], $arr[1], 
            $auth_user->profile->prefer_distance_nearby
        ); 
        
       // dd($nearByUsers);
       

        $nearByUsers = $this->peopleRepo->paginate($nearByUsers,$request->page);  

        $custom_filter_data = new \stdClass;
        $custom_filter_data->prefered_genders = $this->peopleRepo->getPreferedGenders($auth_user);
        $custom_filter_data->prefered_ages = $auth_user->profile->prefer_age;
        $custom_filter_data->prefered_distance = $auth_user->profile->prefer_distance_nearby;

        $search_fields = $this->peopleRepo->getAllSearchFields();
        
        foreach ($search_fields as $field) {
                
            $user_preference   = $this->peopleRepo->getPreference($auth_user->id, $field->id);
            $field->isSelected = ($user_preference && $user_preference->prefer_value != '') ? true : false;
            $perfer_value = (isset($user_preference->prefer_value)) 
                            ? $user_preference->prefer_value
                            : '';
            $field->options    = $this->peopleRepo->getFieldOptions($field, $perfer_value);
        }
        
        $custom_filter_data->search_fields = $search_fields;



        return Theme::view('plugin.PeopleNearByPlugin.peoplenearby', [
            'logUser'                              => $auth_user,
            'nearByUsers'                          => $nearByUsers,
            'custom_filter_data'                   => $custom_filter_data,
            "filter_distance_unit"                 => $this->utilityRepo->get_setting('filter_distance_unit'),
            "filter_distance"                      => $this->utilityRepo->get_setting('filter_distance'),
            "filter_range_min"                     => $this->utilityRepo->get_setting('filter_range_min'),
            "filter_range_max"                     => $this->utilityRepo->get_setting('filter_range_max'),
            "filter_non_superpowers_range_enabled" => $this->utilityRepo->get_setting('filter_non_superpowers_range_enabled')
        ]);

    }



    public function set_location (Request $request) {

       try {


            $user = Auth::user();


            if (!$request->city || !$request->country || !$request->lat || !$request->long) {


               return response()->json([

                   'status'  => 'error', 
                   'message' => trans('app.failed_save_location')

               ]);


            }


           if ($user->latitude == null  || $user->longitude == null) {



               $user->city               = $request->city;
               $user->country            = $request->country;
               $user->latitude           = $request->lat;
               $user->longitude          = $request->long;
               $user->save();
               
               $user->profile->latitude  = $request->lat;
               $user->profile->longitude = $request->long;

               $user->profile->save();



           } else {


               $user->profile->latitude  = $request->lat;
               $user->profile->longitude = $request->long;
               $user->profile->save();

           }



           return response()->json([

               'status'  => 'success', 
               'message' => trans('app.success_save_location')

           ]);



       } catch (\Exception $e) {

           return response()->json([

               'status'  => 'error', 
               'message' => trans('app.failed_save_location')

           ]);

       }
     }
    




    public function getRiseupCredits () {

        try {


            $riseupCredits = $this->peopleRepo->getRiseupCredits();

            if (!$riseupCredits) {

                return response()->json([

                        'status'  => 'success', 
                        'credits' => 0,
                    
                    ]);           

            }


            return response()->json([

                'status'  => 'success', 
                'credits' => $riseupCredits
            
            ]); 


        } catch (\Exception $e) {

            return response()->json([

                    'status'  => 'error', 
                    'credits' => 0,
                
                ]);
        }

    


    }
    






    
    //this function pays for user's rise up money
    public function payRiseUp () {

        try {


            $user          = Auth::user();
            $riseupCredits = $this->peopleRepo->getRiseupCredits();

            if (!$riseupCredits) {

               $riseupCredits = 0;

            }


            //checking the user whether user has enough balance to pay the riseup money
            if ( $riseupCredits <= $user->credits->balance ) {

                $this->peopleRepo->payRiseUp($user->id, $riseupCredits);

                Plugin::fire('riseup_pay', $user->credits->balance, $riseupCredits);

                return response()->json([

                    'status'  => 'success', 
                    'message' => trans('app.success_paid_riseup')
                
                ]);


            } else {

                return response()->json([

                    'status'  => 'error', 
                    'message' => trans('app.not_enough_credits')
                
                ]);
            }





        } catch (\Exception $e) {


            return response()->json([

                'status'  => 'error', 
                'message' => trans('app.failed_pay_riseup')
                
            ]);

        }

        
        
    }




    public function searchFilter (Request $request) {   
        
        $auth_user = Auth::user();

        $prefer_gender   = $request->prefer_gender; //gender string with (,) seperated eg. "male,female"
        $prefer_age      = $request->prefer_age; //age string with (-) seperated eg. "18-80 without spaces"
        $prefer_distance = $request->prefer_distance; //numeric

        if (strlen($prefer_gender) > 0 
            && preg_match("/^[0-9]+[-][0-9]+$/", $prefer_age)
            && preg_match("/^[0-9]+$/", $prefer_distance)) 
        {
            $this->peopleRepo->setFilter( $auth_user, [
                "prefer_gender"   => $prefer_gender,
                "prefer_age"      => $prefer_age,
                "prefer_distance" => $prefer_distance,
            ]);


            if(isset($request->for_encounter)) {
                return response()->json(['status' => 'success']);
            }


        } else {
            return response()->json(['status' => 'error']);
        }

        $sections = $this->profileRepo->get_fieldsections();
        $user_preferences = [];
        foreach($sections as $section) {
            foreach($section->fields as $field) {

                $code = $field->code;
                if ($field->on_search == 'yes') {

                    $user_preferences[$field->id] = '';

                    if ($field->on_search_type == 'range' 
                        && isset($request->$code)
                        && preg_match("/^\d*\.?\d*[-]\d*\.?\d*$/", $request->$code)) {

                        $user_preferences[$field->id] = $request->$code;

                    } else if ($field->on_search_type == 'dropdown' 
                                && isset($request->$code)
                                && strlen($request->$code) > 0) {
                        $user_preferences[$field->id] = $request->$code;
                    }
                    
                }
            }
        }

        $this->peopleRepo->savePreferenceFields($auth_user->id, $user_preferences);
        return response()->json(['status' => 'success']);
    }



    public function setPreferOnlineStatus (Request $req) {
        $prefer_online_status = $req->prefer_online_status;
        $auth_user = Auth::user();

        if ($prefer_online_status != 'all' && $prefer_online_status != 'online') {
            return response()->json(['status' => 'error']);
        }
        
        $profile = $auth_user->profile;
        $profile->prefer_online_status = $prefer_online_status;
        $profile->save();

        return response()->json(['status' => 'success']);
    }
    
}
