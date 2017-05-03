<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Validator;
use stdCLass;
use DB;

use App\Models\Settings;
use App\Models\NotificationSettings;
use App\Models\UserSuperPowers;
use App\Models\User;
use App\Models\Notifications;
use App\Models\EmailSettings;

use App\Repositories\UserRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\CreditRepository;
use App\Repositories\VisitorRepository;
use App\Repositories\EncounterRepository;
use App\Repositories\SuperpowerRepository;
use App\Repositories\NotificationsRepository;
use App\Repositories\Admin\UtilityRepository;

use App\Components\Theme;
use App\Components\Plugin;




class ProfileController extends Controller
{

	protected $profileRepo;
	protected $userRepo;
	protected $creditRepo;
	protected $visitorRepo;
	protected $encounterRepo;
	protected $superpowerRepo;
	protected $notifRepo;

	public function __construct(ProfileRepository $profileRepo, 
								UserRepository $userRepo,
								CreditRepository $creditRepo,
								VisitorRepository $visitorRepo,
								SuperpowerRepository $superpowerRepo,
								NotificationsRepository $notifRepo,
								EncounterRepository $encounterRepo) 
	{
		
		$this->profileRepo   = $profileRepo;
		$this->userRepo      = $userRepo;
		$this->creditRepo    = $creditRepo;
		$this->visitorRepo   = $visitorRepo;
		$this->encounterRepo = $encounterRepo;
		$this->superpowerRepo = $superpowerRepo;
		$this->notifRepo = $notifRepo;
	}



	//this method will update personal information 
	public function saveUserFields (Request $request) {
		
		$arr = $request->all();

 		try{
			$this->profileRepo->saveUserFields(Auth::user()->id,$arr);
			return response()->json(['status' => 'success']);

		} catch (\Exception $e) {


			return response()->json(['status' => 'error']);
		}

	}


	public function save_invisible_settings(Request $request)
    {
        $sup = UserSuperPowers::where('user_id','=',Auth::user()->id)->first();
        if($sup)
        {
        	$sup->invisible_mode = $request->hide_visitors;
        	$sup->hide_superpowers = $request->hide_superpowers;
        	$sup->save();
        }
    }

	public function activate_invisible_mode()
	{
		$sup = UserSuperPowers::where('user_id', '=', Auth::user()->id)->first();
		if($this->superpowerRepo->isSuperPowerActivated(Auth::user()->id))
		{
			$sup->invisible_mode = 1;
			$sup->save();
		}
		else
			return redirect('/credits');
	}

	public function deactivate_invisible_mode()
	{
		$sup = UserSuperPowers::where('user_id', '=', Auth::user()->id)->first();
		$sup->invisible_mode = 0;
		$sup->save();
		return back();
	}
	

	//this method only store personal information of profile table instead of aboutme 
	

	//uploading other photos 
	public function uploadPhoto(Request $request)
	{
		$id = $logUser=Auth::user()->id;
		foreach($request->photo as $photo)
		{
			$image_name = $this->profileRepo->photo($id,$photo);
			Plugin::fire('image_watermark', $image_name);
		}

		return redirect('/profile/' . $id);
	}

	public function redirectToSlugURL($id)
	{
		$slugName = $this->profileRepo->getSlugnameByUserID($id);		
		return ($slugName) ? redirect(url("user/$slugName")) : response()->view('errors.errors_404',[], 404);
	}
	

	public function showProfile ($slug_name) {

		$auth_user = Auth::user();	
		$visiting_user = $this->profileRepo->getUserBySlugname($slug_name);

		if(!$visiting_user) {
			return response()->view('errors.errors_404',[], 404);
		}


		$id = $visiting_user->id;

		$visit_difference = $this->visitorRepo->get_difference_visit_counts($id);
		$visiting_details = $this->visitorRepo->getVisitingDetails($id);
		// $interests        = $this->profileRepo->getInterests($id);
		$field_sections   = $this->profileRepo->get_fieldsections();
		$score            = $this->profileRepo->calculate_score($id);

		$max_file_size = UtilityRepository::get_setting('max_file_size');
		

		if ($auth_user->id == $id) {
			
			return Theme::view('profile_edit', [

				'profile'          => $auth_user->profile,
				'logUser'          => $auth_user,
				'score'            => $score,
				'field_sections'   => $field_sections,
				'max_file_size'    => $max_file_size,
				'visiting_details' => $visiting_details,
				"socialAccountVerifications" => $auth_user->socialAccountVerifications(),
				"nude_photos_count" => $auth_user->photos->where('nudity', 1)->count()
			]);


		} else {

			//ohter user visit code
			//$visiting_user = $this->profileRepo->getUserById($id);
			$visiting_user_profile          = $visiting_user->profile;
			$visiting_user_profile->likedMe = $this->profileRepo->isUserLikedMe($auth_user->id, $id);
			$visiting_user_profile->like    = $this->profileRepo->isUserLiked($auth_user->id, $id);

			

			$fields_arr  = $user_sections = [];
			$user_fields = $this->profileRepo->getUserFields($id);
			$this->get_fields_and_sections($id, $user_fields, $field_sections, $fields_arr, $user_sections);

			$photo_restriction_mode = $this->profileRepo->getPhotoRestrictionMode();
			$photos_required = 0;
			$photos_allowed = $this->get_required_photos($photo_restriction_mode, $photos_required);
			
			$distance = $this->profileRepo->calculate_distance($auth_user, $visiting_user);
			$common_interests_count = $this->profileRepo->getCommonInterestsCount($auth_user->id, $id, 'ALL');
			$common_interests = $this->profileRepo->getCommonInterests($auth_user->id, $id);


			$visitedUser      = $visiting_user;
			$user             = Auth::user();

			
			$exists_notif = $this->notifRepo->getLastDayNotifs($auth_user->id, $id, 'visitor');	
			$exists = $this->notifRepo->getLastDayNotifsWithUnseenStatus($auth_user->id, $id, 'visitor');
			
			$invisible = $auth_user->isInvisible();
			if(!$invisible) {
				$this->profileRepo->createVisitEntry ($auth_user->id, $id);

				if (!$exists) {
					$this->encounterRepo->insertNotif($auth_user->id,$id,'visitor',$auth_user->id);
				}
			}
		
			if($exists_notif == null)
			{
				$email_array = new stdCLass;
                $email_array->user = $visitedUser;
                $email_array->user2 = $user;
                $email_array->type = 'visitor';
                Plugin::Fire('send_email', $email_array);
			}

			$fb_mutual_friends = $this->profileRepo->getFacebookMutualFrields($auth_user->id, $id);
			$fb_mutual_friends_count = $this->profileRepo->getTotalFacebookMutualFriendsCount($auth_user->id, $id);

			//profile visit event fire
			Plugin::fire('profile_visited', $visiting_user);

			return Theme::view('profile', [

				'user'                       => $visiting_user, 
				'profile'                    => $visiting_user_profile,  
				'common_interests_count'     => $common_interests_count,
				'visiting_details'           => $visiting_details,	
				'visit_difference'           => $visit_difference,
				"common_interests"           => $common_interests,
				'score'                      => $score,
				'distance'                   => $distance,
				'minimum_photo_count'        => $photos_required,
				'field_sections'             => $field_sections,
				'user_fields'                => $user_fields,
				'user_sections'              => $user_sections,
				"user_see_all_photos"        => $photos_allowed,
				"fb_mutual_friends"          => $fb_mutual_friends,
				"fb_mutual_friends_count"    => $fb_mutual_friends_count,
				'socialAccountVerifications' => $visiting_user->socialAccountVerifications(),
				"nude_photos_count"          => $visiting_user->photos->where('nudity', 1)->count(),
				"chat_settings_option"       => UtilityRepository::get_setting('chat_settings_option')
			]);
		}

	}


	public function get_required_photos ($mode, &$photos_required) {

		$user_photos_count = count(Auth::user()->photos);
		$minimum_photo_count = $this->profileRepo->getMinPhotoCount();

		if($mode == "true" && $minimum_photo_count > $user_photos_count) {	

			$photos_required = $minimum_photo_count - $user_photos_count;	
			return false;
		}

		$photos_required = 0;	
		return true;

	}




	public function get_fields_and_sections ($id, $user_fields, $field_sections, &$fields_arr, &$user_sections) {

		foreach($user_fields as $field)
		{
			array_push($fields_arr,$field->field_id);
		}

		foreach($field_sections as $section)
		{
			$obj = new stdCLass;
			$obj->id = $section->id;
			$obj->code = $section->code;
			$obj->fields = array();
			foreach($section->fields as $field)
			{
				if(in_array($field->id, $fields_arr))
				{
					$new_obj = new stdCLass;
					$new_obj->code = $field->code;
					$new_obj->type = $field->type;
					$new_obj->value = $field->user_field($id);
					array_push($obj->fields, $new_obj);
				}
			}
			array_push($user_sections,$obj);
		}

	}

	public function getUserPhotos (Request $request) {

		try {

			if ( !$this->userRepo->getUserById($request->userid)) {

				return response()->json(['status' => 'error']);
			}

			$flag  = $this->profileRepo->canSeePhotos(Auth::user()->id);


			if ($flag) {

				//get one photo
				$photos = $this->profileRepo->getUserPhotos ($request->userid, $flag);

			} else {

				//get all photos
				$photos = $this->profileRepo->getUserPhotos ($request->userid, $flag);

			}


			foreach ($photos as $photo) {

				$photo->photo_url = $photo->photo_url();
			}


			return response()->json([

				'status' => 'success', 
				'minimum_photo_count' => $this->profileRepo->getMinPhotoCount(), 
				'can_user_see_all' => $flag, 
				'photos' => $photos

			]);

		} catch (\Exception $e) {

			return response()->json(['status' => 'error']);
		}


	}

	//this funciton returns interests suggestions
	public function getInterestSuggestions(Request $request)
	{
		$str = $request->search;

		$suggestions = $this->profileRepo->getSuggestions($str);

		return response()->json( $suggestions );
	}

	//This function adds suggeson to userinterests only if it is in interests table
	//or else it adds to both userinterests and interests table
	public function addInterest (Request $request) {

		try {


			$logId = Auth::user()->id;

			$interest = $request->interest;


			if ($interest == null) {

				return response()->json(["status" => 'error', 'errors' => ['interest is required.']]);

			} else if(strlen($interest) < 3 || strlen($interest) > 100) {

				return response()->json(["status" => 'error', 'errors' => ['Interest must be between 3 - 100']]);	
			}


			$interest_id = null;

			//check user already have this interest added or not
			if (!$this->profileRepo->isUserInterestExist($logId,$interest)) {

				$findInterest = $this->profileRepo->findInterest($interest);

				if( $findInterest)  {

					$this->profileRepo->addToUserInterests($logId, $findInterest->id);
					$interest_id = $findInterest->id;
					
				} else {

					//adds interest to userintersts and interests both
					$interest = $this->profileRepo->addToInterests($interest);
					$this->profileRepo->addToUserInterests($logId, $interest->id);
					$interest_id = $interest->id;
					Plugin::fire('interest_added', $interest);
				}



				return response()->json([
					"status"=>'success',
					"interest_id" => $interest_id
				]);

			}


			return response()->json([
				"status" => 'error', 
				'errors' => [
					'Already exists'
				]
			]);



		} catch (\Exception $e) {

			return response()->json([
				"status" => 'error', 
				'errors' => [
					'Failed to add insterest'
				]
			]);

		}
			

		
	}


	//this function deletes 
	public function deleteInterest (Request $request) {

		try {


			$interest_id  = $request->interest_id;
			$auth_user_id = Auth::user()->id;

			$this->profileRepo->deleteInterest($auth_user_id, $interest_id);

			return response()->json(["status" => 'success']);


		} catch (\Exception $e) {

			return response()->json([
				"status" => 'error', 
				'errors' => [
					'Failed to delete insterest'
				]
			]);

		}
		
	}


	/* This method will return all common instrests of logged in user with a particular user
		@param  : userid
		@return : list of interests
		
		Route::post('user/get/common_interests', 'ProfileController@getCommonInterests');
	*/
	public function getCommonInterests (Request $request) {

		$auth_user = Auth::user();
		$user_id   = $request->id;
		$last_row_id = $request->last_row_id == '' ? 'ALL' : $request->last_row_id; 

		if (!$user_id) return response()->json(["status" => "error"]);

		$stdObject = new \stdCLass;
		$stdObject->logged_user_id = $auth_user->id;
		$stdObject->other_user_id  = $user_id;
		$stdObject->interests = $this->profileRepo->getCommonInterests($auth_user->id, $user_id, $last_row_id);
		$stdObject->count     = $this->profileRepo->getCommonInterestsCount($auth_user->id, $user_id);

		return response()->json(['status' => 'success', 'common_interests' => $stdObject]);	

	}


	//this method will update users basi information
	public function updateBasicInfo (Request $request) {

		$errors = null;

		$auth_user_id = Auth::user()->id;
		
		if ($this->profileRepo->validateBasicInfo ($request, $errors)) {
			
			//validation success
			$data['name']   = $request->name;
			$data['dob']    = $this->profileRepo->createDateFromFormat($request->dob);
			$data['gender'] = $request->gender;
			
			$this->profileRepo->switchIfDefault(Auth::user(), $request->gender);

			$this->profileRepo->saveBasicInfo ($auth_user_id, $data);

			

			$data['age'] = $this->profileRepo->getUserAge($auth_user_id);

			$this->profileRepo->setUserFieldByCode(Auth::user()->id, $data['gender']);

			return response()->json(['status' => 'success', 'data' => $data]);

			
		} else {
			//validation fails
			return response()->json(['status' => 'error', 'errors' => $errors]);
		}
		
	}




	//this method will update updatelocation
	public function updateLocation (Request $request) {

        try {

		
			$errors = null;
			
			if ($this->profileRepo->validateLocationInfo ($request, $errors)) {
				
				//validation success
				$data['latitude']  = $request->lat;
				$data['longitude'] = $request->long;
				$data['city']      = $request->city;
				$data['country']   = $request->country;

				$this->profileRepo->saveLocation (Auth::user()->id, $data);

				return response()->json(['status' => 'success']);
				
			} else {

				//validation fails
				return response()->json(['status' => 'error', 'errors' => $errors]);
			}			


     	} catch (\Exception $e) {

     		return response()->json(['status' => 'error', 'errors' => [trans('app.fail_save')]]);
     	}

    }








    public function updateAboutme (Request $request) {

    	try {

		
			if (!$request->aboutme) {

				//validation fails
				return response()->json(['status' => 'error', 'errors' => [trans_choice('app.about_me_is_required',1)]]);
			
			} 

			$data['aboutme'] = $request->aboutme;
			$this->profileRepo->savePersonalInfo (Auth::user()->id, $data);
			return response()->json(['status' => 'success']);
			
			
			
     	} catch (\Exception $e) {

     		return response()->json(['status' => 'error', 'error_message'=> $e->getMessage(), 'errors' => [trans_choice('app.fail_save',1)]]);
     	}

    }



    public function updateHereto (Request $request) {

    	try {

		
			if (!$request->hereto) {

				//validation fails
				return response()->json(['status' => 'error', 'errors' => [trans_choice('app.hereto_is_required',1)]]);
			
			} 

			$data['hereto'] = $request->hereto;
			$this->profileRepo->saveBasicInfo (Auth::user()->id, $data);
			return response()->json(['status' => 'success']);
			
			
			
     	} catch (\Exception $e) {

     		return response()->json(['status' => 'error', 'errors' => [trans_choice('app.fail_save',1)]]);
     	}

    }




    //this function takes image then crops it and resize it of
    //3 sized photo then save it.
    
    public function uploadProfilePicture (Request $request) {

    	try {

		
			$errors = null;
			
			if ($this->profileRepo->validateProfilePicture ($request, $errors)) {
				
				//validation success
				$auth_user = Auth::user();

				$image_name = $this->profileRepo->saveProfilePicture (

					$auth_user->id, $request->profile_picture, 
					$request->crop_width, $request->crop_height, 
					$request->crop_x, $request->crop_y
				);

				
				$auth_user->profile_pic_url = $image_name;
				$auth_user->save();

				Plugin::fire('image_watermark', $image_name);
				$image_paths = [
					
					asset('/uploads/others/thumbnails/'.$image_name),
					asset('/uploads/others/'.$image_name),
					asset('/uploads/others/original/'.$image_name),
					asset('/uploads/others/encounters/'.$image_name),
					
				];

				return response()->json(['status' => 'success', 'images' => $image_paths]);
				
			} else {

				//validation fails
				return response()->json(['status' => 'error', 'errors' => $errors]);
			}			


     	} catch (\Exception $e) {

     		return response()->json(['status' => 'error', 'errors' => [trans_choice('app.fail_save',1)] ]);
     	}
    }






    //this method delete photo by photoname 
    //from photo table if exist on user profile pic
    //then set male or female

    public function deletePhoto (Request $request) {

    	try {

    		$this->profileRepo->deletePhoto(Auth::user()->id, $request->photo_name);
    		return response()->json(['status' => 'success']);


    	} catch (\Exception $e) {

    		return response()->json(['status' => 'error', 'errors' => [trans_choice('app.unable_to_delete',1)] ]);
    	}
    }





    //this method will return online status of a particular userid
    public function getOnlineStatus (Request $request) {

    	$status = $this->profileRepo->getUserOnlineStatus($request->user_id);
     	
     	if($status)   
     		return response()->json(['user_id' => $request->user_id, 'online' => '1']);
     	else
        	return response()->json(['user_id' => $request->user_id, 'online' => '0']);
    }




    //this method will make any other pictures as profile picture
    //no crop option is there

    public function changeProfilePicture (Request $request) 
    {
    	$photo_name = $request->photo_name;
		

		if (is_null($photo_name)) {
			return response()->json(['status' => 'error', 'errors' => [trans_choice('app.photo_name_is_required',1)]]);
		}

		$auth_user  = Auth::user();
		$photo_user_id = $this->profileRepo->getUserIdByPhotoName ($photo_name);

		if($photo_user_id != $auth_user->id) {
			return response()->json(['status' => 'error', 'errors' => [trans_choice('app.unable_to_set_profile_pic',1)]]);
		}


		$this->profileRepo->save_crop_resize_photo(
			public_path('/uploads/others/original/'.$photo_name),
			 $photo_name, 
			 $request->crop_width, 
			 $request->crop_height, 
			 $request->crop_x, 
			 $request->crop_y
		);

		$this->profileRepo->changeProfilePicture ($auth_user->id, $photo_name);

		$image_paths = [
			asset('/uploads/others/thumbnails/'.$photo_name),
			asset('/uploads/others/'.$photo_name),
			asset('/uploads/others/original/'.$photo_name),
			asset('/uploads/others/encounters/'.$photo_name),
			
		];

		return response()->json(['status' => 'success', 'photo' => $image_paths]);
    	
    }





    /* this method returns interets of a user */
    public function getInterests(Request $req) {
    	
    	$user_id = $req->user_id;
    	$last_user_interest_id = $req->last_user_interest_id ? $req->last_user_interest_id : 0;

    	$user_interests = $this->profileRepo->getInterestsByUserId($user_id, $last_user_interest_id);

    	return response()->json([
    		"status" => "success",
    		"data" => $user_interests,
    		"count" => count($user_interests),
    		"total_user_interests_count" => $this->profileRepo->getTotalUserInterestsCount($user_id)
    	]);

    }




}
