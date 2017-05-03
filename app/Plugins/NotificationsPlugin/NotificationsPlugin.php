<?php
use App\Components\PluginAbstract;
use App\Events\Event;
use App\Components\Plugin;
use App\Components\Theme;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Settings;
use App\Repositories\NotificationsRepository;
use App\Models\Notifications;
use App\Components\Api;

class NotificationsPlugin extends PluginAbstract
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
		return 'This is the Notification Plugin';
	}

	public function version()
	{
		return '1.0.0';
	}


	public function hooks()
	{
		

		//central notifications hook
		Theme::hook('central_notifications', function(){

			//retriving auth user
			$auth_user = Auth::user();

			//retriving last 5 central notifications
			$notifications = NotificationsRepository::getCentralNotifications($auth_user->id, 5, $notif_count);

			//gathering all notification items and concatinating html contents
			$notification_items_content = "";
			
			foreach ($notifications as $notification) {

				$content = Plugin::fire($notification->type, ["notification" => $notification]);
				if(isset($content[0])) {
					$notification_items_content .= $content[0]->render();	
				}
				
			}
			
			return Theme::view('plugin.NotificationsPlugin.central_notifications_container', [
				"notifications" => $notifications,
				"notification_items_content" => $notification_items_content,
				"notif_count" => $notif_count,
				"last_notification_timestamp" => $notifications->last() != null ? $notifications->last()->created_at : ""
			]);

		});

		

		Plugin::add_hook('insert_notification', function($from_user, $to_user, $notificaion_type, $entity_id, $notification_hook_type = 'left_menu'){

	        $notif = new Notifications;
			$notif->from_user = $from_user;
			$notif->to_user   = $to_user;
			$notif->type      = $notificaion_type;
			$notif->status    = "unseen";
			$notif->entity_id = $entity_id;
			$notif->notification_hook_type = $notification_hook_type;

	        $notif->save();	    

	        return $notif;
		});







		Theme::hook('admin_plugin_menu', function(){

			$url = url('plugins/settings/push-notification');
			$trans_text = trans('notification.push_notification_setting');
			$html = "<li>
						<a href=\"{$url}\">
							<i class=\"fa fa-circle-o\"></i>{$trans_text}
						</a>
					</li>";

			return $html;
		});







	}	

	public function isCore()
	{
		return true;
	}

	public function autoload()
	{

		return array(
			Plugin::path('NotificationsPlugin/controllers'),
			Plugin::path('NotificationsPlugin/repositories'),
			Plugin::path('NotificationsPlugin/models'),

		);

	}

	public function routes()
	{
		Route::group(['middleware' => 'auth'], function(){

			Route::get('/poll_notifications/{count}', 'App\Http\Controllers\NotificationsController@poll');
			Route::get('/get_notifications', 'App\Http\Controllers\NotificationsController@getNotifications');


			/* central notification routes */
			Route::get('notifications/central', 'App\Http\Controllers\NotificationsController@getCentralNotifications');
			// Route::post('notifications/mark-seen', 'App\Http\Controllers\NotificationsController@markSeenNotification');
			// Route::post('notifications/mark-unseen', 'App\Http\Controllers\NotificationsController@markUnseenNotification');
			Route::post('notifications/central/mark-seen-all', 'App\Http\Controllers\NotificationsController@markSeenAllCentralNotifications');
			// Route::post('notifications/mark-unseen-all', 'App\Http\Controllers\NotificationsController@markUnseenAllNotification');


		});

		$namespace = "App\\Http\\Controllers\\";

		Route::group(['middleware' => 'admin'], function() use($namespace){
			
			Route::get('plugins/settings/push-notification', "{$namespace}NotificationsController@pushNotification");
			Route::post('plugins/settings/push-notification', "{$namespace}NotificationsController@savePushNotificationSetting");
		});
		


		/* Mobile apis */
		Api::route('notifications/push/register-device', $namespace."NotificationsController@registerDevice");
		
	}
}