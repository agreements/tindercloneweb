<?php 
namespace App\Repositories;

use App\Models\User;
use App\Models\Profile;
use App\Models\Settings;
use Hash;
use Mail;
use Log;


class RegisterRepository {

	public function __construct(User $user, Profile $profile, Settings $settings) {
		$this->user     = $user;
		$this->profile  = $profile;
		$this->settings = $settings;
	}



	//checking whether username registered or not
	//parameter : $email
	public function isUsernameExisted ($username) {
		$username = $this->user->where('username', '=', $username)->first();
		return ($username) ? true : false;
	}


	public function register ($arr) {
		return $this->user->create($arr);
	}




	public function activateUser ($id, $token) {

	    $logUser = $this->user->find($id);
	    
		if($logUser && $logUser->id == $id && $logUser->activate_token == $token) {
		    $logUser->activate_user = "activated";
		    $logUser->save();
		    return true;
		}
		return false;	
	}

	public function forgotPassword($username) {

		$user = $this->user->where('username', '=', $username)->first();
		
		if($user)
		{
			$password_token       = str_random(60) . $username;
			$user->password_token = $password_token; 
			$user->save();

			return $user;			
		}
		
		return false;
		
	}

	public function resetPasswordSubmit($id, $token , $password, $confirmPassword) {

		$user = $this->user->where('id', '=', $id)->first();
		
		if($user) {

			if($password == $confirmPassword && $user->password_token == $token && $password != '') {	
				$user->password = Hash::make($password);
				$user->save();
				return true;
			
			} else {	
				return false;
			}
		
		} else {
			return false;
		}

	}

}
