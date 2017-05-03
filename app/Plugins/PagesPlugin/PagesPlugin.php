<?php
use App\Components\PluginAbstract;
use App\Events\Event;
use App\Components\Plugin;
use App\Components\Theme;
use App\Repositories\HomeRepository;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Pages;
use App\Models\Settings;
use App\Models\Notifications;
use App\Models\ChatMessage;

class PagesPlugin extends PluginAbstract
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
		return 'This is the pages plugin';
	}

	public function version()
	{
		return '1.0.0';
	}


	public function hooks()
	{

		Theme::hook('admin_plugin_menu', function(){

			$url = url('/admin/pages');
			$html = '<li><a href="' . $url . '"><i class="fa fa-circle-o"></i>'. trans_choice('admin.page',0).' '.trans('admin.setting').'</a></li>';

			return $html;
		});	

		Plugin::add_hook('interest_added', function($interest){

        	$inter = strtolower($interest->interest);
        	// $inter = preg_replace(' ', '-', $inter);
        	$inter = str_replace(' ','-', $inter);
        	$interest->slug = $inter;
        	$interest->save();

        	$this->addInSitemap(url('interests/'.$interest->slug),date('Y-m-d h:i:sa'));


		});

		
	}	

	public function addInSitemap($url,$date)
    {
        $path = base_path().'/sitemap.xml';
		$content = file($path);
		$inserted = "<url>\n<loc>".$url."</loc>\n<lastmod>".$date."</lastmod>\n<changefreq>daily</changefreq>\n<priority>1.0</priority>\n</url>\n";
		array_splice($content, 3, 0, $inserted);
        file_put_contents($path, $content);
    }

	public function autoload()
	{

		return array(
			Plugin::path('PagesPlugin/controllers'),
			Plugin::path('PagesPlugin/repositories'),
			Plugin::path('PagesPlugin/models'),

		);

	}

	public function routes()
	{
		Route::group(['middleware' => 'admin'], function(){

			Route::get('/admin/pages', 'App\Http\Controllers\PagesController@show_settings');
			Route::post('/admin/pages/update', 'App\Http\Controllers\PagesController@updatePage');
			Route::post('/admin/pages/delete', 'App\Http\Controllers\PagesController@deletePage');
			Route::post('/admin/pages/create', 'App\Http\Controllers\PagesController@save_settings');
			Route::post('admin/page/activate', 'App\Http\Controllers\PagesController@activatePage');
			Route::post('admin/page/deactivate', 'App\Http\Controllers\PagesController@deactivatePage');

			//route for pages plugin upload image through ckeditor
			Route::post('/pages/upload/image', 'App\Http\Controllers\PagesController@uploadPageImage'); 
		});

		//Route::get('/pages/{page}', 'App\Http\Controllers\PagesController@page');
		Route::get('/interests/{slug}', 'App\Http\Controllers\PagesController@interests');
		Route::get('/peoplenearby/{city_route}', 'App\Http\Controllers\PagesController@peoplenearby');
		Route::post('/peoplenearby/{city_route}', 'App\Http\Controllers\PagesController@filter');
		


		$pages = Pages::all();
		foreach ($pages as $page) {

			Route::get($page->route, function() use ($page){
		
       		 	return Theme::view('plugin.PagesPlugin.page', ['body' => $page->body]);

			});

		}


	}
}