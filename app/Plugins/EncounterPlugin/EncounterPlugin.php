<?php
use App\Components\PluginAbstract;
use App\Events\Event;
use App\Components\Plugin;
use App\Components\Api;
use App\Components\Theme;
use App\Components\Email;
use Illuminate\Support\Facades\Auth;
use App\Models\Notifications;
use App\Models\User;
use App\Models\Settings;
use App\Repositories\Admin\UtilityRepository;

class EncounterPlugin extends PluginAbstract
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
		return 'This is the Encounter Plugin.';
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

		Theme::hook('main_menu',function() {
		
			$whoLiked = Notifications::get_count('liked');
			$matches = Notifications::get_count('match');
			if($whoLiked == 0)
				$whoLiked='';
			if($matches == 0)
				$matches='';
			$url = url('encounter');
			$url2 = url('likedme');
			$url4 = url('iliked');
			$url3 = url('matches');
		
			return array(
				array(
					"title" => trans('app.encounters'), 
					"symname" => "whatshot" ,
					"priority" => 1 , 
					"url" => $url, 
					"attributes" => array(
						"class" => "material-icons pull-left material-icon-custom-styling"
					)
				),
				array(
					"title" => trans('app.likedme'), 
					"notification_type" => "liked" ,  
					"symname" => "favorite", 
					"count" => $whoLiked,
					"priority" => 2 ,
					"url" => $url2, 
					"attributes" => array(
						"class" => "material-icons pull-left material-icon-custom-styling"
					)
				),
				array(
					"title" => trans('app.matches'), 
					"notification_type" => "match" ,  
					"symname" => "people", 
					"count" => $matches ,
					"priority" => 3 ,
					"url" => $url3, 
					"attributes" => array(
						"class" => "material-icons pull-left material-icon-custom-styling"
					)
				),
				array(
					"title" => trans('app.mylikes'), 
					"symname" => "star" , 
					"priority" => 4 , 
					"url" => $url4, 
					"attributes" => array(
						"class" => "material-icons pull-left material-icon-custom-styling"
					)
				)
			);
		});


		Notifications::add_formatter("match", function($data){
			$data->entity = User::find($data->entity_id);
			return $data;
   		});


   		Notifications::add_formatter("liked", function($data){
			$data->entity = User::find($data->entity_id);
			return $data;
   		});


		Theme::hook('admin_email_content', function(){
            return array(
                array(
                    'heading' => 'Liked Mail Settings',
                    'title' => 'Liked',
                    'mailbodykey' => 'likedMailBody',
                    'mailsubjectkey' => 'likedMailSubject',
                    'email_type' => 'liked',
                ),

            );
        });

        Theme::hook('admin_email_content', function(){
            return array(
                array(
                    'heading' => 'Matches Mail Settings',
                    'title' => 'Matches',
                    'mailbodykey' => 'matchesMailBody',
                    'mailsubjectkey' => 'matchesMailSubject',
                    'email_type' => 'match'
                ),

            );
        });





        Plugin::add_hook('auth.login', function($user, $remember){
        	if(UtilityRepository::get_setting('auto_browser_geolocation') == 'true') {
        		request()->session()->flash('auto_browser_geolocation_save_required', true);
        	}
        		
        });

		
	}	

	public function autoload()
	{
		return array(
			Plugin::path('EncounterPlugin/controllers'),
			Plugin::path('EncounterPlugin/repositories'),
			Plugin::path('EncounterPlugin/models'),
		);
	}

	public function routes()
	{

		Route::group(['middleware' => 'auth'], function() {
			Route::get('/encounter', 'App\Http\Controllers\EncounterPluginController@showHome');
			Route::post('/doencounter', 'App\Http\Controllers\EncounterPluginController@doEncounter');
			Route::post('/liked/{id}/{val}', 'App\Http\Controllers\EncounterPluginController@isLiked');
			Route::get('/matches', 'App\Http\Controllers\EncounterPluginController@match');
			Route::get('/iliked', 'App\Http\Controllers\EncounterPluginController@liked');
			Route::get('/likedme', 'App\Http\Controllers\EncounterPluginController@whoLiked');
		});
		


		/* Mobile apis */
		$nspace ="App\\Http\\Controllers\\";
		Api::route('encounters', $nspace."EncounterApiController@encounters");
		Api::route('encounter/user/like', $nspace."EncounterApiController@doLike");
		Api::route('matches', $nspace."EncounterApiController@matches");
		Api::route('likes', $nspace."EncounterApiController@likes");
		Api::route('mylikes', $nspace."EncounterApiController@myLikes");
	}
}