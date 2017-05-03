<?php
use App\Components\PluginAbstract;
use App\Events\Event;
use App\Components\Plugin;
use App\Components\Theme;
use App\Repositories\StripeRepository;

class StripePlugin extends PluginAbstract
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
		return 'This plugin enables payment from Stripe.';
	}

	public function version()
	{
		return '1.0.0';
	}

	
	
	public function hooks()
	{
		//adding admin hook to left menu
		Theme::hook('admin_plugin_menu', function(){

			$url = url('/admin/pluginsettings/stripe');
			$html = '<li><a href="' . $url . '"><i class="fa fa-circle-o"></i> Stripe '.trans('admin.setting').'</a></li>';

			return $html;
		});

		Theme::hook('payment-tab', function() {
			
			return Plugin::view('StripePlugin/tab', array());
		});

		Theme::hook('payment-tab_content', function() {
			
			//dd($args);
			$stripe_publishable_key = (new StripeRepository)->getStripePublishableKey();
			return Plugin::view('StripePlugin/tab_content', array('stripe_publishable_key' => $stripe_publishable_key));
		});

	}	

	public function autoload()
	{

		return array(
			Plugin::path('StripePlugin/controllers'),
			Plugin::path('StripePlugin/repositories'),
		);

	}

	public function routes()
	{
		
		Route::post('charge', 'App\Http\Controllers\StripeController@charge');

		Route::group(['middleware' => 'auth'], function(){
			//stripe
		});

		Route::group(['middleware' => 'admin'], function(){

			//paypal admin settings view route
			Route::get('/admin/pluginsettings/stripe', 'App\Http\Controllers\StripeController@showSettings');
			Route::post('/admin/pluginsettings/stripe', 'App\Http\Controllers\StripeController@saveSettngs');
		});
	
	}
}
