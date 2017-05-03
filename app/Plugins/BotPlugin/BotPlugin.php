<?php

use App\Components\PluginAbstract;
use App\Events\Event;
use App\Components\Plugin;
use App\Components\Theme;
use Illuminate\Support\Facades\Auth;
use App\BotPlugin\Repository\BotRepository;
use App\Models\User;

class BotPlugin extends PluginAbstract {

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
		return 'This is the Game Mechanics Plugin.';
	}

	public function version()
	{
		return '1.0.0';
	}





	public function hooks () {

		//adding admin hook to left menu
		Theme::hook('admin_plugin_menu', function(){

			$url = url('/admin/plugin/bots');
			$html = '<li><a href="' . $url . '"><i class="fa fa-circle-o"></i>'.trans_choice('admin.bot',0).' '.trans('admin.setting').'</a></li>';

			return $html;
		});



		//adding admin hook to left menu
		Theme::hook('admin_plugin_menu', function(){

			$url = url('/admin/plugin/bots/create');
			$html = '<li><a href="' . $url . '"><i class="fa fa-circle-o"></i> '.trans_choice('admin.create',0).' '.trans('admin.new').' '.trans_choice('admin.bot',0).'</a></li>';

			return $html;
		});	


		//adding admin hook to left menu
		Theme::hook('admin_plugin_menu', function(){

			$url = url('/admin/plugin/bot_users');
			$html = '<li><a href="' . $url . '"><i class="fa fa-circle-o"></i>'.trans('admin.show_bot_users').'</a></li>';

			return $html;
		});	




		//create bots event handler
		Plugin::add_hook('account_create', function ($user) {

			$botRepo = app('App\BotPlugin\Repositories\BotRepository');
			
			try {

				$count = $botRepo->countNearbyUsers(
					$user->latitude, 
					$user->longitude, 
					100, 
					'km'
				);

				if($count < 2){
						$botRepo->createBotUsers($user);					
				}
				
			} catch (\Exception $e) {}
		});

		
	}	





	public function autoload()
	{

		return array(

			Plugin::path('BotPlugin/controllers'),
			Plugin::path('BotPlugin/repositories'),
			Plugin::path('BotPlugin/models'),

		);

	}





	public function routes()
	{
		
		Route::group(['middleware' => 'admin'], function() {

			Route::get('admin/plugin/bots', 'App\Http\Controllers\BotController@showBots');
			Route::get('admin/plugin/bot_users', 'App\Http\Controllers\BotController@showBotUsers');
			Route::post('/admin/plugin/bots/doaction', 'App\Http\Controllers\BotController@doAction');

			Route::get('admin/plugin/bots/create', 'App\Http\Controllers\BotController@showCreate');
			Route::post('admin/plugin/bot/create', 'App\Http\Controllers\BotController@createBot');
			
			Route::post('admin/plugin/bot/delete', 'App\Http\Controllers\BotController@deleteBot');
			Route::post('admin/plugin/bot/deactivate', 'App\Http\Controllers\BotController@deactivateBot');
			Route::post('admin/plugin/bot/activate', 'App\Http\Controllers\BotController@activateBot');
			Route::post('admin/plugin/bot/settings', 'App\Http\Controllers\BotController@settings');
			Route::post('admin/plugin/bot/upload-photos', 'App\Http\Controllers\BotController@uploadPhotos');
			Route::get('admin/plugin/bot/photos', 'App\Http\Controllers\BotController@getBotPhotos');
		});
		
	}
}