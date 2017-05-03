<?php
use App\Components\PluginAbstract;
use App\Repositories\PhotoNudityRepository;
use App\Events\Event;
use App\Components\Plugin;
use App\Components\Theme;
use App\Models\Settings;

class PhotoNudityPlugin extends PluginAbstract
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
		return 'This plugin is used to check for photo nudity.';
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

		Theme::hook('photos-nudity', function() {
			
			$nudity_percentage = app('App\Repositories\PhotoNudityRepository')->get_nudity_percentage();
			return Plugin::view('PhotoNudityPlugin/photo_nudity', array("nudity_percentage"=>$nudity_percentage));
		});

		Theme::hook('admin_plugin_menu', function () {
			$url = url('/admin/plugin/nude-photos');
			$html = "<li>
						<a href=\"{$url}\">
							<i class=\"fa fa-circle-o\"></i>
							Nude Photos
						</a>
					</li>";

			return $html;
		});

		//listening to users deleted by admin event
		Plugin::add_hook('users_deleted', function($user_ids){
			app('App\Repositories\PhotoNudityRepository')->deleteNudePhotos($user_ids);
		});
		
	}	


	public function autoload()
	{
		return array(
			Plugin::path('PhotoNudityPlugin/Controllers'),
			Plugin::path('PhotoNudityPlugin/Repositories'),
			Plugin::path('PhotoNudityPlugin/Models'),
		);
	}

	public function routes()
	{
		Route::group(['middleware' => 'auth'], function(){
			Route::post('report-nude-photo', 'App\Http\Controllers\PhotoNudityController@reportNudePhoto');
			Route::post('mark-photo-checked', 'App\Http\Controllers\PhotoNudityController@markPhotoChecked');
		});

		Route::group(['middleware' => 'admin'], function(){
			Route::get('/admin/plugin/nude-photos','App\Http\Controllers\PhotoNudityController@showNudePhotos');
			Route::post('/admin/plugin/nude-photo-delete','App\Http\Controllers\PhotoNudityController@deletePhoto');
			Route::post('/admin/plugin/nude-photo-recover','App\Http\Controllers\PhotoNudityController@recoverPhoto');
			Route::post('/admin/plugin/nude-photo-seen','App\Http\Controllers\PhotoNudityController@seenPhoto');
			Route::post('/admin/plugin/nude-photo-percentage-save','App\Http\Controllers\PhotoNudityController@savePercentage');
		});
	}

}
