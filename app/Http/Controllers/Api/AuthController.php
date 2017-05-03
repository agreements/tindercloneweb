<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\RegisterRepository;
use Validator;
use Hash;
use App\Models\User;
use App\Components\Plugin;

class AuthController extends Controller {

	protected $userRepo = null;
	protected $registerRepo = null;
	public function __construct (UserRepository $userRepo, RegisterRepository $registerRepo) {
		$this->userRepo     = $userRepo;
		$this->registerRepo = $registerRepo;
	}


	public function doLogin (Request $req) {

		if ($this->validLoginData($req->all(), $errors)) {

			$username = $req->username;
			$password = $req->password;

			$user = $this->userRepo->getUserByEmail($username);

			if (!is_null($user)) {

				$user->createAndSaveSlugName();
				
				if (Hash::check($password, $user->password)) {

					$token = $this->userRepo->createAndSaveAccessToken($user);

					$status = $this->userRepo->get_user_setting($user->id, 'activation_status');
		            if ($status != '') {
		                $user->activate_user = 'activated';
		                $this->userRepo->remove_user_setting($user->id, 'activation_status');
		                $user->save();
		            }
					
					if ($user->activate_user == 'deactivated') {
						$errors['error_text'] = 'User account not activated';
					} else {
						return response()->json([
							"status" => "success", 
							"success_data" => [
								"user_id"                => $user->id, 
								"name"                   => $user->name, 
								"username"               => $user->username,
								"credits"                => $user->credits->balance,
								"access_token"           => $user->access_token,
								"last_request_timestamp" => $user->last_request,
								"profile_pictures" => [
			                        "thumbnail" => $user->thumbnail_pic_url(),
			                        "encounter" => $user->encounter_pic_url(),
			                        "other"     => $user->others_pic_url(),
			                        "original"  => $user->profile_pic_url(),
			                    ],
								"success_text"           => "User logged in successfully."
							]
						]);
					}

				} else {
					$errors['error_text'] = 'Password not matched.';
				}

			} else {
				$errors['error_text'] = 'User not registered.';
			}
		
		} else {
			$errors['error_text'] = 'Login Validation failed.';
		}

		
		return response()->json(["status" => "error", "error_data" => $errors]);
	}


	protected function validLoginData ($req_data, &$errors) {

		$validator = Validator::make($req_data ,[   
            'username' => 'required|email|max:100',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            $errors = $this->formatLoginErrors($validator->errors());
            return false;
        }

        return true;
	}


	protected function formatLoginErrors ($errors) {
		
		$messages = [];
        ($errors->has('username')) ? $messages['username'] = $errors->get('username')[0] : '';
        ($errors->has('password')) ? $messages['password']   = $errors->get('password')[0] : '';
        return $messages;
	}




	public function forgotPassword (Request $req) {
		$user = $this->registerRepo->forgotPassword($req->username);

        if ($user) {

            $data       = new \stdClass;
            $data->user = $user;
            $data->type = "password_recover";
            
            $status = Plugin::fire('send_email', $data);

            if(isset($status[0]['success'])) {

                return response()->json([
		            'status'=>'success', 
		            "success_data" => [
		                "success_text" => "Password recovery link has been sent to your registered email id. Please reset your password."
		            ]
		        ]);
            }
                
            return response()->json([
	            'status'=>'error', 
	            "error_data" => [
	                "error_text" => "Email not sent. Try again."
	            ]
	        ]);
        }

        return response()->json([
            'status'=>'error', 
            "error_data" => [
                "error_text" => "Invalid email id."
            ]
        ]);
	}






}