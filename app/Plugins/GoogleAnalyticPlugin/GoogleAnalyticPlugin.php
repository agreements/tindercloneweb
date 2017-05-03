<?php
use App\Components\PluginAbstract;
use App\Events\Event;
use App\Components\Plugin;
use App\Components\Theme;
use App\Models\Settings;
use App\Repositories\Admin\UtilityRepository;

class GoogleAnalyticPlugin extends PluginAbstract
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
		return 'This is the Google Anatytics Plugin.';
	}

	public function version()
	{
		return '1.0.0';
	}

	public function hooks()
	{

		//adding admin hook to left menu
		Theme::hook('admin_plugin_menu', function() {

			$url = url('/admin/googleAnalyticSettings');
			$link = '<li><a href="' . $url . '"><i class="fa fa-circle-o"></i> '.trans('admin.google_analytic_settings').'</a></li>';

			return $link;
		});	

		//adding scriptTags
		Theme::hook('spot', function(){

			$google_analytics_key = UtilityRepository::get_setting('google_accountNumber');

			$google_analytics_script = 

<<<GOOGLE_ANALYTICS_CODE
<script>
				(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js','ga');  ga('create','{$google_analytics_key}', 'auto');ga('send', 'pageview');
</script>
GOOGLE_ANALYTICS_CODE;
			
			return $google_analytics_script;
		});	
		Theme::hook('top-header', function(){

			$google_analytics_key = UtilityRepository::get_setting('google_accountNumber');

			$google_analytics_script = 

<<<GOOGLE_ANALYTICS_CODE
	<script>
				(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js','ga');  ga('create','{$google_analytics_key}', 'auto');ga('send', 'pageview');
	</script>

GOOGLE_ANALYTICS_CODE;
			
			return $google_analytics_script;
		});	

	}	


	public function autoload()
	{
		return array(
			Plugin::path('GoogleAnalyticPlugin/controllers'),
		);
	}

	public function routes()
	{
		
		Route::group(['middleware' => 'admin'], function(){

			//google admin settings view route
			Route::get('/admin/googleAnalyticSettings', 'App\Http\Controllers\GoogleAnalyticController@showSettings');
			Route::post('/admin/googleAnalyticSettings', 'App\Http\Controllers\GoogleAnalyticController@saveSettngs');

		});
	}

}
