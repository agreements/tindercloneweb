<?php
use App\Components\PluginAbstract;
use App\Components\Plugin;
use App\Components\Theme;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Admin\UtilityRepository;
use App\Repositories\LandingPageRepository;
use Illuminate\Support\Facades\Blade;

class LandingPagesPlugin extends PluginAbstract {

	public function website()
	{
		return 'datingframework.com';
	}

	public function author()
	{
		return 'DatingFramework';
	}

	public function description() {

		return 'This is landing pages plugin helps admin to choose landing pages.';
	}

	public function version() { 
	
		return '1.0.0'; 
	}
	
	
	public function isCore()
	{
		return true;
	}
	
	public function hooks() {
		

		Plugin::add_hook('composing:*', function ($view) {

			$custom_landing = UtilityRepository::get_setting('custom_landing_page');

			if ($custom_landing != '' && $custom_landing != '-1') {

				if(!(strpos($view->getName(), 'auth.registration') == false)) {

					$view->setPath($this->getRegistrationPath($custom_landing));
					$view->with('only_social_logins', UtilityRepository::get_setting('only_social_logins'));

				} else if(!(strpos($view->getName(), 'auth.signin') == false)) {
					
					$view->setPath($this->getSininPath($custom_landing));
					$view->with('only_social_logins', UtilityRepository::get_setting('only_social_logins'));
					$view->with('show_forgot_password_link', Plugin::isPluginActivated('EmailPlugin'));

				}

			}
			
		});


		Theme::hook('admin_plugin_menu', function () {

			$url = url('/admin/plugins/landing-pages-setting');
			$link = "<li><a href=\"{$url}\"><i class=\"fa fa-circle-o\"></i>Landing Pages</a></li>";
			return $link;
		});	
		


		//blade methods
		$custom_landing = UtilityRepository::get_setting('custom_landing_page');
		$storage_content_path = base_path('storage/LandingPages');

		Blade::directive('LandingPageImage', function($expression) 
			use($custom_landing, $storage_content_path) {
            
            $expression = substr($expression, 2, strlen($expression) - 4);
            
            $instance = LandingPageRepository::landingPageInstance($custom_landing);
            $language_file = $instance->languageFile();
            $image_array_file = $storage_content_path. "/{$language_file}_images.stub";

            $image_sections_from_obj = $instance->imageSections();

            $imageSections = file_exists($image_array_file) ? unserialize(file_get_contents($image_array_file)) : $image_sections_from_obj;
            $key = isset($imageSections[$expression]['url']) ? $imageSections[$expression]['url'] : $image_sections_from_obj[$expression]['url'];
            $url = url($key);
            
            return $url;
        });


        Blade::directive('LandingPageUrl', function($expression) 
			use($custom_landing, $storage_content_path) {
            
            $expression = substr($expression, 2, strlen($expression) - 4);
            
            $instance = LandingPageRepository::landingPageInstance($custom_landing);
            $language_file = $instance->languageFile();
            $link_array_file = $storage_content_path. "/{$language_file}_links.stub";

            $link_sections_from_obj = $instance->linkSections();

            $linkSections = file_exists($link_array_file) ? unserialize(file_get_contents($link_array_file)) : $link_sections_from_obj;
            $key = isset($linkSections[$expression]['url']) ? $linkSections[$expression]['url'] : $link_sections_from_obj[$expression]['url'];
            $url = url($key);
            
            return $url;
        });


        //video background
        Theme::hook('top-header', function(){

        	$landing_page_video_mode = UtilityRepository::get_setting('landing_page_video_mode');
        	if ($landing_page_video_mode == 'true') {

        		$landing_page_video_poster_url = UtilityRepository::get_setting("landing_page_video_poster_url");
            	$landing_page_video_url  = UtilityRepository::get_setting("landing_page_video_url");

        		
        		$script = <<<VIDEO_SCRIPT
<style>
.fullscreen-bg {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    overflow: hidden;
    z-index: -100;
}

.fullscreen-bg__video {
    position: absolute;
    top: 50%;
    left: 50%;
    width: auto;
    height: auto;
    min-width: 100%;
    min-height: 100%;
    -webkit-transform: translate(-50%, -50%);
       -moz-transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
	    transform: translate(-50%, -50%);
}


.bg_size {
	z-index:1;
}
.logo_col_margin {
	margin-top:0px;
}
.header-bg {
	position:relative;
}
</style>
<script>
$(document).ready(function(){
	$(".header-bg").removeClass('z_index-change');
	$(".bg_size").css('background', '');
});	
</script>
<div class="fullscreen-bg">
    <video loop muted autoplay poster="$landing_page_video_poster_url" class="fullscreen-bg__video">
        <source src="$landing_page_video_url" type="video/mp4">
    </video>
</div>
VIDEO_SCRIPT;

				return $script;
			}

        });

		
	}	

	public function autoload() {

		return array(
			Plugin::path('LandingPagesPlugin/controllers'),
			Plugin::path('LandingPagesPlugin/repositories'),
		);

	}

	public function routes() {
		
		Route::group(['middleware' => 'admin'], function () {

			Route::get('/admin/plugins/landing-pages-setting', 'App\Http\Controllers\LandingPagesController@showSetting');
			Route::post('/admin/plugins/landing-pages-setting/save', 'App\Http\Controllers\LandingPagesController@saveSetting');

			Route::get('/admin/plugins/landing-pages-setting/edit', 'App\Http\Controllers\LandingPagesController@editLandingPage');
			Route::post('/admin/plugins/landing-pages-setting/content-section/save', 'App\Http\Controllers\LandingPagesController@contentSectionSave');
			Route::post('/admin/plugins/landing-pages-setting/image-section/save', 'App\Http\Controllers\LandingPagesController@imageSectionSave');
			Route::post('/admin/plugins/landing-pages-setting/image-section/upload', 'App\Http\Controllers\LandingPagesController@imageSectionUploadImage');
			Route::post('/admin/plugins/landing-pages-setting/link-section/save', 'App\Http\Controllers\LandingPagesController@linkSectionSave');
			

			Route::post('/admin/plugins/landing-pages-setting/viedeo-mode/save', 'App\Http\Controllers\LandingPagesController@saveVideoMode');
			Route::post('/admin/plugins/landing-pages-setting/viedeo-settings/save', 'App\Http\Controllers\LandingPagesController@saveVideoSettings');
			Route::post('/admin/plugins/landing-pages-setting/viedeo-settings/upload-poster-image', 'App\Http\Controllers\LandingPagesController@uploadPosterImage');
			Route::post('/admin/plugins/landing-pages-setting/viedeo-settings/upload-video', 'App\Http\Controllers\LandingPagesController@uploadVideo');

		});
		
	}


	public function getSininPath ($custom_landing) {

		return app_path()."/Plugins/LandingPagesPlugin/views/landing_pages/{$custom_landing}/signin.blade.php";
	}

	public function getRegistrationPath ($custom_landing) {

		return app_path()."/Plugins/LandingPagesPlugin/views/landing_pages/{$custom_landing}/registration.blade.php";
	}
}