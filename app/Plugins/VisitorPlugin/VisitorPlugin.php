<?php
use App\Components\PluginAbstract;
use App\Events\Event;
use App\Components\Plugin;
use App\Components\Theme;
use App\Components\Api;
use App\Components\Email;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Notifications;
use App\Models\Settings;

class VisitorPlugin extends PluginAbstract
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
		return 'This plugin keeps track of member profile visitors.';
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
			$visitors = Notifications::get_count('visitor');
			if($visitors == 0)
				$visitors='';
			$url = url('visit');            
        	return array(array("title" => trans('app.visitors'), "notification_type" => 'visitor' ,  "symname" => "visibility","count" => $visitors , "priority" => 4 , "url" => $url, "attributes" =>array("class"=>"material-icons pull-left material-icon-custom-styling")));
		});

		Notifications::add_formatter("visitor", function($data){
			$data->entity = User::find($data->entity_id);
			return $data;
   		});


   		Theme::hook('admin_email_content', function(){
            return array(
                array(
                    'heading' => 'Visitor Mail Settings',
                    'title' => 'Visitor',
                    'mailbodykey' => 'visitormailbody',
                    'mailsubjectkey' => 'visitormailsubject',
                    'email_type' => 'visitor',
                ),

            );
        });
	}	

	public function autoload()
	{

		return array(
			Plugin::path('VisitorPlugin/controllers'),
			Plugin::path('VisitorPlugin/repositories'),
			Plugin::path('VisitorPlugin/models'),
		);

	}

	public function routes()
	{
		Route::group(['middleware' => 'auth'], function () {
			Route::get('/visit', 'App\Http\Controllers\VisitorController@getVisitors');
		});

	
		$nspace ="App\\Http\\Controllers\\";
		Api::route('visitors', $nspace."VisitorApiController@visitors");
	
	}	
}