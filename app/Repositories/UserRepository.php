<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Profile;
use App\Models\Match;
use App\Models\Encounter;
use App\Models\Visitor;
use App\Models\UserInterests;
use App\Models\Settings;
use App\Models\EmailSettings;
use App\Models\Notifications;
use App\Models\NotificationSettings;
use App\Models\SuperPowerPackages;
use App\Models\UserSuperPowers;
use App\Models\UserSettings;
use App\Models\Transaction;
use App\Models\Photo;
use App\Models\UserSocialLogin;
use Validator;

use DB;
use App\Repositories\BlockUserRepository;

use App\Models\SuperpowerHistory;
use App\Components\Plugin;

use stdClass;

class UserRepository
{
    protected $blockUserRepo;
    
    public static $user_settings_cache = array();
    public function __construct(User $user, UserSettings $user_settings, UserSocialLogin $user_social_login, Photo $photo, EmailSettings $email_settings, UserSuperPowers $user_superpowers)
    {
        $this->blockUserRepo = app("App\Repositories\BlockUserRepository");
        $this->user = $user;
        $this->user_settings = $user_settings;
        $this->user_social_login = $user_social_login;
        $this->photo = $photo;
        $this->email_settings = $email_settings; 
        $this->user_superpowers = $user_superpowers;
    }

    public function socialAccountVerifications($userId)
    {
        $userSocialLogins = $this->user_social_login->where('userid', $userId)->select('src')->get();
        
        $socialVerifications = [
            "socialVerificationCount" => 0,
            "socialVerificationAccounts" => [
            ]
        ]; 

        foreach($userSocialLogins as $login) {
            $verified = Plugin::fire("{$login->src}_verification");
            
            if(isset($verified[0])) {
                $socialVerifications["socialVerificationCount"] += 1;
                $socialVerifications["socialVerificationAccounts"][$login->src] = $verified[0];
            }
        }

        return $socialVerifications;
    }


    public function set_user_setting ($user_id, $key, $value) {

        //$setting = $this->user_settings->where('userid', $user_id)->where('key', $key)->first();
        
        $setting = $this->user_settings->get_setting($user_id, $key);

        if ($setting) {
            $setting->value = $value;
        } else {
            $setting         = new UserSettings;
            $setting->userid = $user_id;
            $setting->key    = $key;
            $setting->value  = $value;
        }

        $setting->save();
        return $setting;
    }

    public function get_user_setting ($user_id, $key) {
       
        //$setting = $this->user_settings->where('userid', $user_id)->where('key', $key)->first();
        $setting = $this->user_settings->get_setting($user_id, $key);
        
        if ($setting) {
            return $setting->value;
        } else {
            return '';
        }
    }

    public function remove_user_setting ($user_id, $key) {
        
        $setting = $this->user_settings->where('userid', $user_id)->where('key', $key)->first();
        
        if ($setting) {
            $setting->forceDelete();
            return true;
        }
        return false;
    }

    public function getSocialAccounts($id)
    {
        return $this->user_social_login->where('userid','=', $id)->get();
    }

    public function getPhotosBySrc($user_id, $src)
    {
        return $this->photo->where('userid', $user_id)->where('photo_source', $src)->get();
    }

    public function getSocialLoginBySrc($id,$src)
    {
        return $this->user_social_login->where('src_id','=',$id)->where('src','=',$src)->first();
    }

    public function getEmailSettings($type)
    {
        return $this->email_settings->where('email_type','=',$type)->first();
    }


    public function insert_social_login($response_user, $user, $src)
    {
        $already_exists = $this->user_social_login->where('src',$src)->where('userid',$user->id)->first();
        if($already_exists)
        {
           $already_exists->src_id = $response_user->id;
           $already_exists->save();
        }
        else
        {
            $social = new UserSocialLogin;
            $social->userid = $user->id;
            $social->src = $src;
            $social->src_id = $response_user->id;
            $social->save();
        }
    }

    public function registerValidate ($request_data, $userId) {

        return Validator::make($request_data, [
            'username'               => 'required|email|unique:user,username,'.$userId,
            'gender_val'            => 'required',
            'dob'                   => 'required|date_format:d/m/Y|before:18 years ago',
            'lat'                   => 'required',
            'lng'                   => 'required',
            'city'                  => 'required',
            'country'               => 'required',
        ]);

    }

    public function formatErrors ($errors) {

        $messages = [];
        ($errors->has('username')) ? $messages['username'] = $errors->get('username')[0] : '';
        ($errors->has('gender_val')) ? $messages['gender'] = $errors->get('gender_val')[0] : '';
        ($errors->has('dob')) ? $messages['dob']           = $errors->get('dob')[0] : '';
        ($errors->has('lat')) ? $messages['lat']           = $errors->get('lat')[0] : '';
        ($errors->has('lng')) ? $messages['lng']           = $errors->get('lng')[0] : '';
        ($errors->has('city')) ? $messages['city']         = $errors->get('city')[0] : '';
        ($errors->has('country')) ? $messages['country']   = $errors->get('country')[0] : '';

        return $messages;
    }

    public function getAllUsers()
    {
        return $this->user->all();
    }
    
    public function getInvisibleSettings($id) {
        
        $invisible = array();
        
        $settings = $this->user_superpowers->where("user_id",$id)->first();
        if($settings) {
            
            $invisible["hide_visitors"] = $settings->invisible_mode;
            $invisible["hide_superpowers"] = $settings->hide_superpowers;
        } else {
            $invisible["hide_visitors"] = 0;
            $invisible["hide_superpowers"] = 0;
        }
        
        return $invisible;
    }

    public function getPrivacySettings($id) {
        
        $arr =array();
         $set_settings = array();
        $all_settings = array("online_status","show_distance");
        $settings = $this->user_settings->where("userid",$id)->get();
        foreach($settings as $setting)
        {
            $arr[$setting->key] = $setting->value;
            array_push($set_settings,$setting->key);
        }
        
        $not_set_setting = array_diff($all_settings,$set_settings);
        foreach($not_set_setting as $setting) {
            $arr[$setting] = 1;
        }
        return $arr;
        
    }

    // change mail 
    public function changeEmail($user,$email)
    {        
        $activation_code = str_random(60) . $email;
        $user->activate_token = $activation_code;
        $user->username = $email;
        $user->activate_user = "deactivated";
        $user->save();
        
        return $user;
    }

    // change password
    public function changePasswordMail($user)
    {
        $msg = array();
        $set = $this->email_settings->where('email_type','=','change_password')->first();
        if($set)
        {
            array_push($msg, $set->subject);
            array_push($msg,$this->mailBodyParser($set->content, $user));
        }
        return $msg;
    }

    public function mailBodyParser($body, $user)
    {
        $title = Settings::where('admin_key','=','website_title')->first();
        $symlink = array('@website_name' => $title->value,
                        '@name' => $user->name,
                        '@email' => $user->username,
                        '@link' => 'laravel5.gangcloud.com',
                        '@profilelink' => url('profile').'/'.$user->id,
                        '@activationlink' => url('sample/activate/') . '/'. $user->id . '/'. $user->activate_token);

        foreach ($symlink as $key => $value) 
        {
            $body = str_replace($key, $value, $body);   
        }

        return $body;
    }

    // this function will return user object based on id passed
    public function getUserById($id)
    {
       return $this->user->find($id);
    }

    public function getUserByEmail($username)
    {
       return $this->user->where('username', '=', $username)->first();
    }
    
    public function getTitle()
    {
        $title = Settings::where('admin_key','=','website_title')->first();
        return isset($title) ? $title->value : '';
    }


    public function createAndSaveAccessToken ($user) {
        $token = \Hash::make($user->username);
        $user->access_token = $token;
        $user->save();
        return $token;
    }


    /* this method checks users data incomplete returns true if incomplete data */
    public  function checkUserDataIncomplete ($user) {

        if ($user->dob == '0000-00-00') return true;
        if ($user->city == '' || $user->country = '' || $user->latitude == '' || $user->longitude == '') return true;
        if ($user->name == '') return true;
        return false;

    }


    public function isOnline($user)
    {
        $to_time   = strtotime( date('Y-m-d H:i:s') );
        $from_time = strtotime($user->last_request);
        $minute    = round(abs($to_time - $from_time) / 60);
        
        return ($minute <= 5) ? true : false;
    }


}
