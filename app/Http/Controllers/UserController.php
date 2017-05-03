<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
//repository use
use Auth;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\VisitorRepository;
use App\Repositories\RegisterRepository;
use App\Repositories\CreditRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\EncounterRepository;
use App\Repositories\LanguageRepository;
use App\Repositories\Admin\UtilityRepository;
use App\Repositories\NotificationsRepository;
use App\Components\Theme;
use App\Components\Plugin;
use App\Models\UserSocialLogin;
use Hash;
use Mail;
use DB;
use stdClass;
use Validator;

class UserController extends Controller
{
    protected $userRepo;
    protected $registerRepo;
    protected $creditRepo;
    protected $profileRepo;
    protected $encounterRepo;
    protected $langRepo;
    protected $notifRepo;
    protected $visitorRepo;
    
    public function __construct(ProfileRepository $profileRepo, UserRepository $userRepo, RegisterRepository $registerRepo, CreditRepository $creditRepo, EncounterRepository $encounterRepo, LanguageRepository $langRepo, NotificationsRepository $notifRepo, VisitorRepository $visitorRepo)
    {
        $this->userRepo      = $userRepo;
        $this->registerRepo  = $registerRepo;
        $this->creditRepo    = $creditRepo;
        $this->profileRepo   = $profileRepo;
        $this->encounterRepo = $encounterRepo;
        $this->langRepo      = $langRepo;
        $this->notifRepo     = $notifRepo;
        $this->visitorRepo   = $visitorRepo;
    }   

    

    public function deactivateUser () {

        try {
            
            $auth_user_id = Auth::user()->id;
            app('App\Repositories\Admin\UserManagementRepository')->deactivateUsers([$auth_user_id]);   

            $this->userRepo->set_user_setting($auth_user_id, 'activation_status', '0');

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        

    }

    public function deleteUser(Request $request) {

        try {

            if ( Auth::user()->password != '' && !password_verify($request->confirm_password, Auth::user()->password) ) {
                return response()->json(['status' => 'error']);
            } 


            $auth_user_id = Auth::user()->id;
            app('App\Repositories\Admin\UserManagementRepository')->delete_users_permenently([$auth_user_id]);
            
            Plugin::fire('users_deleted', [ [$auth_user_id] ]);
            

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
            
    }



    public function save_left_fields (Request $request) {

        $user = Auth::user();

        //creating validator for login requests
        $validator = $this->userRepo->registerValidate($request->all(), $user->id);
        $errors    = $this->userRepo->formatErrors($validator->errors());

        //if input validation fails then response validation failed
        if ($validator->fails()) {
            
            return response()->json([
                'status' => 'error',
                'errors' => $errors
            ]);

        }
        
        


        $should_fire = false;
        if ($user->latitude == null || $user->longitude == null) {
            $should_fire = true;
        }

        /*insert user information to user table 
        and also create one row in profile table with same userid */
        try {
            
            $arr             = $request->all();
            
            $prev_gender     = $user->gender;
            $user->username  = $arr['username'];
            $user->name      = $arr['name'];
            $user->latitude  = $arr['lat'];
            $user->longitude = $arr['lng'];
            $user->gender    = $arr['gender_val'];
            $user->city      = ($arr['city'] == "undefined") ? "" : $arr['city'];
            $user->country   = $arr['country'];
            $user->dob       = \DateTime::createFromFormat('d/m/Y', $arr['dob']);
            $user->save();

            $user->createAndSaveSlugName();

            $sections = $this->profileRepo->get_fieldsections();
            
            $user_fields = [];
            foreach ($sections as $section) {
                foreach ($section->fields as $field) {
                    if ($field->on_registration == 'yes') {
                        $code = $field->code;


                        if($field->type == "text" || $field->type == 'textarea') {

                            if(isset($request->$code) && strlen($request->$code) > 0) {
                                $user_fields[$field->id] = $request->$code;
                            } else {
                                return response()->json([
                                    "status" => "error", 
                                    "errors" => [
                                        "custom_field" => trans('admin.require_attr', ['attr' => trans('custom_profile.'.$code)])
                                    ] 
                                ]);
                            }

                        } else {

                            if(isset($request->$code) && intval($request->$code) != 0) {
                                $user_fields[$field->id] = $request->$code;
                            } else {
                                return response()->json([
                                    "status" => "error", 
                                    "errors" => [
                                        "custom_field" => trans('admin.require_attr', ['attr' => trans('custom_profile.'.$code)])
                                    ] 
                                ]);
                            }

                        }

                    }
                }
            }
            $this->profileRepo->saveUserFields($user->id, $user_fields);
            $gender['user_id'] = $user->id;
            $gender['value']   = $request->gender_val;
            $this->profileRepo->saveGender($gender); 

            $defaul_gender_picture = UtilityRepository::get_setting("default_".$user->gender);
            $prev_default_gender_picture   = UtilityRepository::get_setting("default_".$prev_gender);
            if ($user->profile_pic_url == $prev_default_gender_picture) {
                $user->profile_pic_url = $defaul_gender_picture;
                $user->save();
            }

            session()->forget('data_incomplete');
            if ($should_fire) 
                Plugin::fire('account_create', $user);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {

            return response()->json(['status' => 'error', 'errors' => [$e->getMessage()] ]);

        }
    }
    
    public function save_privacy_settings(Request $request)
    {
        $user = Auth::user();
        $user->settings_save('online_status',$request->online_status);
        $user->settings_save('show_distance',$request->show_distance);
        return response()->json([
                    'status'  => 'success', 
                ]);
    }

    public function save_notif_settings(Request $request)
    {
        $this->notifRepo->set_notif_settings($request->all(),Auth::user()->id);

        return redirect('settings');
    }

    public function social_verification($id)
    {
        $arr = array();
        $user = $this->userRepo->getSocialAccounts($id);
        foreach($user as $u)
        {
            if($u->src == 'facebook')
                $arr['facebook'] = 1;
            if($u->src == 'google')
                $arr['google'] = 1;
        }
       
        return $arr;
    }

    public function changeEmail(Request $request)
    {
        
        $valid = Validator::make($request->all(), [
            'email'  => 'required|max:100|email|unique:user,username',
        ]);

        if ($valid->fails ()) {
            $messages = $valid->errors()->all();
            return response()->json(['status'=>'error','message'=>$messages[0]]);
        }

        $user  = Auth::user();
        $email = $request->email;

        $this->userRepo->changeEmail($user, $email);

        $email_array       = new stdCLass;
        $email_array->user = $user;
        $email_array->type = "change_email";

        Plugin::fire('send_email', $email_array);

        return response()->json([
            'status'  => 'success', 'message' => trans('app.success_change_email')
        ]);
    }

    public function changePassword(Request $request)
    {
        $user    = Auth::user();
        $oldPass = $request->oldPassword;

        if ($user->password == '' || password_verify($oldPass, $user->password)) {

            if ($request->password == $request->confirm_password) {

                $newPass = Hash::make($request->password);
                $user->password = $newPass;
                $user->save();
            
            } else {

                return response()->json(['status'  => trans('app.password_no_match')]);
            }
        } else {

            return response()->json(['status'  => trans('app.wrong_password')]);   
        }
   
        $email_array       = new stdCLass;
        $email_array->user = $user;
        $email_array->type = "change_password";

        Plugin::fire('send_email', $email_array);

        return response()->json([
            'status'  => 'success','message' => trans('app.password_change')
        ]);
    }

    public function settings() {

		$logId              = Auth::user()->id;
		$arr                = array();
		$logUser            = Auth::user();
		$like               = $this->encounterRepo->getAllLikes($logId);
		$languages          = $this->langRepo->getSupportedLanguages();
		$notif_settings     = $this->notifRepo->getNotificationSettings($logId);
		$privacy_settings   = $this->userRepo->getPrivacySettings($logId);
		$invisible_settings = $this->userRepo->getInvisibleSettings($logId);
		$user_password      =($logUser->password == '') ? false : true; 

        return Theme::view('settings', [

			'logUser'            => $logUser,
			'languages'          =>$languages,
			'notif_settings'     => $notif_settings,
			'privacy_settings'   => $arr,
			'like'               =>$like,
			"invisible_settings" => $invisible_settings,
			"privacy_settings"   => $privacy_settings,
			"user_password"      => $user_password

        ]);
    }

    public function setUserLanguage (Request $request) {

        try {

            if (!$request->language) {

                return response()->json(['status' => 'error']);

            }

            $user = Auth::user();
            $user->language = $request->language;
            $user->save();

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {

            return response()->json(['status' => 'error']);

        }

    }

    //email activation
    public function sampleActivate ($id,$token) {
        
        $success = $this->registerRepo->activateUser($id,$token);
		session()->flash('account_activation', (($success) ? 'true' : 'false'));        	
        
        return redirect('/login');    
    }

   // password reset
    public function forgotPassword()
    {
        //die($id);
        return Theme::view('forgotPassword_view');
    
    }

    public function forgotPasswordSubmit(Request $request)
    {
        $user = $this->registerRepo->forgotPassword($request->username);

        if ($user) {

            $data       = new \stdClass;
            $data->user = $user;
            $data->type = "password_recover";
            
            $status = Plugin::fire('send_email', $data);

            if(isset($status[0]['success'])) {

                session()->flash('message', trans('app.password_sent'));
                return back()->withInput();
            }
                
            session()->flash('message', trans('app.try_again'));
            return back()->withInput();
        }

        session()->flash('message', trans('app.valid_email'));
        return back()->withInput();
    }

    public function reset($id,$token)
    {
        return Theme::view('auth/forgotpassword', array('id'=>$id,'token'=>$token));
    
    }

    public function resetPassword(Request $request)
    {
        $response = $this->registerRepo->resetPasswordSubmit($request->id, $request->token, $request->password , $request->confirmPassword);
        if($response == true)
            return redirect('/login')->with('message', trans('app.new_password_set'));
        else
            return redirect('reset/'.$request->id.'/'.$request->token)->with('message', trans('app.enter_password_correctly'));
    }

}
