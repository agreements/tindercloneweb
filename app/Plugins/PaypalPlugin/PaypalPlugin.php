<?php
use App\Components\PluginAbstract;
use App\Events\Event;
use App\Components\Plugin;
use App\Components\Theme;
use App\Settings;

class PaypalPlugin extends PluginAbstract
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
		return 'This plugin enables Payment using Paypal.';
	}

	public function version()
	{
		return '1.0.0';
	}

	
	public function hooks()
	{

		//adding admin hook to left menu
		Theme::hook('admin_plugin_menu', function(){

			$url = url('/admin/pluginsettings/paypal');
			$html = '<li><a href="' . $url . '"><i class="fa fa-circle-o"></i>'.trans('admin.paypal').' '.trans('admin.setting').'</a></li>';

			return $html;
		});

		Theme::hook('payment-tab', function() {
			
			return Plugin::view('PaypalPlugin/tab', array());
		});

		Theme::hook('payment-tab_content', function() {
			
			return Plugin::view('PaypalPlugin/tab_content', array());
		});

	}	

	public function autoload()
	{

		return array(
			Plugin::path('PaypalPlugin/controllers'),
			Plugin::path('PaypalPlugin/repositories'),
		);

	}

	public function routes()
	{
		Route::post('/paypal', 'App\Http\Controllers\PaypalController@paypal');
			//paypal top-up
		Route::get('paypal/returnurl', 'App\Http\Controllers\PaypalController@returnurl');
		Route::get('paypal/cancelurl', 'App\Http\Controllers\PaypalController@cancelurl');

		Route::group(['middleware' => 'auth'], function(){

		});

		Route::group(['middleware' => 'admin'], function(){

			//paypal admin settings view route
			Route::get('/admin/pluginsettings/paypal', 'App\Http\Controllers\PaypalController@showSettings');
			Route::post('/admin/pluginsettings/paypal', 'App\Http\Controllers\PaypalController@saveSettngs');
		});
	
	}
}
