<?php
use App\Components\PluginAbstract;
use App\Components\Plugin;
use App\Components\Theme;
use App\Repositories\Admin\UtilityRepository;

class GoogleMapPlugin extends PluginAbstract
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
		return 'This is the Google Map Plugin that helps to get location in registration page';
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
		
		//adding admin hook to left menu
		Theme::hook('admin_plugin_menu', function(){
			$url = url('/admin/plugin/google-map');
			$html = '<li><a href="' . $url . '"><i class="fa fa-circle-o"></i>'.trans('GoogleMapPlugin.menu_text').'</a></li>';

			return $html;
		});


		$google_map_key = UtilityRepository::get_setting('google_map_api_key');
		
		view()->composer('*', function ($view) use ($google_map_key){
			view()->share('google_map_key', $google_map_key);
		});



		Theme::hook('top-header', function() use ($google_map_key) {
			
			$html = <<<MAP_SCRIPT
<script>
function initMapGoogle() { 

        google.maps.event.addDomListener(window, 'load', function () {
            var places = new google.maps.places.Autocomplete(document.getElementById('txtPlaces1'));
            google.maps.event.addListener(places, 'place_changed', function () {
                var place = places.getPlace();

                var address = place.formatted_address;
                var latitude = place.geometry.location.lat();
                var longitude = place.geometry.location.lng();


             for (var i=0; i<place.address_components.length; i++) {
            for (var b=0;b<place.address_components[i].types.length;b++) {


                 if (place.address_components[i].types[b] == "country") {
                    //this is the object you are looking for
                    var country= place.address_components[i];

                   
                }
                if (place.address_components[i].types[b] == "locality") {
                    //this is the object you are looking for
                    var city= place.address_components[i].long_name;

                   
                }
            }
        }
        //city data
      
                var country = country.long_name;
                document.getElementById('lat').value = latitude;
                document.getElementById('lng').value = longitude;
                document.getElementById('country').value = country;
                document.getElementById('city').value = city;
             
                
            });
        });

}
</script>
MAP_SCRIPT;

			if ($google_map_key) {
				$html .= '<script src="https://maps.googleapis.com/maps/api/js?key='.$google_map_key.'&signed_in=true&libraries=places&callback=initMapGoogle" async defer></script>';
			} else {
				$html .= '<script src="https://maps.googleapis.com/maps/api/js?&signed_in=true&libraries=places&callback=initMapGoogle" async defer></script>';
			}

        	return $html;
		});


	}	

	public function autoload()
	{
		return array(
			Plugin::path('GoogleMapPlugin/controllers'),
			Plugin::path('GoogleMapPlugin/Repositories'),
		);
	}

	public function routes()
	{
		Route::group(['middleware' => 'admin'], function(){
			Route::get('/admin/plugin/google-map', 'App\Http\Controllers\GoogleMapPluginController@showSettings');
			Route::post('/admin/plugin/google-map/save', 'App\Http\Controllers\GoogleMapPluginController@saveSetting');
		});
	}
}