<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\VisitorRepository;
use App\Repositories\RegisterRepository;
use App\Repositories\CreditRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\EncounterRepository;
use App\Repositories\SuperpowerRepository;
use App\Repositories\LanguageRepository;
use App\Repositories\Admin\UtilityRepository;
use App\Repositories\NotificationsRepository;
use App\Repositories\Admin\UserManagementRepository;
use Validator;
use Hash;
use App\Components\Plugin;

class SettingsController extends Controller {

    protected $userRepo;
    protected $registerRepo;
    protected $creditRepo;
    protected $profileRepo;
    protected $encounterRepo;
    protected $langRepo;
    protected $notifRepo;
    protected $visitorRepo;
    protected $superRepo;
    
    public function __construct () {
        $this->userRepo      = app("App\Repositories\UserRepository");
        $this->registerRepo  = app("App\Repositories\RegisterRepository");
        $this->creditRepo    = app("App\Repositories\CreditRepository");
        $this->profileRepo   = app("App\Repositories\ProfileRepository");
        $this->encounterRepo = new EncounterRepository;
        $this->langRepo      = new LanguageRepository;
        $this->notifRepo     = new NotificationsRepository;
        $this->visitorRepo   = new VisitorRepository;
        $this->superRepo     = app("App\Repositories\SuperpowerRepository");
    }   

	
    public function settings (Request $req) {

        $auth_user = $req->real_auth_user;
        $languages = $this->langRepo->getSupportedLanguages();
        $notif_settings = $this->notifRepo->getNotificationSettings($auth_user->id);
        $privacy_settings = $this->userRepo->getPrivacySettings($auth_user->id);
        $invisible_settings = $this->userRepo->getInvisibleSettings($auth_user->id);

        return response()->json([
            "status" => "success",
            "success_data" => [
                "languages" => [
                    "supported" => $languages,
                    "user_language" => $auth_user->language
                ],
                "notification_settings" => [
                    "visitor_notification" => [
                        "browser" => $notif_settings['browser_visitor'],
                        "email" => $notif_settings['email_visitor']
                    ],
                    "like_notification" => [
                        "browser" => $notif_settings['browser_liked'],
                        "email" => $notif_settings['email_liked']
                    ],
                    "match_notification" => [
                        "browser" => $notif_settings['browser_match'],
                        "email" => $notif_settings['email_match']
                    ],
                ],
                "privacy_settings" => [
                    "show_online_status" => $privacy_settings['online_status'],
                    "show_distance" => $privacy_settings['show_distance'],
                ],
                "invisible_settings" => [
                    "hide_profile_visit" => $invisible_settings['hide_visitors'],
                    "hide_superpower" => $invisible_settings['hide_superpowers']
                ],
                "user_password_set" => ($auth_user->password) ? "true" : "false",
                "auth_user" => $req->auth_user,
                "success_text" => "User settings retrived successfully."
            ]
        ]);
    }



    public function changeEmail (Request $req) {
        
        $valid = Validator::make($req->all(), [
            'email'  => 'required|max:255|email|unique:user,username',
        ]);

        if ($valid->fails ()) {
            $messages = $valid->errors()->all();
            return response()->json([
                'status'=>'error', 
                "error_data" => [
                    "error_text" => $messages[0]
                ]
            ]);
        }

        $auth_user = $req->real_auth_user;
        $new_email = $req->email;

        $changed_user = $this->userRepo->changeEmail($auth_user, $new_email);
        $changed_user->activate_user = 'activated';
        $changed_user->save();
        
        $this->register_after_mail_send_event();
        $this->sendEmailChangedMail($auth_user);

        $account_activated = ($auth_user->activate_user == 'activated') ? "true" : "false";

        return response()->json([
            'status'=>'success', 
            "success_data" => [
                "user_account_activated" => $account_activated,
                "success_text" => "Email has changed successfully."
            ]
        ]);
    }

    public function register_after_mail_send_event () {

        Plugin::hook('after_mail_send', function($user){

            $user->activate_user = 'deactivated';
            $user->save();

        });

    }

    public function sendEmailChangedMail ($user) {
        $email_array = new \stdCLass;
        $email_array->user = $user;
        $email_array->type = "change_email";
        Plugin::fire('send_email', $email_array);
    }




    public function changePassword (Request $req) {

        $user    = $req->real_auth_user;
        $oldPass = $req->old_password;

        if (!$req->password) {
            return response()->json([
                "status" => "error", 
                "error_data" => [
                    "error_text" => "Password is required"
                ]
            ]);
        }


        if ($user->password == '' || password_verify($oldPass, $user->password)) {

            if ($req->password == $req->confirm_password) {

                $newPass = Hash::make($req->password);
                $user->password = $newPass;
                $user->save();
            
            } else {

                return response()->json([
                    "status" => "error", 
                    "error_data" => [
                        "error_text" => "Confirm password not matched."
                    ]
                ]);
            }
        } else {

            return response()->json([
                "status" => "error", 
                "error_data" => [
                    "error_text" => "Wrong old password."
                ]
            ]);
        }
   
        $email_array = new \stdCLass;
        $email_array->user = $user;
        $email_array->type = "change_password";

        Plugin::fire('send_email', $email_array);

        return response()->json([
            'status'=>'success', 
            "success_data" => [
                "success_text" => "Password has changed successfully."
            ]
        ]);
    }



    public function saveNotifications (Request $req) {
        $auth_user = $req->real_auth_user;

        $notif_settings = [];
        $notif_settings['browser_visitor'] = $req->browser_visitor ? "1" : '0';
        $notif_settings['email_visitor']   = $req->email_visitor ? "1" : '0';
        $notif_settings['browser_liked']   = $req->browser_liked ? "1" : '0';
        $notif_settings['email_liked']     = $req->email_liked ? "1" : '0';
        $notif_settings['browser_match']   = $req->browser_match ? "1" : '0';
        $notif_settings['email_match']     = $req->email_match ? "1" : '0';
        
        $this->notifRepo->set_notif_settings($notif_settings, $auth_user->id);  

        return response()->json([
            'status'=>'success', 
            "success_data" => [
                "success_text" => "Notification settings saved successfully."
            ]
        ]);   
    }


    public function savePrivacy (Request $req) {
        
        $auth_user = $req->real_auth_user;
        
        $show_online_status = $req->show_online_status ? "1" : "0";
        $show_distance = $req->show_distance ? "1" : "0";
        $auth_user->settings_save('online_status', $show_online_status);
        $auth_user->settings_save('show_distance', $show_distance);
        
        return response()->json([
            'status'=>'success', 
            "success_data" => [
                "success_text" => "Privacy settings saved successfully."
            ]
        ]);
    }



    public function saveInvisible (Request $req) {

        $auth_user = $req->real_auth_user;
        $super_power = $auth_user->user_super_powers()->first();

        if (!$this->superpowerActivated ($super_power)) {
            return response()->json([
                'status'=>'error', 
                "error_data" => [
                    "user_superpower" => "false",
                    "error_text" => "User superpower not activated"
                ]
            ]);
        }

        $super_power->invisible_mode = $req->hide_profile_visit ? "1" : "0";
        $super_power->hide_superpowers = $req->hide_superpowers ? "1" : "0";
        $super_power->save();
        
        return response()->json([
            'status'=>'success', 
            "success_data" => [
                "success_text" => "Invisible settings saved successfully."
            ]
        ]);
    }

    public function superpowerActivated ($supwerpower_obj) {
        if ($supwerpower_obj
            && date_create(date('Y-m-d')) <= date_create($supwerpower_obj->expired_at) ) {
            return true;
        } 
        return false;
    }




    public function deactivateUser (Request $req) {

        $auth_user = $req->real_auth_user;

        app('App\Repositories\Admin\UserManagementRepository')->deactivateUsers([$auth_user->id]);   
        $auth_user->settings_save('activation_status', '0');
        $auth_user->access_token = rand();
        $auth_user->save();

        return response()->json([
            'status'=>'success', 
            "success_data" => [
                "success_text" => "User deactivated successfully."
            ]
        ]);
    }



    public function deleteUser (Request $req) {

        $auth_user = $req->real_auth_user;

        
        if ( $auth_user->password != '' && !password_verify($req->password, $auth_user->password) ) {
            return response()->json([
                'status'=>'error', 
                "error_data" => [
                    "error_text" => "User password not matched."
                ]
            ]);
        } 

        app('App\Repositories\Admin\UserManagementRepository')->delete_users_permenently([$auth_user->id]);
        Plugin::fire('users_deleted', [ [$auth_user->id] ]);

        return response()->json([
            'status'=>'success', 
            "success_data" => [
                "success_text" => "User deleted successfully."
            ]
        ]);    
    }


    public function saveLanguage (Request $req) {

        $auth_user = $req->real_auth_user;
        $language = $req->language;

        if (!$language) {
            return response()->json([
                'status'=>'error', 
                "error_data" => [
                    "error_text" => "Language is required."
                ]
            ]);
        }

        $auth_user->language = $language;
        $auth_user->save();

        return response()->json([
            'status'=>'success', 
            "success_data" => [
                "success_text" => "User language saved successfully."
            ]
        ]);  
    }


}
 