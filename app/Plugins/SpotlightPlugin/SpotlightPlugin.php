<?php
use App\Components\PluginAbstract;
use App\Events\Event;
use App\Components\Plugin;
use App\Components\Theme;
use App\Repositories\CreditRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\Admin\UtilityRepository;
use App\Repositories\SpotlightRepository;
use Illuminate\Support\Facades\Auth;
use App\Components\Api;

class SpotlightPlugin extends PluginAbstract
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
		return 'This is the Spotlight Plugin.';
	}

	public function version()
	{
		return '1.0.0';
	}

	

	public function isCore()
	{
		return true;
	}

	public function autoload()
	{

		return array(
			Plugin::path('SpotlightPlugin/controllers'),
			Plugin::path('SpotlightPlugin/repositories'),
			Plugin::path('SpotlightPlugin/models'),
		);

	}

	public function hooks()
	{
		 $credits = app("App\Repositories\CreditRepository");
		 $spotRepo = app('App\Repositories\SpotlightRepository');
			
			
		Theme::hook('spot', function() use ($credits,$spotRepo){
			
			$spots = $spotRepo->getSpotUsers();
			$spotCredits = $spotRepo->getSpotCredits();

			
			return Theme::view('plugin.SpotlightPlugin.spot_view', 
						array(	
								'spots' => $spots,
								'spotCredits' => $spotCredits,
								'logUser' => Auth::user(),
							)
					);


		});

		Theme::hook('credits-feature',function() use ($spotRepo){
			
			$spotlight_screenshot = $spotRepo->getSpotlightScreenshotUrl();

			return Theme::view('plugin.SpotlightPlugin.credits_spot', [
				'spotlight_screenshot' => $spotlight_screenshot, 
			]);
		});			
	}

	public function routes()
	{
		Route::post('/user/spotlight/add', 'App\Http\Controllers\SpotlightController@addToSpotlight');
		Api::route('spotlight', 'App\Http\Controllers\SpotlightApiController@spotlight');
		Api::route('spotlight/add', 'App\Http\Controllers\SpotlightApiController@addToSpotlight');
	}

	
}