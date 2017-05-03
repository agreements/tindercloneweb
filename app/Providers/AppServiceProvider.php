<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Blade;
use App\Components\Theme;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
	   
        $this->initNeededModels();

        //view path sets for plugin 
        config(['view.paths' => [
          realpath(base_path('app/Plugins')), 
          realpath(base_path('resources/views')),
          realpath(base_path('app/Custom/Views'))
        ]]);

       
        $debug_mode = $this->settings->get('debug_mode');
        $debug_mode = ($debug_mode == 'true') ? true: false;
        config([
          'app.debug' => $debug_mode
        ]);

        
        $this->register_user_create();
        $this->register_photo_create();
        
        
        //this is for @plugin_asset() directive
        Blade::directive('plugin_asset', function($expression) {
            $expression = substr($expression, 2, strlen($expression) - 4);
            return asset('plugins/' . $expression);
        });

        

        
        //this is for @theme_asset() directive
        Blade::directive('theme_asset', function($expression) {

            $parent_theme = Theme::get_activated_theme_by_role('parent');
            $child_theme = Theme::get_activated_theme_by_role('child');

            $expression = substr($expression, 2, strlen($expression) - 4);
            
            $path = public_path("/themes/{$child_theme}/{$expression}");
            
            return ($child_theme && file_exists($path)) 
                    ? asset("themes/{$child_theme}/{$expression}")
                    : asset("themes/$parent_theme/{$expression}");    

        });

         
        
         //this is for @asset() directive
        Blade::directive('asset', function($expression) {

            $parent_theme = Theme::get_activated_theme_by_role('parent');
            $expression = substr($expression, 2, strlen($expression) - 4);
            return asset("themes/{$parent_theme}/{$expression}");

        });



        $this->callThemeHooks();
        $this->setEmailSettings();

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
	    
        $this->app->singleton('GlobalComposer', function($app){
            return $this->app->make('App\Http\ViewComposers\GlobalComposer');
        });

        $this->app->singleton('CreditRepository', function ($app) {
	     
			return new App\Repositories\CreditRepository(
                $this->app->make("App\Models\Package"), 
                $this->app->make("App\Models\Credit"), 
                $this->app->make("App\Models\CreditHistory"), 
                $this->app->make("App\Models\Transaction"), 
                $this->app->make("App\Models\User")
            );
		});
		
		
        $this->app->singleton('BlockUserRepository', function ($app) {
	  
				return new App\Repositories\BlockUserRepository($this->app->make("App\Models\BlockUsers"));
		});
		
		
		$this->app->singleton('AbuseReportRepository', function ($app) {
	    
			return new App\Repositories\AbuseReportRepository(
                $this->app->make("App\Models\Photo"), 
                $this->app->make("App\Models\PhotoAbuseReport"), 
                $this->app->make("App\Models\UserAbuseReport"), 
                $this->app->make("App\Models\User") 
            );
				
		});
		
		
        // Encounter $encounter, User $user, NotificationSettings $notification_settings, UserSuperPowers $user_superpowers, UserSettings $user_settings, SuperPowerPackages $superpower_packages, Notifications $notifications, Transaction $transaction, SuperpowerHistory $superpower_history, Match $match, Visitor $visitor, EmailSettings $email_settings
		
		 $this->app->singleton('HomeRepository', function ($app) {
	    
			return new App\Repositories\HomeRepository(
                $this->app->make("App\Models\Encounter"), $this->app->make("App\Models\User"), 
                $this->app->make("App\Models\NotificationSettings"), 
                $this->app->make("App\Models\UserSuperPowers"), 
                $this->app->make("App\Models\UserSettings"), 
                $this->app->make("App\Models\SuperpowerPackages"),
                $this->app->make("App\Models\Notifications"), 
                $this->app->make("App\Models\Transaction"), 
                $this->app->make("App\Models\SuperpowerHistory"), 
                $this->app->make("App\Models\Match"), 
                $this->app->make("App\Models\Visitor"), 
                $this->app->make("App\Models\EmailSettings")
            );
				
		});  
		
		
        // User $user, UserSettings $user_settings, UserSocialLogin $user_social_login, Photo $photo, EmailSettings $email_settings, UserSuperPowers $user_superpowers
		
        $this->app->singleton('UserRepository', function ($app) {
	    
			return new App\Repositories\UserRepository(
                $this->app->make("App\Models\User"), 
                $this->app->make("App\Models\UserSettings"), 
                $this->app->make("App\Models\UserSocialLogin"), 
                $this->app->make("App\Models\Photo"), 
                $this->app->make("App\Models\EmailSettings"), 
                $this->app->make("App\Models\UserSuperPowers")
            );
				
		}); 
		
		
		// FieldOptions $field_options, UserFields $user_fields, Fields $fields, UserPreferences $user_preferences, Photo $photo, FieldSections $field_sections, FacebookFriends $facebook_friends, User $user, Encounter $encounter, Visitor $visitor, Interests $interests, UserInterests $user_interests
		
		$this->app->singleton('ProfileRepository', function ($app) {
	    
			return new App\Repositories\AbuseReportRepository(
                $this->app->make("App\Models\FieldOptions"), 
                $this->app->make("App\Models\UserFields"), 
                $this->app->make("App\Models\Fields"), 
                $this->app->make("App\Models\UserPreferences"), 
                $this->app->make("App\Models\Photo"), 
                $this->app->make("App\Models\FieldSections"),
                $this->app->make("App\Models\FacebookFriends"), 
                $this->app->make("App\Models\User"), 
                $this->app->make("App\Models\Encounter"), 
                $this->app->make("App\Models\Visitor"), 
                $this->app->make("App\Models\Interests"), 
                $this->app->make("App\Models\UserInterests")
            );
				
		}); 


        //User $user, Profile $profile, Settings $settings
        $this->app->singleton('RegisterRepository', function($app){

            return new App\Repositories\RegisterRepository(
                $this->app->make('App\Models\User'),
                $this->app->make('App\Models\Profile'),
                $this->app->make('App\Models\Settings')
            );


        });   


        //SuperPowerPackages $superPowerPackages, Transaction $transaction, User $user, SuperpowerHistory $superpowerHistory, UserSuperPowers $userSuperPowers  
		$this->app->singleton('SuperpowerRepository', function($app){

            return new App\Repositories\SuperpowerRepository(
                $this->app->make('App\Models\SuperPowerPackages'),
                $this->app->make('App\Models\Transaction'),
                $this->app->make('App\Models\User'),
                $this->app->make('App\Models\SuperpowerHistory'),
                $this->app->make('App\Models\UserSuperPowers')
            );


        });


        /* registering App\Repositories\MaxmineGEOIPRepository */
        $this->app->singleton('MaxmindGEOIPRepository', function($app){
            return new App\Repositories\MaxmindGEOIPRepository(
                $this->app->make('App\Models\Settings')
            );
        });
    }


    public function initNeededModels()
    {
        $this->user      = $this->app->make('App\Models\User');
        $this->profile   = $this->app->make('App\Models\Profile');
        $this->credit    = $this->app->make('App\Models\Credit');
        $this->settings  = $this->app->make('App\Models\Settings');
        $this->photo     = $this->app->make('App\Models\Photo');
        $this->emailRepo = $this->app->make('App\Repositories\Admin\EmailSettingRepository');
    }

    public function register_user_create()
    {
        $this->user->created(function ($user) {
            $this->create_profile($user);
            $this->create_credits($user);
        });
    }

    public function create_profile($user)
    {
        $profile = new $this->profile;
        $profile->prefer_age = "18-80";
        $profile->prefer_gender = $this->settings->get('default_prefered_genders');
        $profile->prefer_distance_nearby = 100;
        $profile->userid = $user->id;
        $profile->latitude = (isset($user->latitude) && !is_null($user->latitude)) ? $user->latitude : '';
        $profile->longitude = (isset($user->longitude) && !is_null($user->longitude)) ? $user->longitude : '';
        $profile->save();
    }

    public function create_credits($user)
    {
            $defaultCredits = $this->settings->get('defaultCredits');
            $credit = new $this->credit;
            $credit->userid  = $user->id;
            $credit->balance = ($defaultCredits == '') ? 0 : $defaultCredits;
            $credit->save();
    }

    protected function register_photo_create(){

        $make_profil_picture = $this->settings->get('make_profile_picture');

        if($make_profil_picture == 'true') {

            $this->photo->created(function ($photo) {
                $this->make_profile_picture($photo);
            });

        }
    }

    protected function make_profile_picture ($photo) {

        $photo_count = $this->photo->where('userid', $photo->userid)->count();

        if ($photo_count < 2) {
            $user = $this->user->find($photo->userid);
            $user->profile_pic_url = $photo->photo_url;
            $user->save();
        }
    }




    //call every themes hooks method
    public function callThemeHooks () {

        $parent_theme = Theme::get_activated_theme_by_role('parent');
        $child_theme = Theme::get_activated_theme_by_role('child');

        $parent_theme_class = "{$parent_theme}\\{$parent_theme}";        
        $parent_theme_file_path = base_path("/resources/views/themes/{$parent_theme}/{$parent_theme}.php");

        require_once $parent_theme_file_path;
        $parent_theme_obj = new $parent_theme_class;
        $parent_theme_obj->hooks();


        if ($child_theme) {

          $child_theme_class  = "{$child_theme}\\{$child_theme}";
          $child_theme_file_path = base_path("/resources/views/themes/{$child_theme}/{$child_theme}.php");

          require_once $child_theme_file_path;
          $child_theme_obj = new $child_theme_class;
          $child_theme_obj->hooks();

        }
        
    }



    /* This code will set Email config taking informatino from database settings */  
    public function setEmailSettings()
    {
      

        $driver = $this->emailRepo->getMailDriver();
        
        if ($driver == 'mandrill') {

          $mandrill =$this->emailRepo->getMANDRILLSettings();

          config(['mail.driver'              => 'mandrill' ]);
          config(['mail.host'                => $mandrill->host ]);
          config(['mail.port'                => intval($mandrill->port) ]);
          config(['mail.username'            => $mandrill->username ]);         
          config(['services.mandrill.secret' => $mandrill->password ]);


        } else if ($driver == 'smtp') {

          $smtp = $this->emailRepo->getSMTPSettings();

          config(['mail.driver'       => 'smtp' ]);
          config(['mail.host'         => $smtp->host ]);
          config(['mail.port'         => intval($smtp->port) ]);
          config(['mail.username'     => $smtp->username ]);
          config(['mail.password'     => $smtp->password ]);
          config(['mail.from.address' => $smtp->from ]);
          config(['mail.from.name'    => $smtp->name ]);
          config(['mail.encryption'   => $smtp->encryption ]);
          

        }

    }
}
