<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Components\Plugin;
use Socialite;
use App\Repositories\RegisterRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\UserRepository;
use App\Repositories\FacebookRepository;
use App\Repositories\MaxmindGEOIPRepository;
use App\Repositories\Admin\UtilityRepository;
use App\Repositories\Admin\GeneralManageRepository;
use Illuminate\Http\Request;
use Auth;
use Facebook\Facebook;
use stdClass;
use App\Models\Settings;


class FacebookPluginController extends Controller
{
    
    public function __construct(
        ProfileRepository $profileRepo, 
        UserRepository $userRepo, 
        FacebookRepository $facebookRepo, 
        GeneralManageRepository $generalRepo, 
        RegisterRepository $registerRepo,
        MaxmindGEOIPRepository $maxmindGEOIPRepo,
        Settings $settings
    )
    {
        $this->profileRepo      = $profileRepo;
        $this->userRepo         = $userRepo;
        $this->facebookRepo     = $facebookRepo;
        $this->generalRepo      = $generalRepo;
        $this->registerRepo     = $registerRepo;
        $this->maxmindGEOIPRepo = $maxmindGEOIPRepo;
        $this->settings         = $settings;
        $this->socialLoginRepo  = app('App\Repositories\SocialLoginRepository');
    }

   	//facebook login funcitons route (/facebook)
    public function redirect()
    {   
        return Socialite::driver('facebook')->fields([
            'name','email', 'gender', 'birthday','hometown','friends','location', 'likes'
        ])->scopes([
            'email', 'user_birthday','public_profile','user_friends','user_hometown','user_location', 'user_likes'
        ])->redirect();
    }

    public function redirect_facebook_photos () {

        session_start();

        $app_id     = UtilityRepository::get_setting('fb_appId');
        $app_secret = UtilityRepository::get_setting('fb_secretkey');
        
        $fb = new Facebook([

          'app_id'     => $app_id,
          'app_secret' => $app_secret,
          
        ]);


        $helper = $fb->getRedirectLoginHelper();
 
        $permissions = ['email', 'user_posts','user_friends','user_photos']; // optional
        $callback    = url("facebook/import/photos");
        $loginUrl    = $helper->getLoginUrl($callback, $permissions);

        echo '<a href="' . $loginUrl . '">'. trans('app.login_fb').'</a>';
       
    }




    public function import_photos (Request $request) {

        session_start();

        $app_id     = UtilityRepository::get_setting('fb_appId');
        $app_secret = UtilityRepository::get_setting('fb_secretkey');

        $fb_pic_ids = $this->getFacebookPhotoIds (Auth::user()->id);

        $fb = new Facebook([

          'app_id'     => $app_id,
          'app_secret' => $app_secret,
          
        ]);

        $helper = $fb->getRedirectLoginHelper();
        
        $accessToken = $helper->getAccessToken();

        $res = $fb->get('/me/albums', $accessToken);
        $res = $res->getDecodedBody();

        $response = $fb->get('/me', $accessToken);
        $response = $response->getDecodedBody();
        $social = $this->userRepo->getSocialLoginBySrc($response['id'],'facebook');   
        
        if(!($social))
        {
            $obj = new stdClass;
            $obj->id = $response['id'];
            $this->userRepo->insert_social_login($obj,Auth::user(),'facebook');
        }
        $profile_pic_id = '';
        foreach($res['data'] as $arr)
        {
            if($arr['name'] == 'Profile Pictures')
                $profile_pic_id = $arr['id'];        
        }
        $res = $fb->get($profile_pic_id.'/photos', $accessToken);
        
        $res = $res->getDecodedBody();

        foreach($res['data'] as $image)
        {

            if ( in_array($image['id'], $fb_pic_ids) ) {

                continue;
            }

            $pic = '';
            $pics = $fb->get($image['id'].'?fields=images', $accessToken);
            $pics = $pics->getDecodedBody();
            $fileName = uniqid(Auth::user()->id . '_') . '_' . rand(10000000, 99999999) . '.jpg';
            foreach($pics['images'] as $pic)
            {
                $this->profileRepo->save_resize_photo($pic['source'],$fileName);
                break;
            }
            
            // Storage::put('public/uploads/others/'.$fileName, $pic);
            $this->facebookRepo->insertPhoto(Auth::user()->id,$image['id'],$fileName);
            
        }

        return redirect('/home');
    
    }


    public function downloadImportedPhotos (Request $req) {

        $imported_photos = $req->imported_photos;
        $fb_user_id = $req->fb_user_id;

        if ($imported_photos == '' || $fb_user_id == '') {
            return response()->json(['status' => 'error']);
        }

        $imported_photos_array = json_decode($imported_photos, true);
        $auth_user = Auth::user();
        
        $social = $this->userRepo->getSocialLoginBySrc($fb_user_id, 'facebook');   
        
        if(!$social) {
            $obj = new stdClass;
            $obj->id = $fb_user_id;
            $this->userRepo->insert_social_login($obj, $auth_user, 'facebook');
        }

        $user_fb_photo_ids = $this->getFacebookPhotoIds($auth_user->id);
        foreach ($imported_photos_array as $photo) {
            
            if (!in_array($photo['photo_id'], $user_fb_photo_ids)
                && $photo['photo_source'] != '') {

                try {
                    $image_file_name = UtilityRepository::generate_image_filename('fb_photo_', '.jpg');
                    $this->profileRepo->save_resize_photo($photo['photo_source'], $image_file_name);
                    $this->facebookRepo->insertPhoto($auth_user->id, $photo['photo_id'], $image_file_name);
                } catch (\Exception $e) {
                    break;
                }                    
            }
        }

        return response()->json(['status' => 'success']);
    }





    public function getFacebookPhotoIds ($user_id) {

        $photos = $this->userRepo->getPhotosBySrc($user_id,'facebook');

        $ids = [];

        foreach ($photos as $photo) {

            array_push($ids, $photo->source_photo_id);
        }

        return $ids;
    }


    public function scopeFields()
    {
        return ['name','email', 'gender', 'birthday','hometown','friends','location', 'likes'];
    }


    public function facebookInstance()
    {

        if(isset($this->facebookInstance)) {
            return $this->facebookInstance;
        }


        $facebookInstance = new Facebook([
          'app_id'     => $this->settings->get('fb_appId'),
          'app_secret' => $this->settings->get('fb_secretkey'),
        ]);

        return $this->facebookInstance = $facebookInstance;
    }

    public function getUserLocation($socialInstance)
    {
        if (isset($socialInstance->user['location']['id'])) {
            $res = $this->facebookInstance()->get(
                $socialInstance->user['location']['id'].'?fields=id,location', 
                $socialInstance->token
            );
            return $res->getDecodedBody();
        }

        return '';
    }



    public function getProfilePictureData($socialInstance)
    {
        $json = file_get_contents($socialInstance->avatar_original.'&redirect=false');
        return json_decode($json);
    }



    public function handleCallback(Request $request) 
    {

        if($request->error == 'access_denied' || $request->error_code == '200') {
            return redirect('/login');
        }

        $user = Socialite::driver('facebook')->fields($this->scopeFields())->user();    
        
        
        $this->socialLoginRepo->setSocialmedia('facebook') //mandatory
                            ->setUser(Auth::user()) //mandatory
                            ->setSocialMediaUser($user); //mandatory


        /* if auth user exists then social account linked return true after inserting into sociallogin table*/
        if($this->socialLoginRepo->linkSocialAccount()) {
            return redirect('encounter');
        }




        $res = $this->getUserLocation($user);
        $profilePictureData = $this->getProfilePictureData($user);
        

        $this->socialLoginRepo->setName($user->getName())
                                ->setUsername($user->getEmail())
                                ->setGender(isset($user->user['gender']) ? $user->user['gender'] : '')
                                ->setDOB(isset($user->user['birthday']) 
                                    ? \DateTime::createFromFormat('m/d/Y', $user->user['birthday'])
                                    : null
                                )
                                ->setVerify()
                                ->setActivateUser('activated')
                                ->setPasswordToken()
                                ->setCity(isset($res['location']['city']) ? $res['location']['city'] : '')
                                ->setCountry(isset($res['location']['country']) ? $res['location']['country'] : '')
                                ->setLatitude(isset($res['location']['latitude']) ? $res['location']['latitude'] : '')
                                ->setLongitude(isset($res['location']['longitude']) ? $res['location']['longitude'] : '')
                                ->setSocialDefultPicture(!($profilePictureData->data->is_silhouette == false), $user->avatar_original);
         

        $response = $this->socialLoginRepo->doLogin();
    
        if($response['user']) {

            if(isset($user->user['friends'])) {
                $artisan_path = base_path('artisan');
                $php_path = $this->settings->get('php_path');
                $command_string = "$php_path $artisan_path facebook_friends_save_command ".$response['user']->id." ".trim($user->getID()) ." ".$user->token."> /dev/null &";
                exec($command_string);
            }


            if(isset($user->user['likes']['data'])) {
                $artisan_path = base_path('artisan');
                $php_path = $this->settings->get('php_path'); 
                $command_string = "$php_path $artisan_path facebook_likes_as_interest ".$response['user']->id." ".trim($user->getID()) ." ".$user->token."> /dev/null &";
                exec($command_string);
            }


        }



        if($response['login']) {
            return redirect('encounter')->with('data_incomplete', $response['data_incomplete']);
        }

        return redirect('/login');

    }







    //route (/facebook/callback)
    // public function handleCallback(Request $request) {

    //     if($request->error == 'access_denied' || $request->error_code == '200')
    //         return redirect('/login');
        
    //      $res  = null;
    //      $user = Socialite::driver('facebook')->fields([
    //         'name','email', 'gender', 'birthday','hometown','friends','location', 'likes'
    //      ])->user();    

    //     $fb = new Facebook([
    //       'app_id'     => UtilityRepository::get_setting('fb_appId'),
    //       'app_secret' => UtilityRepository::get_setting('fb_secretkey'),
          
    //     ]);


    //     if (isset($user->user['location']['id'])) {
    //         $res = $fb->get($user->user['location']['id'].'?fields=id,location', $user->token);
    //         $res = $res->getDecodedBody();
    //     }

    //     $json = file_get_contents($user->avatar_original.'&redirect=false');
    //     $json = json_decode($json);

    //     //if user already logged in then verify his social links
    //     $social_account_linked = $this->userRepo->getSocialLoginBySrc($user->id, 'facebook');
    //     $auth_user = Auth::user();
    //     if ($auth_user && !$social_account_linked) {
    //         $this->userRepo->insert_social_login ($user, $auth_user,'facebook');
    //         return back();
    //     }

    //     $user_email    = trim($user->getEmail());
    //     if ($user_email == null) {
    //         if ($social_account_linked) {
    //             $registerdUser = $social_account_linked->user;
    //         } else {
    //             $registerdUser = null;
    //         }
    //     } else {
    //         $registerdUser = $this->userRepo->getUserByEmail($user_email);
    //     }

    //     $getDefaultGenderPictures = $this->getDefaultGenderPictures();



    //     //user is registered so log in user
    //     if ($registerdUser != null) {
         
    //         /* set location info with maxmind */
    //         $this->maxmindGEOIPRepo->updateUserLocation($registerdUser);


    //         $data_incomplete = (!isset($registerdUser->city) 
    //                             || !isset($registerdUser->country) 
    //                             || !isset($registerdUser->latitude) 
    //                             || !isset($registerdUser->longitude))
    //                             ? true : false;
    //         if ($registerdUser->name == null) $data_incomplete = true;

    //         if((in_array($registerdUser->profile_pic_url, $getDefaultGenderPictures)) && $json->data->is_silhouette == false) {
    //             $this->replaceDefaultProfilPicture($user->avatar_original, $registerdUser);
    //         }

    //         $status = $this->userRepo->get_user_setting($registerdUser->id, 'activation_status');
    //         if ($status != '') {
    //            $registerdUser->activate_user = 'activated';
    //            $registerdUser->save();
    //            $this->userRepo->remove_user_setting($registerdUser->id, 'activation_status');
    //         }

    //         if ( $registerdUser->activate_user == 'activated') {
                
    //             if(!$social_account_linked) {
    //                 $this->userRepo->insert_social_login($user,$registerdUser,'facebook');
    //             }
                
    //             $registerdUser->createAndSaveSlugName();
    //             Auth::login($registerdUser,false);

    //             /*save facebook friends command */
    //             if(isset($user->user['friends'])) {
    //                 $artisan_path = base_path('artisan');
    //                 $php_path = $this->settings->get('php_path');
    //                 $command_string = "$php_path $artisan_path facebook_friends_save_command ".$registerdUser->id." ".trim($user->getID()) ." ".$user->token."> /dev/null &";
    //                 exec($command_string);
    //             }

    //             return redirect('encounter')->with('data_incomplete', $data_incomplete);
    //         }
            
    //         return redirect('/login');
    //     }
    //     else
    //     {
    //         //not register so register user and login user
    //         $gndr = (isset($user->user['gender'])) ? $user->user['gender'] : $this->getRandomGender();
    //         $data_incomplete       = false;
    //         $arr['name']           = trim($user->getName());
    //         $arr['username']       = trim($user->getEmail());
    //         $arr['verified']       = 'verified';
    //         $arr['activate_user']  = 'activated';
    //         $arr['register_from']  = 'facebook';     
    //         $arr["password_token"] = str_random(60).$arr['username'];       
    //         $arr['gender']         = $gndr;

            
    //         if(!$this->maxmindGEOIPRepo->enabled()) {

    //             if (isset($res)) {
    //                 $arr['city']      = $res['location']['city'];
    //                 $arr['latitude']  = $res['location']['latitude'];
    //                 $arr['longitude'] = $res['location']['longitude'];
    //                 $arr['country']   = $res['location']['country'];
                
    //             } else {
    //                 $data_incomplete = true;
    //             }
    //         }

    //         if ($arr['name'] == null) $data_incomplete = true;
           
    //         if(isset($user->user['birthday'])) {
    //             $arr['dob'] = \DateTime::createFromFormat('m/d/Y', $user->user['birthday']);
    //         } else {
    //             $data_incomplete = true;
    //         }
        
    //         //calling register funciton of RegisterRepository
    //         $created_user = $this->registerRepo->register($arr);
    //         $this->maxmindGEOIPRepo->updateUserLocation($created_user);
            
    //         if($json->data->is_silhouette == false) {
    //             $this->replaceDefaultProfilPicture($user->avatar_original, $created_user);
    //         } else {
    //             $fileName = UtilityRepository::get_setting('default_'.$gndr);
    //             $created_user->profile_pic_url = $fileName;
    //             $created_user->save();
    //         }

    //         $this->userRepo->insert_social_login($user, $created_user, 'facebook');

    //         $created_user->createAndSaveSlugName();
    //         Auth::login($created_user,false);

    //         if(isset($user->user['likes']['data'])) {
    //             $artisan_path = base_path('artisan');
    //             $php_path = $this->settings->get('php_path');
    //             $command_string = "$php_path $artisan_path facebook_likes_as_interest ".$created_user->id." ".trim($user->getID()) ." ".$user->token."> /dev/null &";
    //             exec($command_string);
    //         }



    //         $gender['user_id'] = $created_user->id;
    //         $gender['value'] = $arr['gender'];
            
    //         $this->profileRepo->saveGender($gender);
            
            
    //         /*save facebook friends command */
    //         if(isset($user->user['friends'])) {
    //             $artisan_path = base_path('artisan');
    //             $php_path = $this->settings->get('php_path');
    //             $command_string = "$php_path $artisan_path facebook_friends_save_command ".$created_user->id." ".trim($user->getID()) ." ".$user->token."> /dev/null &";
    //             exec($command_string);
    //         }

            
    //         if ($created_user->latitude != null && $created_user->longitude != null)
    //             Plugin::fire('account_create', $created_user);

    //         return redirect('encounter')->with('data_incomplete', $data_incomplete);
    //     }
    // }


    public function replaceDefaultProfilPicture ($image_url, $user) {

        $fileName = uniqid($user->id . '_') . '_' . rand(10000000, 99999999) . '.jpg';
        app("App\Repositories\ProfileRepository")->save_resize_photo($image_url,$fileName);
        $this->facebookRepo->insertPhoto($user->id, 'avatar', $fileName);

        $user->profile_pic_url = $fileName;
        $user->save();
    }


    protected static $gender_field = null;
    public function getDefaultGenderPictures () {
        self::$gender_field = app("App\Models\Fields")->getGenderField();
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



    //this funciton shows facebook plugin login credential settings
    // Route:: /admin/pluginsettings/facebook
    public function showSettings () {
        
        $fb_appId     = UtilityRepository::get_setting('fb_appId');
        $fb_secretKey = UtilityRepository::get_setting('fb_secretkey');
        $fb_import_enabled = UtilityRepository::get_setting('fb_photos_import');

        return Plugin::view('FacebookPlugin/settings', ['appid' => $fb_appId, 'secretkey' => $fb_secretKey,'fb_import_enabled' => $fb_import_enabled]);

    }




    //this funciton saves/updates facebook pluign login credential settings
    //Route:: /admin/pluginsettings/facebook
    public function saveSettings (Request $request) {
        
        try {

            $mode = ($request->photo_import_mode == 'on') ? '1' : '0'; 
            UtilityRepository::set_setting('fb_appId', $request->appid);
            UtilityRepository::set_setting('fb_secretkey', $request->secretkey);
            UtilityRepository::set_setting('fb_photos_import', $mode);
            return response()->json(['status' => 'success', 'message' => trans('app.fb_set_save')]);

        } catch (\Exception $e) {

            return response()->json(['status' => 'error', 'message' => trans('app.fb_set_fail')]);
        }
            

    }
    
}
