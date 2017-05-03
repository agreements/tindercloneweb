<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Profile;
use App\Models\Match;
use App\Models\Encounter;
use App\Models\Visitor;
use App\Models\Photo;
use App\Models\UserInterests;
use App\Models\Interests;
use App\Models\Notifications;
use App\Models\UserAbuseReport;
use App\Repositories\BlockUserRepository;
use App\Repositories\Admin\UtilityRepository;
use App\Repositories\Admin\GeneralManageRepository;
use App\Components\Plugin;
use App\Models\PhotoAbuseReport;
use App\Models\Settings;
use App\Models\FacebookFriends;
use App\Models\FieldSections;
use App\Models\FieldOptions;
use App\Models\Fields;
use App\Models\UserFields;
use App\Models\UserPreferences;
use DB;
use Intervention\Image\ImageManagerStatic as Image;
use Storage;
use Validator;
use stdClass;
use Auth;

class ProfileRepository
{

	private $blockUserRepo;
	private $generalRepo;
	public function __construct(FieldOptions $field_options, UserFields $user_fields, Fields $fields, UserPreferences $user_preferences, Photo $photo, FieldSections $field_sections, FacebookFriends $facebook_friends, User $user, Encounter $encounter, Visitor $visitor, Interests $interests, UserInterests $user_interests)
	{
		$this->blockUserRepo = app("App\Repositories\BlockUserRepository");
		$this->generalRepo = new GeneralManageRepository;
		
		$this->field_options = $field_options;
		$this->user_fields = $user_fields;
		$this->fields = $fields;
		$this->user_preferences = $user_preferences;
		$this->photo = $photo;
		$this->field_sections =  $field_sections;
		$this->facebook_friends = $facebook_friends;
		$this->user = $user;
		$this->encounter = $encounter;
		$this->visitor = $visitor;
		$this->interests = $interests;
		$this->user_interests = $user_interests;
		$this->settings = app('App\Models\Settings');
	}


	public function getSlugnameByUserID($userID)
	{
		$user = $this->user->select('slug_name')->find($userID);
		return isset($user->slug_name) ? $user->slug_name : "";
	}


	public function getUserBySlugname($slug_name)
	{
		return $this->user->where('slug_name', $slug_name)->first();
	}

	public function setUserFieldByCode($id, $code)
	{
		$option = $this->field_options->where('code',$code)->first();	
		$user_field = $this->user_fields->where('user_id',$id)->where('field_id',$option->field_id)->first();
		if($user_field) {
			$user_field->value = $option->id;
		} else {
			$user_field = new $this->user_fields;
			$user_field->user_id = $id;
			$user_field->field_id = $option->field_id;
			$user_field->value = $option->id;
		}
		$user_field->save();

		return $user_field;
	}

	public function getFieldValue($id)
	{
		$option = $this->field_options->where('id',$id)->first();
		return $option->code;
	}

	public function saveGender($gender)
	{

		$field = $this->fields->where('code','gender')->first();
		$user_field = $this->user_fields->where('user_id', $gender['user_id'])
										->where('field_id', $field->id)
										->first();

		if (is_null($user_field)) {
			$user_field = clone $this->user_fields;
			$user_field->user_id = $gender['user_id'];
			$user_field->field_id = $field->id;
			
			foreach($field->field_options as $option) {
				if($option->code == $gender['value']) {
					$user_field->value = $option->id;			
					$user_field->save();
					return true;		
				}
			}
		}

		return false;

	}

	//profilefields
	public function getLatLong($place)
    {
    	$place = str_replace(' ', '+',$place);
        $geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$place.'&sensor=false');

        $output= json_decode($geocode);

        $arr['lat'] = $output->results[0]->geometry->location->lat;
        $arr['long'] = $output->results[0]->geometry->location->lng;
        return $arr;
    }

    public function savePreferenceFields ($logId,$arr)
	{
		foreach($arr as $key => $value)
		{
			
			if($value == -1 || $value == '' || !isset($value) || trim($value)==='')
				continue;
			
			$user_preference = $this->user_preferences->where('user_id','=',$logId)->where('field_id','=',$key)->first();
			
			if($user_preference)
			{
				$user_preference->field_id = $key;
				$user_preference->prefer_value = $value;
			}
			else
			{
				$user_preference = clone $this->user_preferences;
				$user_preference->user_id = $logId;
				$user_preference->field_id = $key;
				$user_preference->prefer_value = $value;
			}
			$user_preference->save();
		}
	}

	public function saveUserFields ($userId, $customData)
	{	
		if (isset($arr['_token'])) unset($arr['_token']);
		

		foreach($customData as $key => $value) {

			if(is_array($value)) {

				$this->user_fields->where('user_id', $userId)
								->where('field_id', $key)
								->forceDelete();												
											

				foreach($value as $option) {
					
					$userField = new $this->user_fields;
					$userField->user_id = $userId;
					$userField->field_id = $key;
					$userField->value = $option;
						
					$userField->save();

				}



			} else {

				$user_field = $this->user_fields->where('user_id', $userId)->where('field_id', $key)->first();

				if($value == -1 || $value == '' || !isset($value) || trim($value)==='') {
					if($user_field) {
						$user_field->forceDelete();
					}
					continue;
				}

				if($user_field) {
					$user_field->field_id = $key;
					$user_field->value = $value;
				
				} else {

					$user_field = new UserFields;
					$user_field->user_id = $userId;
					$user_field->field_id = $key;
					$user_field->value = $value;
				}
				
				$user_field->save();


			}



		}

		
	}

	public function getUserFields($id)
	{
		return $this->user_fields->where('user_id','=',$id)->get();
	}

    public function get_fieldsections(){
    	$sections = $this->field_sections->all();
        return $sections;
    }

	public function getUserIdByPhotoName ($photo_name) {
		$photo = $this->photo->select('userid')->where('photo_url', '=', $photo_name)->first();
		if ($photo) {
			return $photo->userid;
		} else {
			return 0;
		}
	}

	public function calculate_distance ($from_user, $to_user) {

		$lat1  = deg2rad($from_user->latitude);
		$lat2  = deg2rad($to_user->latitude);

		$theta = $from_user->longitude - $to_user->longitude;
		
		$dist  = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist  = acos($dist);
		$dist  = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;
		
		return $miles * 1.609344;
	}

	
	
	public function get_fb_friends($id)
	{

		$arr = array();
		$facebook_friends = array();
		$users = $this->facebook_friends->where('user1','=',Auth::user()->id)->get();
		if(Auth::user()->id == $id)
		{
			foreach($users as $user)
			{
				array_push($facebook_friends, $this->user->find($user->user2));	
			}
		}
		else
		{
			foreach($users as $user)
			{
				array_push($arr, $user->user2);
			}
			// dd($arr);
			$fb_friends = $this->facebook_friends->where('user1','=',$id)->whereIn('user2',$arr)->get();
			foreach($fb_friends as $friend)
			{
				array_push($facebook_friends, $this->user->find($friend->user2));		
			}
		}
		return $facebook_friends;
	}

	public function calculate_score($id)
	{

		/*
		$users_encountered_auth_user_count = $this->encounter->where('user2', $id)->count();
		$users_liked_auth_user_count       = $this->encounter->where('user2', $id)->where('likes', 1)->count();

		$score = ($users_encountered_auth_user_count == 0) ? 0 : (($users_liked_auth_user_count / $users_encountered_auth_user_count) * 10); 
		*/

		$counts = $this->encounter->select([
			\DB::raw('count(*) as users_encountered_auth_user'),
			\DB::raw('count(IF(likes=1,1,null)) as users_liked_auth_user') 
		])->where('user2', $id)->first()->toArray();

		$score = ($counts['users_encountered_auth_user'] == 0) 
					? 0 
					: (($counts['users_liked_auth_user'] / $counts['users_encountered_auth_user']) * 10); 

		$obj           = new stdClass;
		$obj->score    = $score;
		$obj->likes    = $counts['users_liked_auth_user'];
		$obj->dislikes = $counts['users_encountered_auth_user'] - $counts['users_liked_auth_user'];

		return $obj;
	}

	public function calculate_popularity($id,$max_count)
	{
		$count = $this->visitor->where('user2','=',$id)->count();
		if($max_count == 0)
			$popularity = 0;
		else
			$popularity = ($count/$max_count)*100;
		return $popularity;
	}

	public function getUserById ($user_id) {

		return $this->user->find($user_id);
	}



	public function isUserLiked ($user_id1, $user_id2) {

		$liked = $this->encounter->select('likes')->where ('user1', $user_id1)->where ('user2', $user_id2)->first();
		return ($liked) ? $liked->likes : -1;
	}


	public function isUserLikedMe ($user_id1, $user_id2) {

		$liked = $this->encounter->select('likes')->where ('user2', $user_id1)->where ('user1', $user_id2)->first();
		return ($liked) ? $liked->likes : -1;
	}




	//this function creates new entry in visitor or update if row existed
	public function createVisitEntry ($loguser, $id) {

		$visited = $this->visitor->select(['id' ,'status'])->where('user1', $loguser)->where ('user2', $id)->first();

		if ($visited) {

			$visited->status = 'unseen';
			$visited->save();
			$visited->touch();

		} else 	{

			$visit = clone $this->visitor;
			$visit->user1 = $loguser;
			$visit->user2 = $id;
			$visit->status = 'unseen';
			$visit->save();
		}	

	}






	// public function updateName($id, $name)
	// {
	// 	$user = $this->user->find($id);

	// 	$user->name = $name;

	// 	$user->save();

	// }

	

	// public function updateCity($id, $city)
	// {
	// 	$user = $this->user->find($id); 

	// 	$user->city = $city;

	// 	$user->save();

	// }


	public function updateHereTo($id, $hereto)
	{
		$user = $this->user->find($id);

		$user->hereto = $hereto;

		$user->save();
	}


	public function accountInfo($id,$file,$arr)
	{
		$user = $this->user->find($id);
		
		//$file = Input::file('profile_pic');

		if ($file != null)
		{
			$ext = '';
			
			if($file->getMimeType() == 'image/png')
				$ext = '.png';
			else if($file->getMimeType() == 'image/jpg' || $file->getMimeType() == 'image/jpeg')
				$ext = '.jpg';
			
			
			$fileName = uniqid($id . '_') . '_' . rand(10000000, 99999999) . $ext;
			$path = public_path() . '/uploads/others';

			$file->move($path, $fileName);

			$album = clone $this->photo;
			$album->userid = $id;
			$album->photo_url = $fileName;
			$album->save();

			$user->profile_pic_url = $fileName;

		}
				

			$user->status = $arr['status'];
			$user->name = $arr['name'];
			$user->gender = $arr['gender'];
			$user->city = $arr['city'];
			$user->dob = $arr['dob'];
			$user->hereto = $arr['hereto'];

			$user->save();
	
	}
	
	public function photo ($id, $file, &$photo_obj = null) {

		if (UtilityRepository::validImage($file, $ext)) {

			$fileName = UtilityRepository::generate_image_filename("{$id}_", $ext);	
			
			$this->save_resize_photo($file, $fileName);			

			$album = clone $this->photo;

			$album->userid = $id; 
			$album->photo_url = $fileName;
			

			$album->save();

			$photo_obj = clone $album;

			return $fileName;


		}
	}

	public function compress($source, $destination, $quality) {

	    $info = getimagesize($source);

	    if ($info['mime'] == 'image/jpeg') 
	        $image = imagecreatefromjpeg($source);

	    elseif ($info['mime'] == 'image/gif') 
	        $image = imagecreatefromgif($source);

	    elseif ($info['mime'] == 'image/png') 
	        $image = imagecreatefrompng($source);
	    elseif ($info['mime'] == 'image/bmp') 
	        $image = imagecreatefrombmp($source);

	    imagejpeg($image, $destination, $quality);

	    return $destination;
	}



	public function save_resize_photo ($file, $fileName) {
		
		$size  = getimagesize($file);
		$file  = file_get_contents($file);
		$image = Image::make($file);

		$width          = ($size[0] > 700) ? 700 : $size[0];
		$original_image = $this->resize_photo($image, $width, null);
		$thumbnail      = $this->resize_photo($file, 70, 70, false);
		$encounter      = $this->resize_photo($file, 296, null);
		$other          = $this->resize_photo($file, 195, null);

		$original_image->save(public_path().'/uploads/others/original/'.$fileName);
		$this->compress(
			public_path().'/uploads/others/original/'.$fileName,
			public_path().'/uploads/others/original/'.$fileName,
			75
		);
		$thumbnail->save(public_path().'/uploads/others/thumbnails/'.$fileName);
		$encounter->save(public_path().'/uploads/others/encounters/'.$fileName);
		$other->save(public_path().'/uploads/others/'.$fileName);
	}

	public function resize_photo ($image, $new_width , $new_height, $aspect_ratio_needed = true) {

		$image = Image::make($image);
		$image->resize($new_width, $new_height, function ($c) use($aspect_ratio_needed){
	    	if ($aspect_ratio_needed) {
	    		$c->aspectRatio();
	    	}
		});

		return $image;
	}

	//this function returns all user interests
	public function getInterests($userid)
	{
		$interests = $this->user_interests->where('userid', '=', $userid)->get();
		$interests->count = count($interests);

		return $interests;
	}

	//this function returns suggestions from interests database
	public function getSuggestions ($string) {

		$string = strtolower ($string);
		$suggestions = $this->interests->where ('interest', 'like', "$string%")->get();

		$suggestions->count = count($suggestions);

		$suggestionsArray = array ();

		if ($suggestions->count > 0) {

			foreach ($suggestions as $suggestion) {

				$temp = array ('id' => $suggestion->id, 'interest' => $suggestion->interest);
				
				array_push($suggestionsArray, $temp);
			}

			return $suggestionsArray;

		} else {

			return $suggestionsArray;
		}
			
	}

	//this function returns true or false baseed on interest found in interests table or not
	public function findInterest ($interest) {

		$interest = strtolower($interest);
		$inter = $this->interests->where('interest','=', $interest)->first();

		return $inter;
	}

	public function isUserInterestExist($id, $interest) {

		$interest = strtolower($interest);
		$interest = $this->interests->where('interest','=', $interest)->first();

		if ($interest) {

			$userInterest = $this->user_interests->where('interestid', '=', $interest->id)->where('userid',$id)->first();
			
			if ($userInterest) {

				return true;

			} else {

				return false;
			}

		} else {

			return false;
		}
	}

	public function getInterest($str)
	{
		$str = strtolower($str);
		$inter = $this->interests->where('interest','=', $str)->first();

		return $inter;
	}

	//this function adds interest string for particular user into userinterests table
	public function addToUserInterests($id, $interestid)
	{
		$userInterests = clone $this->user_interests;

		$userInterests->userid = $id;
		$userInterests->interestid = $interestid;
		
		$userInterests->save();

		return $userInterests;
	}

	//this function adds interest string into interests table
	public function addToInterests ($interest) {
		
		$interests = clone $this->interests;
		
		$interests->interest = $interest;
		
		$interests->save();

		return $interests;
	}


	//this function deletes interes of user
	//userid and interest id have to pass from userinterest table
	public function deleteInterest ($userid, $interestid) {

		$userInterest = $this->user_interests->where('userid', '=', $userid)
									->where('interestid', '=', $interestid)
									->first();	

		if ($userInterest) {
			
			$userInterest->delete();
		}

	}





	public function getUserInterests ($id) {

		return $this->user_interests->where('userid', '=', $id)->get();

	}

	public function isInterestExist ($user_id, $interest_id) {

		$interest = $this->user_interests->where('userid', '=', $user_id)
									->where('interestid', '=', $interest_id)
									->first();

		if ($interest) {

			return true;

		} else {

			return false;
		}

	}




	public function getUserPhotos ($userid, $flag = false) {

		if ($flag) {

			return $this->photo->where('userid', '=', $userid)->get();	

		} else {

			return $this->photo->where('userid', '=', $userid)->take(1)->get();	
		}
		

	}

	public function canSeePhotos ($userid) {

		

		if (Settings::_get('photo_restriction_mode') == 'true') {

			$user_photos_count   = $this->photo->where('userid', '=', $userid)->count();
			$minimum_photo_count = Settings::_get('minimum_photo_count');


			if ($user_photos_count < $minimum_photo_count) {
				
				return false;

			} else  {
				
				return true;
			}

		} else {

			return true;
		}


		
	}



	public function getMinPhotoCount () {

		$count = Settings::_get('minimum_photo_count');
		return ($count) ? $count : 0;
	}
	
	public function getPhotoRestrictionMode() {
		
		$mode = Settings::_get("photo_restriction_mode");
		return ($mode != '') ? $mode : "false";
	}

	





	public function savePersonalInfo ($user_id, $data) {

		$user = $this->user->find($user_id);

		foreach ($data as $column => $value) {

			$user->profile->$column = $value;
		}
		
		$user->profile->save();
	}

	



	public function validateLocationInfo ($request, &$errors) {

		$validator = Validator::make($request->all(), [

			'lat' => 'required',
			'long' => 'required',
			'city' => 'required|max:255',
			'country' => 'required|max:255',
        ]);


		if ($validator->fails()) {

			$errors = $validator->errors()->all();
			return false;
		}

		return true;

	}


	public function saveLocation ($user_id, $data) {

		$user = $this->user->find($user_id);

		foreach ($data as $column => $value) {

			$user->$column = $value;
		}

		$user->save();

		unset($data['city']);
		unset($data['country']);

		foreach ($data as $column => $value) {

			$user->profile->$column = $value;
		}

		$user->profile->save();
	}


	public function getValidImageExtension ($image_type) {
		
		$image_types = [
			'image/png'  => '.png',
			'image/jpg'  => '.jpeg',
			'image/jpeg' => '.jpeg',
			'image/bmp'  => '.bmp',
		];

		return (isset($image_types[$image_type])) ? $image_types[$image_type] : '';
	}


	public function saveProfilePicture ($user_id, $file, $crop_width, $crop_height, $crop_x, $crop_y) {

		if (UtilityRepository::validImage($file, $ext)) {

			$fileName = UtilityRepository::generate_image_filename("{$user_id}_", $ext);
			
			$this->save_crop_resize_photo($file, $fileName, $crop_width, $crop_height, $crop_x, $crop_y);		
			$this->createPhotoEntry($user_id, $fileName);
			return $fileName;
		}
		
		
		throw new \Exception('error');

	}


	public function createPhotoEntry($user_id, $photo_name) {

		$photo = clone $this->photo;

		$photo->userid    = $user_id; 
		$photo->photo_url = $photo_name;

		$photo->save();
	}



	public function validateProfilePicture ($request, &$errors) {

		$validator = Validator::make($request->all(), [

			'profile_picture' => 'required',
			'crop_width'      => 'required',
			'crop_height'     => 'required',
			'crop_x'          => 'required',
			'crop_y'          => 'required',

        ]);


		if ($validator->fails()) {

			$errors = $validator->errors()->all();
			return false;
		}

		return true;
	}





	public function save_crop_resize_photo($file, $fileName, $crop_width, $crop_height, $crop_x, $crop_y) {

		$size = getimagesize($file);
		$file  = file_get_contents($file);
		$image  = Image::make($file);

		$thumbnail = $image->crop(
			round($crop_width), round($crop_height), 
			round($crop_x), round($crop_y)
		);

		$thumbnail = $this->resize_photo($thumbnail, 70, 70, false);
		$thumbnail->save(public_path().'/uploads/others/thumbnails/'.$fileName);

		$width = ($size[0] > 700) ? 700 : $size[0];
		$original  = $this->resize_photo($file, $width, null);
		$encounter = $this->resize_photo($file, 296, null);
		$other     = $this->resize_photo($file, 195, null);

		$original->save(public_path().'/uploads/others/original/'.$fileName);
		$this->compress(
			public_path().'/uploads/others/original/'.$fileName, 
			public_path().'/uploads/others/original/'.$fileName, 
			75
		);
		$encounter->save(public_path().'/uploads/others/encounters/'.$fileName);
		$other->save(public_path().'/uploads/others/'.$fileName);
		
	}





	public function deletePhoto ($auth_user_id, $photo_name) {

		$auth_user = $this->user->find($auth_user_id);
		//delete photo from photo table
		$photo = $this->photo->where('userid', $auth_user_id)->where('photo_url', $photo_name)->first();


		if ($photo) {

			//delete phto form photo table
			$photo->delete();

			if ($auth_user->profile_pic_url == $photo_name) {

				//set default profile picture to user profile picture

				$auth_user->profile_pic_url = Settings::_get('default_'.$auth_user->gender);
				$auth_user->save();

			}

			return true;
		}

		return false;
	}





	public function changeProfilePicture ($auth_user_id, $photo_name) {

		$auth_user = $this->user->find($auth_user_id);

		$auth_user->profile_pic_url = $photo_name;

		$auth_user->save(); 

	}
	
	
	public function validateBasicInfo ($request, &$errors) {

        $validator = Validator::make($request->all(), [
        
            'name'   => 'required|min:4|max:100',
            'gender' => 'required',
            'dob'    => 'required|date_format:d-m-Y|before:18 years ago',

       ]);

        if ($validator->fails()) {
            
            $errors = $validator->errors()->all();
            return false;
        }

        return true;
    }


    public function createDateFromFormat ($date) {

    	return \DateTime::createFromFormat('d-m-Y', $date);
    }


    public function saveBasicInfo ($user_id, $data) {
    
        $user = $this->user->find($user_id);
        
        foreach ($data as $column => $value) {
        
            $user->$column = $value;
        }

        $user->createSlugName();
        
        $user->save();
    }

    public function switchIfDefault($user, $gender)
    {
    	if($user->profile_pic_url == Settings::_get('default_'.$user->gender))
    	{
    		$user->profile_pic_url = Settings::_get('default_'.$gender);
    		$user->save();
    	}
    	return $user;
    }

    public function getUserAge ($user_id) {

    	$user = $this->user->find($user_id);
    	return ($user) ? $user->age() : 0;
    }

    public function getUserOnlineStatus ($user_id) {

    	$user = $this->user->find($request->user_id);

        if ($user) {
			return $user->onlineStatus();
        }

       	return false;
    }

    // function that return profile percentage
    public function profileCompletePercent ($user) {

        $custom_field_remain_percentage = 58;

        $percent = 5;
        $percent += ($this->ownProfilePicture($user->profile_pic_url)) ? 20 : 0;
       	$percent += ($user->city != '' && $user->country != '') ? 5 : 0;        
        $percent += ($user->dob) ? 2 : 0;
        
        if($this->settings->get('profile_interests_show_mode') == 'true') {
        	$percent += ($this->getTotalUserInterestsCount($user->id) > 0) ? 10 : 0;	
        } else {
        	$custom_field_remain_percentage += 10;
        }
        

        $count_user_fields = $this->user_fields->where('user_id', $user->id)->count();
		$count_all_fields = $this->fields->count();
		if ($count_user_fields > $count_all_fields)
			$custom_field_percent = $custom_field_remain_percentage;
		else
			$custom_field_percent = ($count_user_fields / $count_all_fields) * $custom_field_remain_percentage;

		$percent += $custom_field_percent;

		return round($percent);
    }

    public function getAllFields () {
    	return $this->fields->all();
    }

    public function ownProfilePicture ($profile_picture) {
    	
    	$gender_fields = $this->generalRepo->getGenderField();
    	
    	foreach ($gender_fields->field_options as $gender) {
    		$default_picture = UtilityRepository::get_setting("default_".$gender->code);
    		if ($default_picture == $profile_picture) {
    			return false;
    		}
    	}

    	return true;
    }




    public function getPhotoByName ($photo_name) {
    	return $this->photo->where('photo_url', $photo_name)->first();
    }



    public function getPopularityType ($popularity){
		
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







	public function getInterestsByUserId ($user_id, $last_user_interest_id = 0) {


		/* if last_user_interest_id is 0 then take last 20 interests
			else take 20 interests previous than last_user_interest_id
		*/
		if ($last_user_interest_id == 0) {
			$user_interests = $this->user_interests->join('interests', 'interests.id', '=', 'userinterests.interestid')
											->where('userinterests.userid', $user_id)
											->orderBy('userinterests.created_at', 'desc')
											->orderBy('userinterests.id', 'desc')
											->select(['userinterests.*', 'interests.interest'])
											->take(20)
											->get();
		} else {
			$user_interests = $this->user_interests->join('interests', 'interests.id', '=', 'userinterests.interestid')
											->where('userinterests.userid', $user_id)
											->where('userinterests.id', '<', $last_user_interest_id)
											->orderBy('userinterests.created_at', 'desc')
											->orderBy('userinterests.id', 'desc')
											->select(['userinterests.*', 'interests.interest'])
											->take(20)
											->get();
		}
		

		return $user_interests;

	}


	public function getTotalUserInterestsCount($user_id) {
		return $this->user_interests->where('userid', '=', $user_id)->count();
	}




	/**
	* Used to retrive mutual facebook friends
	*
	* just pass both users ids and last_row record to get mututal facebook friends of both users
	*
	* @param user1 first user id
	* @param user2 second user id
	* @param last_row_id cursor for rows to retrive value can be 
	*	(ALL--> retrive all uses atonce, 0 --> retrive first 20 mutual friends, last_row_id_retrived before --> to retrive furthur mutual friends)
	*
	* @return return mutual friends user object
	*/
	public function getFacebookMutualFrields($user1, $user2, $last_row_id = 0) {	

		$query_build = $this->user->join('facebook_friends', 'user.id', '=', 'facebook_friends.user2')
							->where('facebook_friends.user1', '=', $user1)
							->where('user.activate_user', '=', 'activated')
							->whereRaw("user2 IN (SELECT user2 FROM facebook_friends WHERE user1 = {$user2} AND deleted_at is null)")
							->select('user.*');



		if ($last_row_id == 'ALL') {
			
			return $query_build->get();

		} else if ($last_row_id == 0) {

			return $query_build->take(20)->get();

		} else {
			
			return $query_build->where('facebook_friends.id', '>', $last_row_id)->take(20)->get();

		}

	}


	/**
	* Used to retrive total count mutual facebook friends
	*
	* just pass both users ids to get total count mututal facebook friends of both users
	*
	* @param user1 first user id
	* @param user2 second user id
	*
	* @return return total count of mutual friends (integer)
	*/

	public function getTotalFacebookMutualFriendsCount($user1, $user2) {

		return $this->user->join('facebook_friends', 'user.id', '=', 'facebook_friends.user2')
							->where('facebook_friends.user1', '=', $user1)
							->where('user.activate_user', '=', 'activated')
							->whereRaw("user2 IN (SELECT user2 FROM facebook_friends WHERE user1 = {$user2} AND deleted_at is null)")
							->count();
	}





	/**
	* Used to retrive total count common interests
	*
	* @param user_id1 first user id
	* @param user_id2 second user id
	*
	* @return return total count of common interests (integer)
	*/
	public function getCommonInterestsCount ($user_id1, $user_id2) {

		return $this->user_interests->join('interests', 'interests.id', '=', 'userinterests.interestid')
							->where('userid', '=', $user_id1)
							->whereRaw( "interestid IN (SELECT interestid FROM userinterests WHERE userid = {$user_id2} AND deleted_at is null)")
							->count();

    }


    /**
	* Used to retrive common interests
	*
	* @param user_id1 first user id
	* @param user_id2 second user id
	* @param last_row_id cursor for rows to retrive value can be 
	*	(ALL--> retrive all uses atonce, 0 --> retrive first 20, last_row_id_retrived_before(int) --> to retrive furthur common interests)
	* @return return interest objects (integer)
	*/
	public function getCommonInterests ($user_id1, $user_id2, $last_row_id = 0) {

		$query_build = $this->user_interests->join('interests', 'interests.id', '=', 'userinterests.interestid')
							->where('userid', '=', $user_id1)
							->whereRaw( "interestid IN (SELECT interestid FROM userinterests WHERE userid = {$user_id2} AND deleted_at is null)")
							->select(['userinterests.*', 'interests.interest']);


		if ($last_row_id == 'ALL') {
			
			return $query_build->get();

		} else if ($last_row_id == 0) {

			return $query_build->take(20)->get();

		} else {
			
			return $query_build->where('userinterests.id', '>', $last_row_id)->take(20)->get();

		}
    }



}


