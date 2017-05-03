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
use App\Repositories\GoogleRepository;
use App\Repositories\Admin\UtilityRepository;
use App\Repositories\Admin\GeneralManageRepository;
use App\Repositories\MaxmindGEOIPRepository;
use App\Models\User;
use App\Models\Lists;
use Illuminate\Http\Request;
use Auth;
use Storage;
use App\Models\Photo;
use stdCLass;
use OAuth;
use curl;


class GooglePluginController extends Controller
{
    
    public function __construct(
        ProfileRepository $profileRepo, 
        UserRepository $userRepo, 
        GoogleRepository $googleRepo, 
        GeneralManageRepository $generalRepo,
        MaxmindGEOIPRepository $maxmindGEOIPRepo
    )
    {
        $this->profileRepo      = $profileRepo;
        $this->userRepo         = $userRepo;
        $this->googleRepo       = $googleRepo;
        $this->generalRepo      = $generalRepo;
        $this->registerRepo     = app('App\Repositories\RegisterRepository');
        $this->maxmindGEOIPRepo = $maxmindGEOIPRepo;
        $this->socialLoginRepo  = app('App\Repositories\SocialLoginRepository');
    }

   	//facebook login funcitons route (/google)
    public function send_emails(Request $request)
    {
        $arr = explode(',', $request->str);
        $user = Auth::user();
        foreach($arr as $email)
        {
            $subject = UtilityRepository::get_setting('inviteMailSubject');
            $body    = UtilityRepository::get_setting('inviteMailBody');

            $msg = $this->userRepo->mailBodyParser($body, $user);
            $email_array          = new stdCLass;
            $email_array->user    = $user;
            $email_array->msg     = $msg;
            $email_array->subject = $subject;
            Plugin::Fire('send_email', $email_array);
        }
    }

    public function redirect()
    {   
        return Socialite::driver('google')->scopes(['email'])->redirect();
    }

    public function redirect_contacts()
    {
        config(['services.google.redirect' => url('/google/import/contacts') ]);
        return Socialite::driver('google')->scopes(['https://www.google.com/m8/feeds'])->redirect();   
    }


    //route (/gogle/callback)
    public function handleCallback (Request $request) 
    {

        if($request->error == 'access_denied') {
            return redirect('/login');
        }

        $user = Socialite::driver('google')->user();

        $this->socialLoginRepo->setSocialmedia('google') //mandatory
                            ->setUser(Auth::user()) //mandatory
                            ->setSocialMediaUser($user); //mandatory

        /* if auth user exists then social account linked return true after inserting into sociallogin table*/
        if($this->socialLoginRepo->linkSocialAccount()) {
            return redirect('encounter');
        }


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
                                ->setSocialDefultPicture(!($user->user['image']['isDefault'] == false), $this->getLarzeAvatarURL($user));


        $response = $this->socialLoginRepo->doLogin();
            
           
        if($response['login']) {
            return redirect('encounter')->with('data_incomplete', $response['data_incomplete']);
        }

        return redirect('/login');

    }


    public function getLarzeAvatarURL($user)
    {
        $split = explode('?', $user->avatar);
        return $split[0] . '?sz=550';
    }


    public function importContacts(Request $request)
    {

/*
        if (isset($request->code)) {
         $auth_code = $request->code;
         $_SESSION['google_code'] = $auth_code;
         // header('Location: ' . 'http://localhost/liteoxide/liteoxide/public/google/callback');
        }

        $auth_code = $_SESSION['google_code'];
        $max_results = 200;
        $fields=array(
        'code'=>  urlencode($auth_code),
        'client_id'=>  urlencode('307122860694-urjv85sp4k15aof5qacsqk5q5pvu14ie.apps.googleusercontent.com'),
        'client_secret'=>  urlencode('7f2sxrg8W7VJJb69rc6V-X2C'),
        'redirect_uri'=>  urlencode('http://localhost/liteoxide/liteoxide/public/google/import/contacts'),
        'grant_type'=>  urlencode('authorization_code')
        );
        $post = '';
        foreach($fields as $key=>$value)
        {
            $post .= $key.'='.$value.'&';
        }
        $post = rtrim($post,'&');
        // dd($post);
        $result = $this->curl('https://accounts.google.com/o/oauth2/token',$post);
        $response =  json_decode($result);
        $accesstoken = $response->access_token;
        $url = 'https://www.google.com/m8/feeds/contacts/default/full?max-results='.$max_results.'&alt=json&v=3.0&oauth_token='.$accesstoken;
        
        $xmlresponse =  $this->curl($url);
        $contacts = json_decode($xmlresponse,true);
        // $googleService = \OAuth::consumer('Google');
        // $result = json_decode($googleService->request($url), true);
        $name = array();

         if (!empty($contacts['feed']['entry'])) {
         foreach($contacts['feed']['entry'] as $contact) {
                   //retrieve Name and email address  
            // else
            //     continue;
                if(isset($contact['title']['$t']) && isset($contact['gd$email'][0]['address']))
                {
                    array_push($name, $name[$contact['title']['$t']] = $contact['gd$email'][0]['address']);
                    // array_push($email, $contact['gd$email'][0]['address']);
                }

            } 
         }
         
         return $name;   
*/
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








    //this funciton shows gogole pluign login credential settings
    // Route:: /admin/pluginsettings/google
    public function showSettings () {

        $google_appId     = UtilityRepository::get_setting('google_appId');
        $google_secretKey = UtilityRepository::get_setting('google_secretKey');

        return Plugin::view('GooglePlugin/settings', ['appid' => $google_appId, 'secretkey' => $google_secretKey]);
    }



    //this funciton saves/updates gogole pluign login credential settings
    //Route:: /admin/pluginsettings/google
    public function saveSettngs (Request $request) {
        
        try {

            UtilityRepository::set_setting('google_appId', $request->appid);
            UtilityRepository::set_setting('google_secretKey', $request->secretkey);

            return response()->json(['status' => 'success', 'message' => trans_choice('admin.google',1).' '.trans_choice('admin.login',0).' ' .trans_choice('admin.set_status_message',0)]);

        } catch (\Exception $e) {

            return response()->json(['status' => 'error', 'message' => trans_choice('admin.set_status_message',1) .' '.trans_choice('admin.google',1).' '.trans_choice('admin.login',0) . ' '. trans_choice('admin.settings',0) ]);
        }
        
        
    }
}
