<?php
use App\Components\PluginAbstract;
use App\Events\Event;
use App\Components\Plugin;
use App\Components\Theme;
use App\Components\Api;
use App\Models\Settings;
use App\Models\SocialLogins;
use App\repositories\FacebookRepository;

class FacebookPlugin extends PluginAbstract
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
		return 'This plugin is used for Facebook Login and Facebook photo importing.';
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

		$facebookRepo = app('App\Repositories\FacebookRepository');


		Theme::hook('login', function() use($facebookRepo){

			return array(
				array(
					"title" => trans('admin.signin_with')." Facebook" ,
					"priority" => $facebookRepo->loginPriority(),
					"icon_class" => "fa fa-facebook iconfb",
					"url" => url('/facebook'), 
					"attributes" => array(
						"class"=>"inline btn btn--sm btn--social btn--facebook dd fadeInLeft wow"
					)
				)
			);

		});



		if($facebookRepo->photoImportEnabled()) {
			$facebookRepo->addThemeHooks();
		}

				
		Theme::hook('admin_plugin_menu', function(){

			$url = url('/admin/pluginsettings/facebook');
			$trans_text = trans('admin.facebook').' '.trans('admin.setting');
			$html = "<li>
						<a href=\"{$url}\">
							<i class=\"fa fa-circle-o\"></i>{$trans_text}
						</a>
					</li>";

			return $html;
		});
		

		Plugin::add_hook('users_deleted', function($user_ids) use($facebookRepo){
			$facebookRepo->deleteFacebookUsers($user_ids);
		});


		Plugin::add_hook("facebook_verification", function(){
			return ["text" => 'Facebook', 'icon_class' => 'fa fa-facebook iconfb'];
		});

		
	}	


	public function autoload()
	{
		return array(
			Plugin::path('FacebookPlugin/controllers'),
			Plugin::path('FacebookPlugin/models'),
			Plugin::path('FacebookPlugin/Repositories'),
		);
	}

	public function routes()
	{

			//faceboook login route
		Route::get('/facebook', 'App\Http\Controllers\FacebookPluginController@redirect');
		Route::get('facebook/callback', 'App\Http\Controllers\FacebookPluginController@handleCallback');
		Route::get('facebook/photos', 'App\Http\Controllers\FacebookPluginController@redirect_facebook_photos');
		Route::get('facebook/import/photos', 'App\Http\Controllers\FacebookPluginController@import_photos');
		
		Route::group(['middleware' => 'admin'], function(){
		//facebook admin settings view route
			Route::get('/admin/pluginsettings/facebook', 'App\Http\Controllers\FacebookPluginController@showSettings');
			Route::post('/admin/pluginsettings/facebook', 'App\Http\Controllers\FacebookPluginController@saveSettings');
		});	

		Route::group(['middleware' => 'auth'], function(){
			Route::post('/plugin/facebook/save-imported-photos', 'App\Http\Controllers\FacebookPluginController@downloadImportedPhotos');
		});	
		
		
		Api::route('facebook', 'App\Http\Controllers\FacebookApiController@facebook', 'no-api');
		Api::route('facebook/get-app-id', 'App\Http\Controllers\FacebookApiController@getAppID', 'no-api');
		Api::route('facebook/import-photos', 'App\Http\Controllers\FacebookApiController@downloadFbPhotos');

	}



}
