<?php

namespace App\Components;

//use of Plugins model
use App\Components\Plugin;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/* This class is used to declare mobile api routes
   All the routes are by default post 
   Routes are by default CSRF Token bypassed
 */

class Api {

	protected static $api_route_prefix = "api";

	public static function route ($route, $controller, $middleware = null) {

		$prefix = self::$api_route_prefix;
		$route = rtrim($prefix, '/').'/'.ltrim($route, '/');
        $route = trim($route, '/');
        
		Plugin::removeCSRFToken($route);

		if (!$middleware) {
			$middleware = ['api'];
		} else if (is_string($middleware) && strcmp('no-api', $middleware) == 0) {
			$middleware = [];
		}

		Route::post($route, $controller)->middleware($middleware);
	}



	public static function response ($arr) {
		
		if (!is_array($arr)) { 
			return response()->json([
				"status" => "error",
				"error_data" => [
					"error_text" => "Some error in api response"
				]
			]); 
		}

		$res_arr = Plugin::fire("api_global_data");

		$global_data = static::globalData();

		foreach ($res_arr as $data) {
			if (is_array($data)) {
				$global_data = array_merge($global_data, $data);
			}
		}

		$arr["global_data"] = $global_data;
		return response()->json($arr);
	}


	protected static function globalData () {
		return ["currency" => ""];
	}


}
