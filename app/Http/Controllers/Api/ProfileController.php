<?php

namespace App\Http\Controllers\Api;

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




class ProfileController extends Controller {

	protected $profileRepo;
	protected $userRepo;
	protected $creditRepo;
	protected $visitorRepo;
	protected $encounterRepo;
	protected $superpowerRepo;
	protected $notifRepo;

	public function __construct () {
		
		$this->profileRepo    = app("App\Repositories\ProfileRepository");
		$this->userRepo       = app("App\Repositories\UserRepository");
		$this->creditRepo     = app("App\Repositories\CreditRepository");
		$this->visitorRepo    = new VisitorRepository;
		$this->encounterRepo  = new EncounterRepository;
		$this->superpowerRepo = app("App\Repositories\SuperpowerRepository");
		$this->notifRepo      = new NotificationsRepository;
	}



	

	public function myProfile (Request $req) {

		$auth_user = $req->auth_user;		

		$visit_difference = $this->visitorRepo->get_difference_visit_counts($auth_user->id);
		$visiting_details = $this->visitorRepo->getVisitingDetails($auth_user->id);
		
		$score            = $this->profileRepo->calculate_score($auth_user->id);
		$profile_complete_percentage = $this->profileRepo->profileCompletePercent($req->real_auth_user);

		$interests = [];
		foreach ($this->profileRepo->getInterests($auth_user->id) as $user_interest) {
			$temp = [];
			$temp["id"] = $user_interest->id;
			$temp["interest_id"] = $user_interest->interestid;
			$temp["interest_text"] = $user_interest->interests->interest;
			array_push($interests, $temp);
		}

		$about_me = $auth_user->profile->aboutme;
		
		$popularity = (!is_null($auth_user->profile->popularity)) ?: 0;
		$popularity_type = $this->getPopularityType($popularity);
		unset($auth_user->profile);

		$field_sections = $this->getFieldSections($auth_user);

	
		$photos = $this->getPhotos($auth_user);
		unset($auth_user->photos);

		return response()->json([
			"status" => "success",
			"success_data" => [
				"photos" => $photos,
				"field_sections" => $field_sections,

				"user_popularity" => [
					"popularity" => $popularity,
					"popularity_type" => $popularity_type
				],
				"profile_complete_percentage" => $profile_complete_percentage,
				"user_score" => $score,
				"user" => $auth_user,
				"about_me" => $about_me,
				"user_interests" => $interests,

				"profile_visitor_count" => [
					"today"      => $visiting_details->day,
					"this_week"  => $visiting_details->week,
					"this_month" => $visiting_details->month,
				],

				"profile_visitor_difference" => [
					"today_increased" => ($visit_difference->day == 0) ? "false" : "true",
					"this_week_increased"       => ($visit_difference->week == 0) ? "false" : "true",
					"this_month_increased"      => ($visit_difference->month == 0) ? "false" : "true"
				],
				"max_file_upload_size" => [
					"value" => UtilityRepository::get_setting('max_file_size'),
					"unit" => "MB"
				],
				"success_text" => "Profile Data retrived successfully."
			]
		]);


	}


	protected function getPopularityType ($popularity){
		
		switch (true) {
			case ($popularity < 10):
				$popularity_type = "very_very_low";
				break;

			case ($popularity >= 10 && $popularity < 25):
				$popularity_type = "very_low";
				break;

			case ($popularity >= 25 && $popularity < 50):
				$popularity_type = "low";
				break;

			case ($popularity >= 50 && $popularity < 75):
				$popularity_type = "medium";
				break;

			case ($popularity >= 75 && $popularity <= 100):
				$popularity_type = "high";
				break;
			
			default:
				$popularity_type = "";
		}

		return $popularity_type;
	}

	public function getFieldSections ($user) {

		$field_sections = [];

		foreach ($this->profileRepo->get_fieldsections() as $section) {

			$section_temp = [
				"section_id" => $section->id,
				"text" => trans('custom_profile.'.$section->code),
				"fields" => []
			];

			foreach($section->fields as $field) {
				if($field->type == "dropdown" && $field->code != 'gender') {

					$field_temp = [
						"field_id" => $field->id,
						"field_type" => "dropdown",
						"text" => trans('custom_profile.'.$field->code),
						"options" => []
					];


					foreach($field->field_options as $option) {
						
						$option_temp = [
							"option_id"   => $option->id,
							"text"        => trans('custom_profile.'.$option->code),
							"is_selected" => 'false'
						];

						if ($field->user_field($user->id) 
							&& $field->user_field($user->id) == $option->code) {
							$option_temp['is_selected'] = 'true';
						}
						array_push($field_temp['options'], $option_temp);
					}
				} else if ($field->type == 'text') {

					$field_temp = [
						"field_id"   => $field->id,
						"field_type" => "text",
						"text"       => trans('custom_profile.'.$field->code),
						"value"      => ($field->user_field($user->id)) ? :'',
					];

				} else if ($field->type == 'textarea') {
					
					$field_temp = [
						"field_id"   => $field->id,
						"field_type" => "textarea",
						"text"       => trans('custom_profile.'.$field->code),
						"value"      => ($field->user_field($user->id)) ? :'',
					];
				}
				array_push($section_temp['fields'], $field_temp);
			}
			
			array_push($field_sections, $section_temp);
		}

		return $field_sections;
	}


	protected function getPhotos ($user) {

		$photos = [];
		foreach ($user->photos as $photo) {
			$photo_name = substr($photo->photo_url, 0);
			$photo_url = [
				"thumbnail" => url('uploads/others/thumbnails/'.$photo_name),
                "encounter" => url('uploads/others/encounters/'.$photo_name),
                "other"     => url('uploads/others/'.$photo_name),
                "original"  => url('uploads/others/original/'.$photo_name),
			];

			$photo->photo_name = $photo_name;
			$photo->photo_url = $photo_url;
			array_push($photos, $photo);
		}

		return $photos;
	}



	public function profile (Request $req) {
		
			$auth_user = $req->auth_user;		
			$id = $req->view_user_id;
			$user = $this->profileRepo->getUserById($id);

			if (is_null($user)) {
				return response()->json([
					"status" => "error",
					"error_data" => [
						"error_text" => "User not found."
					]
				]);
			}

			$visit_difference = $this->visitorRepo->get_difference_visit_counts($id);
			$visiting_details = $this->visitorRepo->getVisitingDetails($id);
			$interests        = $this->profileRepo->getInterests($id);
			$field_sections   = $this->profileRepo->get_fieldsections();
			$score            = $this->profileRepo->calculate_score($id);
			$profile_complete_percentage = $this->profileRepo->profileCompletePercent($user);
			
			$user->gender_text          = trans('custom_profile.'.$user->gender);
            $user->age                  = $user->age();
            $user->superpower_activated = ($user->isSuperPowerActivated()) ? 'true' : 'false';
            $user->superpower_days_left = $user->superpowerdaysleft();
            $user->invisible            = ($user->isInvisible()) ? 'true' : 'false';
            $user->online_status        = ($user->onlineStatus()) ? 'true' : 'false';
            $user->social_links         = $user->get_social_links();
            $user->social_verified      = ($user->is_social_verified()) ? 'true' : 'false';
            unset($user->social_login_links);

			$profile_picture = substr($user->profile_pic_url, 0);
            $user->profile_picture = $profile_picture;
            $profile_pic_url = [
                "thumbnail" => $user->thumbnail_pic_url(),
                "encounter" => $user->encounter_pic_url(),
                "other"     => $user->others_pic_url(),
                "original"  => $user->profile_pic_url(),
            ];
            $user->profile_pic_url = $profile_pic_url;
			$about_me = $user->profile->aboutme;
			unset($user->password);

			$popularity = (!is_null($user->profile->popularity)) ?: 0;
			$popularity_type = $this->getPopularityType($popularity);

			$photos = $this->getPhotos($user);

			$likedMe = $this->profileRepo->isUserLikedMe($auth_user->id, $user->id);
			$iLiked  = $this->profileRepo->isUserLiked($auth_user->id, $user->id);

			unset($user->profile);
			unset($user->photos);
				
			$interests = [];
			foreach ($this->profileRepo->getInterests($user->id) as $user_interest) {
				$temp = [];
				$temp["id"] = $user_interest->id;
				$temp["interest_id"] = $user_interest->interestid;
				$temp["interest_text"] = $user_interest->interests->interest;
				array_push($interests, $temp);
			}


			$field_sections = $this->getFieldSections($user);


			$photo_restriction_mode = $this->profileRepo->getPhotoRestrictionMode();
			$photos_required = 0;
			$photos_allowed  = $this->get_required_photos($photo_restriction_mode, $photos_required, $auth_user);

			$distance = $this->profileRepo->calculate_distance($auth_user, $user);
			$common_interests = $this->profileRepo->getCommonInterests($auth_user->id, $id, 'ALL');

			

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
                $email_array->user = $user;
                $email_array->user2 = $auth_user;
                $email_array->type = 'visitor';
                Plugin::Fire('send_email', $email_array);
			}

			//profile visit event fire
			Plugin::fire('profile_visited', $user);

			$blocked = $req->real_auth_user->blocked_users();
			$blocked = $blocked->where('user2', $user->id)->first();
			$blocked = ($blocked) ? 'true' : 'false';

			$blocked_me = $req->real_auth_user->users_blocked_me();
			$blocked_me = $blocked_me->where('user1', $user->id)->first();
			$blocked_me = ($blocked_me) ? 'true' : 'false';

		return response()->json([
			"status" => "success",
			"success_data" => [
				"commom_interests" => $common_interests,
				"photos" => $photos,
				"field_sections" => $field_sections,

				"user_popularity" => [
					"popularity" => $popularity,
					"popularity_type" => $popularity_type
				],
				"liked_me" => $likedMe, 
				"i_liked"  => $iLiked, 
				"profile_complete_percentage" => $profile_complete_percentage,
				"user_score" => $score,
				"user" => $user, 
				"blocked" => $blocked,
				"blocked_me" => $blocked_me,
				"about_me" => $about_me,
				"distance" => [
					"value" => $distance,
					"unit" => "Km"
				],
				"user_interests" => $interests,

				"profile_visitor_count" => [
					"today"      => $visiting_details->day,
					"this_week"  => $visiting_details->week,
					"this_month" => $visiting_details->month,
				],

				"profile_visitor_difference" => [
					"today_increased"      => ($visit_difference->day == 0) ? "false" : "true",
					"this_week_increased"  => ($visit_difference->week == 0) ? "false" : "true",
					"this_month_increased" => ($visit_difference->month == 0) ? "false" : "true"
				],
				"max_file_upload_size" => [
					"value" => UtilityRepository::get_setting('max_file_size'),
					"unit" => "MB"
				],
				"minimum_photos_required" => $photos_required,
				"user_can_see_all_photos" => $photos_allowed,
				"success_text" => "Profile Data retrived successfully."
			]
		]);

	}



	public function get_required_photos ($mode, &$photos_required, $auth_user) {

		$user_photos_count = count($auth_user->photos);
		$minimum_photo_count = $this->profileRepo->getMinPhotoCount();

		if($mode == "true" && $minimum_photo_count > $user_photos_count) {	

			$photos_required = $minimum_photo_count - $user_photos_count;	
			return false;
		}

		$photos_required = 0;	
		return true;

	}




	//this function takes image then crops it and resize it of
    //3 sized photo then save it.
    
    public function uploadProfilePicture (Request $req) {
		
    	$errors = [];
    	if ($this->profileRepo->validateProfilePicture ($req, $errors)) {

    		$auth_user = $req->real_auth_user;

    		try {

    			$image_name = $this->profileRepo->saveProfilePicture (
					$auth_user->id, $req->profile_picture, 
					$req->crop_width, $req->crop_height, 
					$req->crop_x, $req->crop_y
				);

					
				$auth_user->profile_pic_url = $image_name;
				$auth_user->save();

				Plugin::fire('image_watermark', $image_name);

				$photo_obj = $this->profileRepo->getPhotoByName($image_name);

				$image_paths = [
					"thumbnail"  => asset('/uploads/others/thumbnails/'.$image_name),
					"other"      => asset('/uploads/others/'.$image_name),
					"original"   => asset('/uploads/others/original/'.$image_name),
					"encounter"  => asset('/uploads/others/encounters/'.$image_name),
					"photo_id"   => $photo_obj->id,
					"photo_name" => $image_name
				];
    		
    		} catch (\Exception $e) {
    			$errors['error_text'] = "Unknown error occoured";
    			return response()->json([
		    		'status' => 'error',
		    		 'error_data' => $errors
		    	]);
    		}
    		
    		return response()->json([
	    		'status' => 'success',
	    		 'success_data' => [
	    		 	"photo_url" => $image_paths,
	    		 	"success_text" => "Profile picture uploaded successfully."
	    		 ]
	    	]);


    	} else {
    		
    		$errors['error_text'] = "Validation Error";
    		return response()->json([
	    		'status' => 'error',
	    		 'error_data' => $errors
	    	]);
    	}
    }


    public function uploadPhotos (Request $req) {

		$id = $req->real_auth_user->id;

		$errors = [];
		if ($this->validateOtherPhotos($req->photos, $errors)) {
			
			return response()->json([
				"status" => "error",
				"error_data" => $errors
			]);
			
		}
			
		$image_urls = [];

		foreach ($req->photos as $photo) {
			
			try {

				$photo_obj = null;
				$image_name = $this->profileRepo->photo($id, $photo, $photo_obj);
				Plugin::fire('image_watermark', $image_name);

				$image_paths = [
					"thumbnail" => asset('/uploads/others/thumbnails/'.$image_name),
					"other"     => asset('/uploads/others/'.$image_name),
					"original"  => asset('/uploads/others/original/'.$image_name),
					"encounter" => asset('/uploads/others/encounters/'.$image_name),
					"photo_id"  => $photo_obj->id,
					"photo_name" => $image_name
				];

				array_push($image_urls, $image_paths);

			} catch (\Exception $e) {
				$errors['error_text'] = "Unknown error occoured";
    			return response()->json([
		    		'status' => 'error',
		    		 'error_data' => $errors
		    	]);
			}
		}

		return response()->json([
    		'status' => 'success',
    		 'success_data' => [
    		 	"photo_urls" => $image_urls,
    		 	"success_text" => "Profile picture uploaded successfully."
    		 ]
    	]);
		
	}


	protected function validateOtherPhotos ($photos, &$errors) {
		
		$error = false;
		
		if (is_null($photos)) {
			$error = true;
			$errors['error_text'] = 'The photos[] field is required.';

		} else if (!is_array($photos)) {
			$error = true;
			$errors['error_text'] = 'Photos must be send as array.';	
		
		} 

		return $error;
	}



	public function deletePhoto (Request $req) {

		$auth_user = $req->real_auth_user;
		$photo_name = $req->photo_name;

		if (is_null($photo_name)) {
			return response()->json([
				"status" => "error",
				"error_data" => [
					"error_text" => "The photo_name field is required."
				]
			]);
		}

		if ($this->profileRepo->deletePhoto($auth_user->id, $photo_name)) {
			return response()->json([
	    		'status' => 'success',
	    		 'success_data' => [
	    		 	"success_text" => "Photo deleted successfully."
	    		 ]
	    	]);
		}

		return response()->json([
			"status" => "error",
			"error_data" => [
				"error_text" => "Unable to delete photo."
			]
		]);	

	}






	//this method will update users basi information
	public function updateBasicInfo (Request $req) {

		$errors = [];
		$auth_user = $req->real_auth_user;
		
		if ($this->profileRepo->validateBasicInfo ($req, $errors)) {
			
			//validation success
			$data['name']   = $req->name;
			$data['dob']    = $this->profileRepo->createDateFromFormat($req->dob);
			$data['gender'] = $req->gender;
			
			$updt_user = $this->profileRepo->switchIfDefault($auth_user, $req->gender);

			$this->profileRepo->saveBasicInfo ($auth_user->id, $data);

			

			$data['age'] = $this->profileRepo->getUserAge($auth_user->id);

			$this->profileRepo->setUserFieldByCode($auth_user->id, $data['gender']);

			return response()->json([
	    		'status' => 'success',
	    		 'success_data' => [
	    		 	"name" => $data['name'],
	    		 	"age" => $data['age'],
	    		 	"gender" => $data['gender'],
	    		 	"profile_picture_url" => [
	    		 		"thumbnail" => asset('/uploads/others/thumbnails/'.$updt_user->profile_pic_url),
						"other"     => asset('/uploads/others/'.$updt_user->profile_pic_url),
						"original"  => asset('/uploads/others/original/'.$updt_user->profile_pic_url),
						"encounter" => asset('/uploads/others/encounters/'.$updt_user->profile_pic_url),
						"photo_name" => $updt_user->profile_pic_url
	    		 	],
	    		 	"success_text" => "Basic information updated."
	    		 ]
	    	]);

			
		} else {
			$errors['error_text'] = "Validation Error.";
			return response()->json([
				'status' => 'error', 
				'error_data' => $errors
			]);
		}
		
	}




	public function updateLocation (Request $req) {

       	$auth_user = $req->real_auth_user;
       	$errors = [];
		
		$success = $this->profileRepo->validateLocationInfo ($req, $errors);
		if ($success) {
			
			$data['latitude']  = $req->lat;
			$data['longitude'] = $req->long;
			$data['city']      = $req->city;
			$data['country']   = $req->country;

			$this->profileRepo->saveLocation ($auth_user->id, $data);

			return response()->json([
	    		'status' => 'success',
	    		 'success_data' => [
	    		 	"success_text" => "Location updated successfully."
	    		 ]
	    	]);
			
		} else {

			$errors['error_text'] = "Validation Error.";
			return response()->json([
				'status' => 'error', 
				'error_data' => $errors
			]);
		}			



    }



    public function addInterest (Request $req) {

		$auth_user = $req->real_auth_user;
		$interest_text  = $req->interest;

		if (!$interest_text) {
			return response()->json([
				"status" => 'error', 
				'error_data' => [
					"error_text" => 'Interest is required.',
				]
			]);

		} else if(strlen($interest_text) < 2 || strlen($interest_text) > 150) {
			return response()->json([
				"status" => 'error', 
				'error_data' => [
					"error_text" => 'Interest must be between 2 - 150',
				]
			]);	
		}

		

		//check user already have this interest added or not
		$exists = $this->profileRepo->isUserInterestExist($auth_user->id, $interest_text);
		if ($exists) {
			return response()->json([
				"status" => 'error', 
				'error_data' => [
					"error_text" => 'Interest Already exists',
				]
			]);	
		}


		$user_interest = null;
		
		$interest = $this->profileRepo->findInterest($interest_text);

		if (!is_null($interest))  {
			$user_interest = $this->profileRepo->addToUserInterests($auth_user->id, $interest->id);
			
		} else {
			//adds interest to userintersts and interests both
			$interest = $this->profileRepo->addToInterests($interest_text);
			$user_interest = $this->profileRepo->addToUserInterests($auth_user->id, $interest->id);
			Plugin::fire('interest_added', $interest);
		}

		return response()->json([
    		'status' => 'success',
    		 'success_data' => [
    		 	"interest_id" => $interest->id,
    		 	"user_interest" => $user_interest->id,
    		 	"interest_text" => $interest->interest,
    		 	"success_text" => "Interest added successfully."
    		 ]
    	]);
		
	}



	public function getInterestSuggestions (Request $req) {

		$str = $req->search_text;
		
		if (!$str) {
			return response()->json([
				"status" => 'error', 
				'error_data' => [
					"error_text" => 'Interest search text is required.',
				]
			]);
		}

		$suggestions = $this->profileRepo->getSuggestions($str);

		return response()->json([
    		'status' => 'success',
    		 'success_data' => [
    		 	"suggestions" => $suggestions,
    		 	"success_text" => "Suggestions retrived succesfully."
    		 ]
    	]);
	}



	public function deleteInterest (Request $req) {

		$interest_id  = $req->user_interest_id;
		$auth_user_id = $req->real_auth_user->id;

		if (!$interest_id) {
			return response()->json([
				"status" => 'error', 
				'error_data' => [
					"error_text" => 'User interest id is required.',
				]
			]);
		}

		$this->profileRepo->deleteInterest($auth_user_id, $interest_id);

		return response()->json([
    		'status' => 'success',
    		 'success_data' => [
    		 	"success_text" => "User interest deleted successfully."
    		 ]
    	]);

	}




	public function updateAboutme (Request $req) {

		$auth_user = $req->real_auth_user;
		$about_me = $req->about_me;

		if (!$about_me) {
			return response()->json([
				"status" => 'error', 
				'error_data' => [
					"error_text" => 'The about_me is field required.',
				]
			]);
		}

		$len = strlen($about_me);

		if ($len > 1000) {
			return response()->json([
				"status" => 'error', 
				'error_data' => [
					"error_text" => 'about_me must be within 1000 chars.',
				]
			]);
		}


		$auth_user->profile->aboutme = $about_me;
		$auth_user->profile->save();

		return response()->json([
    		'status' => 'success',
    		 'success_data' => [
    		 	"success_text" => "Your about me is saved successfully."
    		 ]
    	]);

    }





    public function updateCustomFields (Request $req) {

    	$auth_user = $req->real_auth_user;
    	$req_data_array = $req->all();

    	unset($req_data_array['user_id']);
    	unset($req_data_array['access_token']);

 		if (count($req_data_array) < 1) {
 			return response()->json([
				"status" => 'error', 
				'error_data' => [
					"error_text" => 'Choose custom fields options',
				]
			]);
 		}

		$this->profileRepo->saveUserFields($auth_user->id, $req_data_array);
		return response()->json([
    		'status' => 'success',
    		 'success_data' => [
    		 	"success_text" => "Your details are saved successfully."
    		 ]
    	]);
    }



    public function changeProfilePhoto(Request $req)
    {
    	$authUser = $req->real_auth_user;
    	$photoName = $req->photo_name;

    	$photoUserID = $this->profileRepo->getUserIdByPhotoName($photoName);


    	if(!$photoUserID || ($photoUserID != $authUser->id)) {

    		return response()->json([
				"status" => 'error', 
				"error_type" => "PHOTO_NOT_FOUND",
				'error_data' => [
					"error_text" => 'Not photo found with this photo.',
				]
			]);

    	}




    	if($authUser->profile_pic_url !== $photoName) {

    		$authUser->profile_pic_url = $photoName;
    		$authUser->save();

    		return response()->json([
	    		'status' => 'success',
	    		 'success_data' => [
	    		 	"success_text" => "Profile picture changed successfully."
	    		 ]
	    	]);

    	} 

    	return response()->json([
			"status" => 'error', 
			"error_type" => "PHOTO_ALREADY_PROFILE_PICTURE_SET",
			'error_data' => [
				"error_text" => 'Photo already set as profie picture.',
			]
		]);


    }


}
