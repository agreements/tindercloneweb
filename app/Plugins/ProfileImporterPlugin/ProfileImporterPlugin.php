<?php
use App\Components\PluginAbstract;

use App\Components\Plugin;
use App\Components\Theme;


class ProfileImporterPlugin extends PluginAbstract
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
		return 'This plugin imports profiles from csv or excel file.';
	}

	public function version()
	{
		return '1.0.0';
	}

	

	public function isCore()
	{
		return false;
	}
	
	public function hooks()
	{
		Theme::hook('admin_plugin_menu', function(){

			$url = url('/admin/pluginsettings/profileimporter');
			$html = '<li><a href="' . $url . '"><i class="fa fa-circle-o"></i>'.trans('admin.profileimporter').' '.trans('admin.setting').'</a></li>';

			return $html;
		});
		
	}	

	public function autoload()
	{

		return array(
			Plugin::path('ProfileImporterPlugin/controllers')
		);

	}

	public function routes()
	{
		Route::group(['middleware' => 'admin'], function(){

			//paypal admin settings view route
			Route::get('/admin/pluginsettings/profileimporter', 'App\Http\Controllers\ProfileImporterController@showimport');
			Route::post('/admin/pluginsettings/profileimporter', 'App\Http\Controllers\ProfileImporterController@import');
		});

	}
}