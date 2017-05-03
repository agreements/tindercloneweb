<?php
use App\Components\PluginAbstract;

use App\Components\Plugin;
use App\Components\Theme;
use App\Repositories\PeopleNearByRepository;
use App\Components\Api;

class PeopleNearByPlugin extends PluginAbstract
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
		return 'This is the Paypal Plugin.';
	}

	public function version()
	{
		return '1.0.0';
	}


	public function isCore()
	{
		return true;
	}
	
	public function hooks()
	{
		
		$creditRepo = app("App\Repositories\CreditRepository");
		
		Theme::hook('main_menu', function(){
			
			return [
				[
					"title"      => trans('app.peoplenearby'), 
					"symname"    => "location_on", 
					"priority"   => 5, 
					"url"        => url('/peoplenearby'), 
					"attributes" => [
					"class"      =>"material-icons pull-left material-icon-custom-styling"
					]
				]
        		
        	];

		});


		Theme::hook('credits-feature',function() use ($creditRepo){
			$riseup_screenshot = $creditRepo->getRiseupScreenshotUrl();
			return Theme::view('plugin.PeopleNearByPlugin.credits_riseup', ['riseup_screenshot' => $riseup_screenshot]);
		});	
		
		Plugin::add_hook('custom_section_field_deleted', function ($field) {
			PeopleNearByRepository::deleteFieldFromUserPreferences($field->id);
		});



	}	

	public function autoload()
	{

		return array(
			Plugin::path('PeopleNearByPlugin/controllers'),
			Plugin::path('PeopleNearByPlugin/repositories'),
			Plugin::path('PeopleNearByPlugin/models'),
		);

	}

	public function routes()
	{
		Route::group(['middleware' => 'auth'], function(){
			Route::post('/set_location', 'App\Http\Controllers\PeopleNearByController@set_location');
			Route::get('/peoplenearby', 'App\Http\Controllers\PeopleNearByController@showPeople');
			Route::post('/home/searchfilter', 'App\Http\Controllers\PeopleNearByController@searchFilter');
			Route::post('/set-prefer-online-status', 'App\Http\Controllers\PeopleNearByController@setPreferOnlineStatus');
			Route::post('/riseup/pay', 'App\Http\Controllers\PeopleNearByController@payRiseUp');
			Route::post('/get/riseup/credits', 'App\Http\Controllers\PeopleNearByController@getRiseupCredits'); 
		});
		
	


		/* Mobile apis */
		$nspace = "App\\Http\\Controllers\\";
		Api::route('people-nearby', $nspace.'PeopleNearByApiController@peoplenearby');
		Api::route('people-nearby/pay/riseup', $nspace.'PeopleNearByApiController@payRiseUp');
		Api::route('people-nearby/set-profile-location', $nspace.'PeopleNearByApiController@setProfileLocation');
		Api::route('people-nearby/filter/save', $nspace.'PeopleNearByApiController@saveSearchFilter');
		Api::route('people-nearby/filter/online-status/save', $nspace.'PeopleNearByApiController@saveSearchFilterOnlineStatus');
	}
}