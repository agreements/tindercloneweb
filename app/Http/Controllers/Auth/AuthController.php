<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

use App\Models\User;
use Auth;
use Validator;
use App;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

use App\Repositories\RegisterRepository;
use App\Repositories\UserRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\Admin\UtilityRepository;
use App\Repositories\MaxmindGEOIPRepository;

use \Illuminate\Database\QueryException;
use App\Models\Settings;
///facebook login class import
use Socialite;
use URL;
use App\Components\Theme;
use App\Components\Plugin;
use Cookie;
use Hash;

use Session;

class AuthController extends Controller
{

    //if login successful then redirect to /home route
    protected $redirectPath = '/home';


    //if login failed then redirect to /login route
    protected $loginPath = '/login';

    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    
    //registering RegiserRepository 
    protected $registerRepo;
    protected $userRepo;
    protected $profileRepo;

    public function __construct(
        RegisterRepository $registerRepo, 
        UserRepository $userRepo, 
        ProfileRepository $profileRepo,
        MaxmindGEOIPRepository $maxmindGEOIPRepo)
    {
        $this->registerRepo = $registerRepo;
        $this->userRepo = $userRepo;
        $this->profileRepo = $profileRepo;
        $this->maxmindGEOIPRepo = $maxmindGEOIPRepo;

        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }



    public function getRegister (Request $request) {
        
        $sections = $this->profileRepo->get_fieldsections();

        return Theme::view('auth.registration', ['sections' => $sections]);
    }



    //this method will be called when user request for registering into applicaiton inserting all details
    public function postRegister (Request $request) {
        
        //creating validator for login requests
        $validator = $this->registerValidate($request->all());
        $errors    = $this->formatErrors($validator->errors());

        //if input validation fails then response validation failed
        if ($validator->fails()) {
            
            return response()->json([
                'status' => 'error',
                'errors' => $errors
            ]);

        }
   
        /*insert user information to user table 
        and also create one row in profile table with same userid */
        try {

            // saving custom fields    
            $sections = $this->profileRepo->get_fieldsections();
            $user_fields = [];
            foreach($sections as $section)
            {
                foreach($section->fields as $field)
                {
                    if($field->on_registration == 'yes')
                    {
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


       
            $arr                    = [];


            $arr['username']        = $request->username;
            $arr['password']        = Hash::make($request->password);
            $arr['gender']          = $request->gender_val;
            $arr['dob']             = \DateTime::createFromFormat('d/m/Y', $request->dob);
            $arr['name']            = $request->name;
            $arr['activate_user']   = "activated";
            $arr['verified']        = "unverified";
            
            $arr['city']            = $request->city;
            $arr['latitude']        = $request->lat;
            $arr['longitude']       = $request->lng;
            $arr['country']         = $request->country;

            $arr = $this->maxmindGEOIPRepo->setCityCountryLatitudeLongitude($arr);

            // $arr['hereto']          = $request->hereto;
            $arr['activate_token']  = str_random(60) . $request->username;
            $arr['register_from']   = Settings::_get('website_title');
            $arr['profile_pic_url'] = UtilityRepository::get_setting('default_'.$request->gender_val);      
            
            $user = $this->registerRepo->register($arr); 
            
            $user->createAndSaveSlugName();


            $this->profileRepo->saveUserFields($user->id, $user_fields);

            $gender['user_id'] = $user->id;
            $gender['value'] = $request->gender_val;
            $this->profileRepo->saveGender($gender);            

            //for bot create
            Plugin::fire('account_create', $user);
            
            //sending registratin mail
            $this->register_after_mail_send_event();
            $this->sendRegistrationMail ($user);

            $user = User::find($user->id);
            $email_verify_required = ($user->activate_user == 'activated')  ? 0 : 1;

            return response()->json(['status' => 'success', 'email_verify_required' => $email_verify_required]);

        } catch (QueryException $e) {

            if( isset($e) ) {

                return response()->json(['status' => 'error', 'errors' => [$e->getMessage()] ]);

            }
                
        } catch (\Exception $e) {


            return response()->json(['status' => 'error', 'errors' => [$e->getMessage()] ]);

        }

    } // ene of registration code


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


    
    //this method format validatin erros as per fields
    public function formatErrors ($errors) {

        $messages = [];

        ($errors->has('name')) ? $messages['name'] = $errors->get('name')[0] : '';
        
        
        ($errors->has('dob')) ? $messages['dob']             = $errors->get('dob')[0] : '';
        ($errors->has('username')) ? $messages['username']   = $errors->get('username')[0] : '';
        ($errors->has('lat')) ? $messages['lat']             = $errors->get('lat')[0] : '';
        ($errors->has('lng')) ? $messages['lng']             = $errors->get('lng')[0] : '';
        ($errors->has('city')) ? $messages['city']           = $errors->get('city')[0] : '';
        ($errors->has('country')) ? $messages['country']     = $errors->get('country')[0] : '';
        
        ($errors->has('password')) ? $messages['password']   = $errors->get('password')[0] : '';

        return $messages;
    }


    public function registerValidate ($request_data) {

        $validationParameters = [
            'name'             => 'required|max:100',
            'dob'                   => 'required|date_format:d/m/Y|before:18 years ago',
            'username'              => 'required|email|max:100|unique:user,username',
            'lat'                   => 'required',
            'lng'                   => 'required',
            'city'                  => 'required',
            'country'               => 'required',
            'password'              => 'required|min:8|max:100|confirmed',
            'password_confirmation' => 'required|min:8|max:100',
        ];

        $validationParameters = $this->maxmindGEOIPRepo->removeRegistrationValidatorParamters($validationParameters);
        
        return Validator::make($request_data, $validationParameters);
    }


















    //this method will be called when user requests for login view
    public function getLogin (Request $request) {
        
        return Theme::view('auth.signin');
    }



    //this method will be called when user request for logging into applicaiton by credentials
    public function postLogin (Request $request) {

        //creating validator for login requests
        $validator = Validator::make($request->all() ,[
           
            'username' => 'required|email|max:100',
            'password' => 'required|min:8',
        ]);

       
        //if input validation fails then redirect to login view ageain
        if ($validator->fails()) {

            $messages = $validator->errors()->all();
                
            session(['message' => $messages[0]]);
            return redirect('/login');
        }
        

        // $auto_browser_geolocation = UtilityRepository::get_setting('auto_browser_geolocation') == 'true' ? true : false; 
        // if($auto_browser_geolocation && (!$request->latitude ||!$request->longitude ||!$request->city ||!$request->country)) {
        //     session(['message' => trans('app.accept_browser_location_request')]);
        //     return redirect('/login');
        // }







        $remember_me = $request->remember_me == 'on' ? true : false;


        //attempt for authenticate user
        if (Auth::attempt( array('username' => $request->username, 'password' => $request->password), $remember_me)) {

		    $user = Auth::user();


            // if($auto_browser_geolocation) {
            //     $user->city = $request->city;
            //     $user->country = $request->country;
            //     $user->latitude = $request->latitude;
            //     $user->longitude = $request->longitude;
            //     $profile = $user->profile;
            //     $profile->latitude = $request->latitude;
            //     $profile->longitude = $request->longitude;
            //     $user->save();
            //     $profile->save();
            // }



            $this->maxmindGEOIPRepo->updateUserLocation($user);
            $user->createAndSaveSlugName();
            $status = $this->userRepo->get_user_setting($user->id, 'activation_status');
            if ($status != '') {
                $user->activate_user = 'activated';
                $this->userRepo->remove_user_setting($user->id, 'activation_status');
                $user->save();
            }


            $activate_user = $user->activate_user;
            
            if ($activate_user == 'activated') {

                if ($this->userRepo->checkUserDataIncomplete($user))
                    return redirect()->intended('/encounter')->with('data_incomplete', true);
                else
                    return redirect()->intended('/encounter');

            } else {

                Auth::logout();
                
                session(['message' => trans('app.account_not_activated')]);
                return redirect('/login');
            }

        }

        session(['message' => trans('app.login_failed')]);
        return redirect('/login');

    }


    //this function is used for log out user
    public function getLogout() {

		//$rememberMeCookie = Auth::getRecallerName();
        Auth::logout();
        
        //Session::flush();
		session()->regenerate();
		
		//$cookie = Cookie::forget($rememberMeCookie);
		return redirect('/');//->withCookie($cookie);
        
        
    }

}
