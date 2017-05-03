<?php
use App\Components\PluginAbstract;
use App\Events\Event;
use App\Components\Plugin;
use App\Components\Theme;
use App\Models\Settings;

class SEOPlugin extends PluginAbstract {

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
		return 'SEO Plugin.';
	}

	public function version()
	{
		return '1.0.0';
	}


	public function hooks()
	{

		//adding admin hook to left menu
		Theme::hook('admin_plugin_menu', function(){

			$url = url('/admin/seoSettings');
			$html = '<li><a href="'. $url .'"><i class="fa fa-circle-o"></i> SEO Settings</a></li>';

			return $html;
		});	



		//adding meta tags
		
		Theme::hook ('metaTags', function () {

			$meta_description = Settings::_get('meta_description');

			return '<meta name="description" content="'.$meta_description.'">';	
      
		});		



		Theme::hook('metaTags', function(){

			$meta_keywords = Settings::_get('meta_keywords');

			return '<meta name="keywords" content="'.$meta_keywords.'">';

		});


		Theme::hook('metaTags', function(){

			$meta_block = Settings::_get('meta_block');

			if( $meta_block == 'yes') {

				return '<meta name="keywords" content="NOINDEX, NOFOLLOW">';	
			}
			
			
		});

	}	


	public function autoload()
	{
		return array(

			Plugin::path('SEOPlugin/controllers'),
		);
	}

	public function routes()
	{
		
		Route::group(['middleware' => 'admin'], function(){

			//google admin settings view route
			Route::get('/admin/seoSettings', 'App\Http\Controllers\SEOController@showSettings');
			Route::post('/admin/seoSettings', 'App\Http\Controllers\SEOController@saveSettngs');

		});
	}

}
