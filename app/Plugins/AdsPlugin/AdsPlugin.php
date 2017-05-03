<?php
use App\Components\PluginAbstract;
use App\Components\Plugin;
use App\Components\Theme;

class AdsPlugin extends PluginAbstract
{
	public function author()
	{
		return 'DatingFramework';
	}

	public function description()
	{
		return 'This is the Ads Plugin.';
	}

	public function version()
	{
		return '1.0.0';
	}
	public function website()
	{
		return 'datingframework.com';
	}

	public function hooks()
	{


		//adding admin hook to left menu
		Theme::hook('admin_plugin_menu', function(){

			$url = url('/admin/ads');
			$trans_text = trans_choice('admin.advertise',1) . trans('admin.setting');
			$html = "<li>
						<a href=\"{$url}\">
							<i class=\"fa fa-circle-o\"></i>{$trans_text}
						</a>
					</li>";

			return $html;
		});	

		
		$adsRepository = app('App\Repositories\AdsRepository');
		$adsRepository->initializeAds()->registerHooks();
	
		
	}	

	public function autoload()
	{

		return array(
			Plugin::path('AdsPlugin/controllers'),
			Plugin::path('AdsPlugin/repositories'),
			Plugin::path('AdsPlugin/models'),

		);

	}

	public function routes()
	{
		Route::group(['middleware' => 'admin'], function(){

			Route::get('/admin/ads', 'App\Http\Controllers\AdsController@show');
			Route::post('/admin/ads/add_banner', 'App\Http\Controllers\AdsController@add_banner');
			Route::post('/admin/ads/update', 'App\Http\Controllers\AdsController@update');
			Route::post('/admin/ads/delete', 'App\Http\Controllers\AdsController@delete');
			Route::post('/admin/ads/placeholder', 'App\Http\Controllers\AdsController@placeholder');
		});
			
		
	}
}