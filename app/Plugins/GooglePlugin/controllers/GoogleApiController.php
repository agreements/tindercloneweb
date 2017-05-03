<?php

namespace App\Http\Controllers;
 
//include google api library
// require_once base_path().'/vendor/google/apiclient/src/Google/autoload.php';
use App\Http\Controllers\Controller;
use App\Components\Plugin;
use App\Models\Settings;
use Socialite;
use App\Repositories\RegisterRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\UserRepository;
use App\Repositories\Admin\UtilityRepository;
use App\Models\User;
use App\Models\Lists;
use Illuminate\Http\Request;
use Auth;
use Storage;
use App\Models\Photo;
use stdCLass;
use OAuth;
use curl;

class GoogleApiController extends Controller {

    protected $profileRepo;
    protected $userRepo;
    protected $googleRepo;
    protected $generalRepo;
    
    public function __construct () {
        $this->profileRepo  = app("App\Repositories\ProfileRepository");
        $this->userRepo     = app("App\Repositories\UserRepository");
        $this->registerRepo = app('App\Repositories\RegisterRepository');
        $this->googleRepo   = app('App\Repositories\GoogleRepository');
        $this->generalRepo  = app('App\Repositories\Admin\GeneralManageRepository');
    }

   	public function getAppID () {
        return response()->json([
            "status" => "success",
            "success_data" => [
                "facebook_app_id" => UtilityRepository::get_setting('google_appId'),
                "success_text" => "Google Api Key retrived successfully."
            ]
        ]);
    }


    public function google (Request $req) {

        $google_id      = $req->google_id;
        $user_email     = $req->email;
        $country        = $req->country;
        $city           = $req->city;
        $lat            = $req->latitude;
        $lng            = $req->longitude;
        $avatar_url     = $req->avatar_url;
        $gender         = $req->gender;
        $name           = $req->name;
        $birthday       = $req->birthday;
        $is_default_pic = $req->is_default_picture;
       
        $errors = [];
        $error = false;
        if (!$google_id) {
            $errors['google_id'] = 'The google_id field is required.';
            $error = true;
        }
        if (!$avatar_url) {
            $errors['avatar_url'] = 'The avatar_url field is required.';
            $error = true;
        } 
        if (!$user_email) {
            $errors['email'] = 'The email field is required.';
            $error = true;
        } 
        if (!in_array($is_default_pic, ["false", "true"])) {
            $errors['is_default_picture'] = 'The is_default_picture field is required.';
            $error = true;
        }

        if ($error) {
            return response()->json(["status" => "error", "error_data" => $errors]);
        }

        $social_login = $this->userRepo->getSocialLoginBySrc($google_id, 'google');
        $registerdUser = $this->userRepo->getUserByEmail($user_email);

        $getDefaultGenderPictures = $this->getDefaultGenderPictures();

        if ($registerdUser != null) {

            $data_incomplete = (!isset($registerdUser->city) 
                                || !isset($registerdUser->country) 
                                || !isset($registerdUser->latitude) 
                                || !isset($registerdUser->longitude)
                                ) ? true : false;

            if ($registerdUser->name == null) $data_incomplete = true;

            if (in_array($registerdUser->profile_pic_url, $getDefaultGenderPictures) 
                && $is_default_pic == 'false') {
                $this->replaceDefaultProfilPicture($avatar_url, $registerdUser);
            }

            $status = $this->userRepo->get_user_setting($registerdUser->id, 'activation_status');
            if ($status != '') {
               $registerdUser->activate_user = 'activated';
               $registerdUser->save();
               $this->userRepo->remove_user_setting($registerdUser->id, 'activation_status');
            }

            if ( $registerdUser->activate_user == 'activated') {
                if($social_login == null) {
                    $obj = new \stdClass;
                    $obj->id = $google_id;
                    $this->userRepo->insert_social_login($obj, $registerdUser, 'google');
                }

                $token = $this->userRepo->createAndSaveAccessToken($registerdUser);
                $registerdUser->createAndSaveSlugName();
                return response()->json([
                    "status" => "success", 
                    "success_data" => [
                        "user_id"                => $registerdUser->id, 
                        "name"                   => $registerdUser->name, 
                        "username"               => $registerdUser->username,
                        "access_token"           => $registerdUser->access_token,
                        "last_request_timestamp" => $registerdUser->last_request,
                        "data_incomplete"        => ($data_incomplete) ? 'true' : 'false',
                        "profile_pictures" => [
                            "thumbnail" => $registerdUser->thumbnail_pic_url(),
                            "encounter" => $registerdUser->encounter_pic_url(),
                            "other"     => $registerdUser->others_pic_url(),
                            "original"  => $registerdUser->profile_pic_url(),
                        ],
                        "credits"                => $registerdUser->credits->balance,
                        "success_text"           => "User logged in successfully."
                    ]
                ]);
            } else {
                $errors['error_text'] = 'User account not activated';
                return response()->json(["status" => "error", "error_data" => $errors]);
            }
        
        } else {

            //not register so register user and login user
            $gndr = ($gender) ? $gender : $this->getRandomGender();
            $data_incomplete       = true;
            $arr['name']           = (isset($name)) ? $name : '';
            $arr['username']       = $user_email;
            $arr['verified']       = 'verified';
            $arr['activate_user']  = 'activated';
            $arr['register_from']  = 'google';            
            $arr['password_token'] = str_random(60).$arr['username'];
            $arr['gender']         = $gndr;
            
            
            if (isset($city) && isset($country) && isset($lat) && isset($lng)) {
                $arr['city']      = $city;
                $arr['latitude']  = $lat;
                $arr['longitude'] = $lng;
                $arr['country']   = $country;
            
            } else {
                $data_incomplete = true;
            }

            if ($arr['name'] == null) $data_incomplete = true;

            if(isset($birthday)) {
                $arr['dob'] = \DateTime::createFromFormat('m/d/Y', $birthday);
            } else {
                $data_incomplete = true;
            }

            $created_user = $this->registerRepo->register($arr);

            if ($is_default_pic == 'false') {
                $this->replaceDefaultProfilPicture($avatar_url, $created_user);
            } else {
                $created_user->profile_pic_url = UtilityRepository::get_setting('default_'.$gndr);
            }
            $created_user->save();

            $gender_array = [];
            $gender_array['user_id'] = $created_user->id;
            $gender_array['value']   = $arr['gender'];
            $this->profileRepo->saveGender($gender);

            $obj = new \stdClass;
            $obj->id = $google_id;
            $this->userRepo->insert_social_login($obj, $created_user, 'google');

            Plugin::fire('account_create', $created_user);
            $token = $this->userRepo->createAndSaveAccessToken($created_user);
            $created_user->createAndSaveSlugName();
            return response()->json([
                "status" => "success", 
                "success_data" => [
                    "user_id"                => $created_user->id, 
                    "name"                   => $created_user->name, 
                    "username"               => $created_user->username,
                    "access_token"           => $created_user->access_token,
                    "last_request_timestamp" => $created_user->last_request,
                    "data_incomplete"        => ($data_incomplete) ? 'true' : 'false',
                    "profile_pictures" => [
                        "thumbnail" => $created_user->thumbnail_pic_url(),
                        "encounter" => $created_user->encounter_pic_url(),
                        "other"     => $created_user->others_pic_url(),
                        "original"  => $created_user->profile_pic_url(),
                    ],
                    "credits"                => $created_user->credits->balance,
                    "success_text"           => "User registered successfully."
                ]
            ]);
        }

    }

    public function replaceDefaultProfilPicture ($image_url, $user) {
        $size     = explode('?', $image_url);
        $ext      = explode('.', $size[0]);
        $fileName = uniqid($user->id . '_') . '_' . rand(10000000, 99999999) . '.' .$ext[count($ext) -1];
        $image    = $size[0].'?sz=550';
        
        $user->profile_pic_url = $fileName;
        $user->save();
        $this->profileRepo->save_resize_photo($image, $fileName);
        $this->googleRepo->insertPhoto($user->id, 'avatar', $fileName);
    }

    protected static $gender_field = null;
    public function getDefaultGenderPictures () {
        self::$gender_field = $this->generalRepo->getGenderField();
        $arr   = [];
        foreach (self::$gender_field->field_options as $option) {
            $picture = UtilityRepository::get_setting('default_'.$option->code);
            if($picture){ array_push($arr, $picture); }
        }
        return $arr;
    }

    public function getRandomGender() {
        foreach (self::$gender_field->field_options as $option) {
            return $option->code;
        }
    }

    public function importContacts(Request $request)
    {

	    $user = Socialite::driver('google')->user();
        $registerdUser = $this->userRepo->getUserByEmail($user->getEmail());
        // $image = file_get_contents($user->avatar);
        // $this->profileRepo->save_resize_photo($image,$fileName);
        // dd('profile');
        //user is registered so log in user
        if(Auth::user())
        {
            $social = $this->userRepo->getSocialLoginBySrc($user->id,'google');   
            if(!($social))
                $this->userRepo->insert_social_login($user,Auth::user(),'google');
            return back();
        }


    }

    function curl($url, $post = "") {
         $curl = curl_init();
         $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
         curl_setopt($curl, CURLOPT_URL, $url);
         //The URL to fetch. This can also be set when initializing a session with curl_init().
         curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
         //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
         curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
         //The number of seconds to wait while trying to connect.
         if ($post != "") {
         curl_setopt($curl, CURLOPT_POST, 5);
         curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
         }
         curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
         //The contents of the "User-Agent: " header to be used in a HTTP request.
         curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
         //To follow any "Location: " header that the server sends as part of the HTTP header.
         curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE);
         //To automatically set the Referer: field in requests where it follows a Location: redirect.
         curl_setopt($curl, CURLOPT_TIMEOUT, 10);
         //The maximum number of seconds to allow cURL functions to execute.
         curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
         //To stop cURL from verifying the peer's certificate.
         $contents = curl_exec($curl);
         curl_close($curl);
         return $contents;
        }

}
