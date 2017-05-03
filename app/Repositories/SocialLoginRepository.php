<?php

namespace App\Repositories;
     
use App\Components\Plugin;
use Auth;

class SocialLoginRepository
{
	public function __construct() 
	{
		$this->userRepo         = app('App\Repositories\UserRepository');
		$this->registerRepo     = app('App\Repositories\RegisterRepository');
		$this->profileRepo      = app('App\Repositories\ProfileRepository');
		$this->settings         = app('App\Models\Settings');
		$this->fields           = app("App\Models\Fields");
		$this->maxmindGEOIPRepo = app('App\Repositories\MaxmindGEOIPRepository');
		$this->photo            = app('App\Models\Photo');
	}

	protected $data_array = [
		'verified' => 'verified',
		'activate_user' => 'deactivated',
		'profile_pic_url' => ''
	];

	
	protected $data_incomplete = true;
	protected $name = '';
	protected $username = '';
	protected $verified = 'verified';
	protected $activate_user = 'deactivated';
	protected $socialMedia = '';
	protected $gender = '';
	protected $password_token = '';
	protected $dob = '';
	protected $city = '';
	protected $country = '';
	protected $latitude = '';
	protected $longitude = '';
	protected $profile_pic_url = '';

	protected $user = null;
	protected $socialAccountLinked = false;


	protected $socialDefaultPicture = true;
	protected $socialPicURL = '';



	public function setDOB($date)
	{
		if($date != null) {
			$this->data_array['dob'] = $this->dob = $date;
		}

		return $this;
	}



	public function getDOB()
	{
		return $this->dob;
	}




	public function setUser($user)
	{
		$this->user = $user;
		return $this;
	}


	public function getUser()
	{
		return $this->user;
	}


	public function user()
	{
		return $this->user;
	}



	public function setLongitude($longitude) 
	{
		$this->longitude = $this->data_array['longitude'] = $longitude;
		return $this;
	}



	public function getLongitude()
	{
		return $this->longitude;
	}





	public function setLatitude($latitude) 
	{
		$this->latitude = $this->data_array['latitude'] = $latitude;
		return $this;
	}



	public function getLatitude()
	{
		return $this->latitude;
	}




	public function setCountry($country) 
	{
		$this->country = $this->data_array['country'] = $country;
		return $this;
	}



	public function getCountry()
	{
		return $this->country;
	}




	public function setCity($city) {
		$this->city = $this->data_array['city'] = $city;
		return $this;
	}



	public function getCity()
	{
		return $this->city;
	}





	public function setDataIncomplete($dataIncomplete = true)
	{
		$this->data_incomplete = $dataIncomplete;
		return $this;
	}



	public function getDataIncomplete()
	{
		return $this->data_incomplete;
	}





	protected function generatePasswordToken()
	{
		if(isset($this->username)) {
			return str_random(60).$this->username;	
		} else {
			return str_random(60).uniqid();
		}
		
	}



	public function setPasswordToken()
	{
		$this->password_token = $this->data_array['password_token'] = $this->generatePasswordToken();
		return $this;
 	}



 	public function getPasswordToken()
 	{
 		return $this->password_token;
 	}






	public function setSocialmedia($socialMedia)
	{
		if($socialMedia) {
			$this->socialMedia = $this->data_array['register_from'] = $socialMedia;
		}

		return $this;
	}




	public function getSocialMedia()
	{
		return $this->socialMedia;
	}





	public function setActivateUser($activate = 'deactivated')
	{
		$this->activate_user = $this->data_array['activate_user'] = $activate;
		return $this;
	}



	public function getActivateUser()
	{
		return $this->activate_user;
	}





	public function setVerify($verify = 'verified') 
	{
		$this->verified = $this->data_array['verified'] = $verify;
		return $this;
	}


	public function getVerify()
	{
		return $this->verified;
	}




	public function setUsername($username)
	{
		if($username) {
			$this->username = $this->data_array['username'] = $username;
		}

		return $this;
	}


	public function getUsername()
	{
		return $this->username;
	}




	public function setName($name) 
	{
		if($name) {
			$this->name = $this->data_array['name'] = $name;
		}

		return $this;
	}


	public function getName()
	{
		return $this->name;
	}



	public function setGender($gender = '')
	{
		if($gender) {
			$this->gender = $this->data_array['gender'] = $gender;
		} else {
			$this->gender = $this->data_array['gender'] = $this->getRandomGender();
		}

		return $this;
	}

	public function getGender()
	{
		return $this->gender;
	}





	public function getRandomGender()
	{
		return $this->genderOptions()->random()->code;
	}



	public function defaultGenderPicture($gender)
	{
		return $this->settings->get('default_'.$gender);
	}





	public function genderOptions()
	{
		if(isset($this->genderOptions)) {
			return $this->genderOptions;
		}
		
		return 	$this->genderOptions = $this->genderField()->field_options;
	}





	public function genderField()
	{
		if(isset($this->genderField)) {
			return $this->genderField;
		}

		return $this->genderField = $this->fields->getGenderField();
	}


	

	
  
	public function linkSocialAccount()
	{
		
		if(!$this->getUser() || !$this->getSocialMediaUser()) {
			return false;
		}

		$this->userRepo->insert_social_login($this->getSocialMediaUser(), $this->getUser(), $this->getSocialMedia());
		$this->socialAccountLinked = true;
     
        return true;
	}



	public function setSocialMediaUser($user)
	{
		$this->socialMediaUser = $user;
		return $this;
	}


	public function getSocialMediaUser()
	{
		if(isset($this->socialMediaUser)) {
			return $this->socialMediaUser;
		}
		
		return null;
	}



	public function setSocialDefultPicture($socialDefaultPicture, $picURL)
	{
		$this->socialDefaultPicture = $socialDefaultPicture;
		$this->socialPicURL = $picURL;

		return $this;
	}



	public function isSocialDefaultPicture()
	{
		return $this->socialDefaultPicture;
	}

	public function replaceDefaultProfilPicture()
	{
		if(!$this->user()) {
			return false;
		}

		if(!$this->isSocialDefaultPicture() && $this->user()->profile_pic_url == $this->defaultGenderPicture($this->user()->gender)) {
			$this->replacePicture($this->socialPicURL, $this->user());
		} 
	}


	// public function setProfilePicURL()
	// {
	// 	$this->profile_pic_url = $this->data_array = $this->defaultGenderPicture($this->user()->gender);
	// 	return $this;
	// }



	public function replacePicture($imageUrl, $user = null)
	{
		if(!$user) {
			$user = $this->user();
		}


		$fileName = uniqid($user->id . '_') . '_' . rand(10000000, 99999999) . '.jpg';
        $this->profileRepo->save_resize_photo($imageUrl, $fileName);
        $this->insertPhoto($fileName, $user->id, $this->getSocialMediaUser()->id, $this->getSocialMedia());
        $user->profile_pic_url = $fileName;
        $user->save();
	}



	public function insertPhoto($imageName, $userID, $sourcePhotoID, $phtoSource)
	{
		
		$photo = new $this->photo;

        $photo->userid          = $userID;
        $photo->source_photo_id = $sourcePhotoID;
        $photo->photo_source    = $phtoSource;
        $photo->photo_url       = $imageName;
        
        $photo->save();

        return $photo;
		
	}




	public function doLogin()
	{
		if($this->getIfUserRegistered()) {

			$user = $this->getUser();
			$this->maxmindGEOIPRepo->updateUserLocation($user);

			$this->checkDataIncomplete();
			$this->replaceDefaultProfilPicture();
			$user->createAndSaveSlugName();
			$this->linkSocialAccount();
			$this->activateUserIfDeactivatedByItself();

			return $this->login();

		} else if($this->createNewUser()){

			$this->setProfilePicture();
			$created_user = $this->registerRepo->register($this->data_array);
			$this->setUser($created_user);
			$this->maxmindGEOIPRepo->updateUserLocation($created_user);
			
			$this->replaceDefaultProfilPicture();
			$this->checkDataIncomplete();
			$created_user->createAndSaveSlugName();
			$this->linkSocialAccount();
			Plugin::fire('account_create', $created_user);

			return $this->login();
		} 
	}

	public function createNewUser() {
		return true;
	}


	public function setProfilePicture()
	{
		$this->data_array['profile_pic_url'] = $this->profile_pic_url = $this->defaultGenderPicture($this->getGender());
	}



	public function login()
	{
		if($this->getUser()->activate_user == 'activated') {
			Auth::login($this->getUser(), false);

			return ['user' => $this->getUser(), 'data_incomplete' => $this->getDataIncomplete(), 'login' => true];
		}

		return ['user' => $this->getUser(), 'data_incomplete' => $this->getDataIncomplete(), 'login' => false];
	}





	public function activateUserIfDeactivatedByItself()
	{
		$status = $this->userRepo->get_user_setting($this->user()->id, 'activation_status');
        if ($status != '') {
           $this->activateUser();
           $this->userRepo->remove_user_setting($this->user()->id, 'activation_status');
        }
	}



	public function activateUser($user = null)
	{
		if(!$user) {
			$user = $this->getUser();
		}
		
		$user->activate_user = 'activated';
		$user->save();
	}




	public function checkDataIncomplete()
	{
		$data_incomplete = (!isset($this->getUser()->city) 
                                || !isset($this->getUser()->country) 
                                || !isset($this->getUser()->latitude) 
                                || !isset($this->getUser()->longitude))
                                ? true : false;
        
        if ($this->getUser()->name == null) $data_incomplete = true;



        $user = $this->getUser();

        if(is_null($user->dob) || $user->dob == "0000-00-00" || \Carbon\Carbon::parse($user->dob)->diffInYears(\Carbon\Carbon::now()) < 18) {
        	$data_incomplete = true;
        }

		$this->setDataIncomplete($data_incomplete);

	}



	public function getIfUserRegistered()
	{
		if($this->username) {
			return $this->user =  $this->userRepo->getUserByEmail($this->username);
		}

		if($this->getSocialAccount()) {
			return $this->user = $this->getSocialAccount()->user;
		}
		
		$this->setUser(null);
		return null;
	}



	
	public function getSocialAccount()
	{
		if(isset($this->socialAccountObject)) {
			return $this->socialAccountObject;
		}

		return $this->socialAccountObject = $this->userRepo->getSocialLoginBySrc($this->getSocialMediaUser()->id, $this->getSocialMedia());
	}


}

