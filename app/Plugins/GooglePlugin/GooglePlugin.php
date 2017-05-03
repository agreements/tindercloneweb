<?php
use App\Components\PluginAbstract;
use App\Events\Event;
use App\Components\Plugin;
use App\Components\Theme;
use App\Components\Api;
use App\Models\Settings;
use App\Models\SocialLogins;

class GooglePlugin extends PluginAbstract
{
	public function website()
	{
		return 'datingframework.com';
	}

	public function author()
	{
		return 'DatingFramework';
	}

	public function description()
	{
		return 'This plugin is used for Google Login and Register.';
	}

	public function version()
	{
		return '1.0.0';
	}

	public function isSocialLogin()
	{
		return true;
	}
	
	public function hooks()
	{
		//hoocking up the facebook login button to login page
		Theme::hook('login', function(){

			$url = url('/google');
			$social_login = SocialLogins::where('name','GooglePlugin')->first();

// 			$html = '<a href = "'. $url .'"> <button class="social_gplus"><i class="fa fa-google-plus"></i>Login With Google</button> </a>';

			return array(array("title" => trans('admin.signin_with')." Google" ,"priority" => $social_login->priority ,"icon_class" => "fa fa-google icongoogle" ,"url" => $url, "attributes" =>array("class"=>"inline btn btn--sm btn--social btn--google fadeInLeft wow")));
	
			// return $html;
		});

		//adding admin hook to left menu
		Theme::hook('admin_plugin_menu', function(){

			$url = url('/admin/pluginsettings/google');
			$html = '<li><a href="' . $url . '" type="button"><i class="fa fa-circle-o"></i> Google '.trans('admin.login').'</a></li>';

			return $html;
		});

		Theme::hook('admin_email_content', function(){
            return array(
                array(
                    'heading' => 'Invitation Mail Settings',
                    'title' => 'Invite',
                    'mailbodykey' => 'inviteMailBody',
                    'mailsubjectkey' => 'inviteMailSubject',
                    'email_type' => 'google_invite',
                ),

            );
        });
		//retriving google settings
		$data = $this->googleSettings();	
		

		//setting google settings to laravel config
		config(['services.google.client_id' => $data['google_appId'] ]);
		config(['services.google.client_secret' => $data['google_secretKey'] ]);
		config(['services.google.redirect' => url('/google/callback') ]);

		
		Plugin::add_hook("google_verification", function(){
			return ["text" => 'Google', 'icon_class' => 'fa fa-google icongoogle'];
		});
		
	}	


	public function autoload()
	{
		return array(
			Plugin::path('GooglePlugin/controllers'),
			Plugin::path('GooglePlugin/repositories'),
		);
	}

	public function routes()
	{
		//google login route
		Route::get('/google', 'App\Http\Controllers\GooglePluginController@redirect');
		Route::get('google/callback', 'App\Http\Controllers\GooglePluginController@handleCallback');	
		Route::get('google_contacts', 'App\Http\Controllers\GooglePluginController@redirect_contacts');	
		Route::get('google/import/contacts', 'App\Http\Controllers\GooglePluginController@importContacts');
		Route::get('google_send_emails', 'App\Http\Controllers\GooglePluginController@send_emails');	


		Route::group(['middleware' => 'admin'], function(){
			Route::get('/admin/pluginsettings/google', 'App\Http\Controllers\GooglePluginController@showSettings');
			Route::post('/admin/pluginsettings/google', 'App\Http\Controllers\GooglePluginController@saveSettngs');
		});


		Api::route('google', 'App\Http\Controllers\GoogleApiController@google', 'no-api');
		Api::route('google/get-app-id', 'App\Http\Controllers\GoogleApiController@getAppID', 'no-api');

	}


	//this function returns the facebook app 
	//credentials from database settings table
	protected function googleSettings()
	{
		$google_appId = Settings::where('admin_key', '=', 'google_appId')
						->first();
		
		$google_secretKey = Settings::where('admin_key','=','google_secretKey')
						 ->first();


		if($google_appId != null && $google_secretKey != null)
		{
			$google_appId = $google_appId->value;
			$google_secretKey = $google_secretKey->value;	

			return array('google_appId'=>$google_appId,
					 'google_secretKey'=>$google_secretKey);			
		}
		else
		{
			return array('google_appId'=>null,
					 'google_secretKey'=>null);			
		}
	}


}
