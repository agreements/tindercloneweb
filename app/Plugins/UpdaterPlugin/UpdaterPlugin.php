<?php
use App\Components\PluginAbstract;
use App\Components\Plugin;


class UpdaterPlugin extends PluginAbstract
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
		return 'This plugin enables updating the script.';
	}

	public function version()
	{
		return '1.0.0';
	}

	

	public function hooks()
	{

	}	

	public function isCore()
	{
		return true;
	}
	
	public function autoload()
	{

		return [
			Plugin::path('UpdaterPlugin/controllers'),
			Plugin::path('UpdaterPlugin/repositories'),
		];

	}

	public function routes()
	{
		//restrict access without admin login
		Route::group(['middleware' => 'admin'], function(){

			Route::get('admin/updater', 'App\Plugins\UpdaterPlugin\Controllers\UpdaterPluginController@showUpdater');
			Route::post('admin/updater/checkupdate', 'App\Plugins\UpdaterPlugin\Controllers\UpdaterPluginController@checkUpdate');
			// Route::get('/admin/updater/dodownload', 'App\Http\Controllers\UpdaterPluginController@doDownload');
			Route::post('/admin/updater/doupdate', 'App\Plugins\UpdaterPlugin\Controllers\UpdaterPluginController@doUpdate');
			// Route::get('/admin/updater/dobackup', 'App\Http\Controllers\UpdaterPluginController@doBackup');

		});
	}	
}