<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\RegisterRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\Admin\UtilityRepository;
use App\Repositories\Admin\GeneralManageRepository;
use App\Components\Plugin;
use Validator;
use Hash;
use App\Models\User;

class RegisterController extends Controller {

    protected $registerRepo = null;
    protected $generalRepo  = null;
    protected $profileRepo  = null;
    protected $userRepo     = null;
	public function __construct (RegisterRepository $registerRepo, 
								GeneralManageRepository $generalRepo,
								ProfileRepository $profileRepo,
                                UserRepository $userRepo) {
        $this->registerRepo = $registerRepo;
        $this->generalRepo  = $generalRepo;
        $this->profileRepo  = $profileRepo;
        $this->userRepo     = $userRepo;
	}


	public function doRegister (Request $req) {

		$errors = [];
        
        if ($this->registerValidate($req->all(), $errors)) {

        	$arr                    = [];
            $arr['username']        = $req->username;
            $arr['password']        = Hash::make($req->password);
            $arr['gender']          = $req->gender;
            $arr['dob']             = \DateTime::createFromFormat('d/m/Y', $req->dob);
            $arr['name']            = $req->name;
            $arr['activate_user']   = "activated";
            $arr['verified']        = "unverified";
            $arr['latitude']        = $req->lat;
            $arr['longitude']       = $req->lng;                     
            $arr['city']            = $req->city;   
            $arr['country']         = $req->country;
            $arr['activate_token']  = str_random(60) . $req->username;
            $arr['register_from']   = UtilityRepository::get_setting('website_title');
            $arr['profile_pic_url'] = UtilityRepository::get_setting('default_'.$req->gender);      
            
            $user = $this->registerRepo->register($arr); 
			$user->createAndSaveSlugName();

			$user_fields = array();
	        $sections = $this->profileRepo->get_fieldsections();
	            
            foreach($sections as $section)
            {
                foreach($section->fields as $field)
                {
                    if($field->on_registration == 'yes')
                    {
                        $code = $field->code;
                        if(isset($req->$code))
                        {
                            $user_fields[$field->id] = $req->$code;
                        }
                    }
                }
            }
	        
	        $this->profileRepo->saveUserFields($user->id, $user_fields);

            $gender['user_id'] = $user->id;
            $gender['value'] = $req->gender;
            $this->profileRepo->saveGender($gender);            

            //for bot create
            Plugin::fire('account_create', $user);
	            
            //sending registratin mail
            $this->register_after_mail_send_event();
            $this->sendRegistrationMail ($user);

            $user = $this->userRepo->getUserById($user->id);
            $token = $this->userRepo->createAndSaveAccessToken($user);
            $email_verify_required = ($user->activate_user == 'activated')  ? 0 : 1;

	        return response()->json(['status' => 'success', 
	        	"success_data" => [
	        		"success_text" => "User registered Successfully",
                    "user_id" => $user->id,
                    "access_token" => $token,
	        		'email_verify_required' => $email_verify_required,
                    "profile_pictures" => [
                        "thumbnail" => $user->thumbnail_pic_url(),
                        "encounter" => $user->encounter_pic_url(),
                        "other"     => $user->others_pic_url(),
                        "original"  => $user->profile_pic_url(),
                    ]
	        	]
	        ]);
        }

        $errors['error_text'] = 'Validation Error';
        return response()->json([
        	"status" => "error",
        	"error_data" => $errors
        ]);
   
       

	}

	//this method format validatin erros as per fields
    public function formatRegisterErrors ($errors) {

        $messages = [];

		($errors->has('name')) ? $messages['name']         = $errors->get('name')[0] : '';
		($errors->has('dob')) ? $messages['dob']           = $errors->get('dob')[0] : '';
		($errors->has('username')) ? $messages['username'] = $errors->get('username')[0] : '';
		($errors->has('lat')) ? $messages['lat']           = $errors->get('lat')[0] : '';
		($errors->has('lng')) ? $messages['lng']           = $errors->get('lng')[0] : '';
		($errors->has('city')) ? $messages['city']         = $errors->get('city')[0] : '';
		($errors->has('country')) ? $messages['country']   = $errors->get('country')[0] : '';
		($errors->has('gender')) ? $messages['gender']     = $errors->get('gender')[0] : '';
		($errors->has('password')) ? $messages['password'] = $errors->get('password')[0] : '';

        return $messages;
    }



	public function registerValidate ($request_data, &$errors) {

        $validator = Validator::make($request_data, [
			'name'                  => 'required|max:100',
			'dob'                   => 'required|date_format:d/m/Y|before:18 years ago',
			'username'              => 'required|email|max:100|unique:user,username',
			'lat'                   => 'required',
			'lng'                   => 'required',
			'city'                  => 'required',
			'country'               => 'required',
			'gender'                => 'required',
			'password'              => 'required|min:8|max:100|confirmed',
			'password_confirmation' => 'required|min:8|max:100',
        ]);

        if ($validator->fails()) {
        	$errors = $this->formatRegisterErrors($validator->errors());
        	return false;
        }

        return true;

    }


	public function register_after_mail_send_event () {

        Plugin::hook('after_mail_send', function($user){

            $user->activate_user = 'deactivated';
            $user->save();

        });

    }




    public function sendRegistrationMail ($user) {

        $data          = new \stdCLass;
        $data->user    = $user;
        $data->type    = 'account_activation';
        
        Plugin::fire('send_email', $data);


    }


    public function getCustomFields () {

    	//generating gender and options
    	$gender = $this->generalRepo->getGenderField();
		$gender->text = trans('custom_profile.'.$gender->code);

		$gender_options = [];
		foreach ($gender->field_options as $option) {
			$option->text = trans('custom_profile.'.$option->code);
			array_push($gender_options, $option);
		}
        $gender->on_registration = 'yes';
		$gender->options = $gender_options;


		//generating other custom fields
		$other_fields = [];
    	
    	foreach ($this->profileRepo->get_fieldsections() as $section) {

            $section->text = trans('custom_profile.'.$section->code);
            $fields = [];

            foreach($section->fields as $field) {
                
                if($field->type == "dropdown" && $field->code != 'gender') {

                    $field->text = trans('custom_profile.'.$field->code);
                    $options = [];
                    foreach($field->field_options as $option) {
                        $option->text = trans('custom_profile.'.$option->code);
                        array_push($options, $option);
                    }
                    $field->options = $options;

                } else if ($field->type == 'text') {
                    $field->text = trans('custom_profile.'.$field->code);

                } else if ($field->type == 'textarea') {
                    $field->text = trans('custom_profile.'.$field->code);
                }
                
                array_push($fields, $field);
                $section->fields = $fields;
            }
            
            array_push($other_fields, $section);
        }


    	return response()->json(["status" => "success", "success_data" => [
    		"gender" => $gender,
    		"other_fields" => $other_fields,
    		"success_text" => "Custom profile fields retrived successfully."
    	] ]);
    }


}