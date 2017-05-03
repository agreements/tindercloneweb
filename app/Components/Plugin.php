<?php

namespace App\Components;

//this class is used to load plugin classes
use Illuminate\Support\ClassLoader;

//use of Plugins model
use App\Models\Plugins;

//use of storage path
use Storage;

//used to register and fire event
use Event;


//This Plugin class in the main class 
//that does every thing for our plugin system

class Plugin
{
	//this variable is used to store Laravel's $event object
	protected static $events = array();


	//this method is used to get and set Laravel's $event object
	//this method is being called from EventServiceProvider boot method
	public static function setEvent($event)
	{
		array_push(self::$events, $event); 
		
	}


	//this function fires action hook event
	public static function fire($eventname, $arr = null)
	{
		$return_values_from_all_funcs = Event::fire($eventname, $arr);

		return array_filter($return_values_from_all_funcs, function ($var) {
			return !is_null($var);
		});
	}



	//this variable is used to store hoocks
	protected static $hooks = array();

	//this method is used to register an hook listeners
	public static function hook($hookName, $func = null)
	{
		if(isset(self::$hooks[$hookName]))
			array_push( self::$hooks[$hookName], $func);
		else
		{
			self::$hooks[$hookName] = array(); 
			array_push( self::$hooks[$hookName], $func);
		} 
	}




	//this function creates new hook using Laravel's event
	public static function add_hook($hookName, $func =null)
	{
		self::$events[0]->listen($hookName, $func);

	}


	//this method is used to call all hook listeners functions
	public static function apply_hooks($hookName, $data = null)
	{
		if(isset(self::$hooks[$hookName]))
		{
			$arrFuncs = self::$hooks[$hookName];

			foreach ($arrFuncs as $func) 
			{
				call_user_func_array( $func , array($data) );
			}
		}
	}


	//This function returns absolute path for given relative path for a plugin
	public static function path($path = null)
	{
		return app_path() . '/Plugins/'. $path;
	}

	

	//this funcitons registers all activated plugins
	//calls every plugins hooks funciton 
	public static function register () {
		
		
		$activatedPluginsFromFile = self::getActivatedPluginsFromFile();
		$plugin_classes = array();
		
		if(count($activatedPluginsFromFile) > 0){
			
			foreach ($activatedPluginsFromFile as $plugin) {

			$pluginPath = app_path() . "/Plugins/$plugin";
 			$plugin_class_file = "{$pluginPath}/$plugin.php";
 
 			require_once $plugin_class_file;
			$plugin_class = "\\$plugin";
			array_push($plugin_classes, $plugin_class);
			
		}
			
			
		}
		else{ 
		
		$activatedPlugins = self::getActivatedPlugins();


		
		//registering plugins by calling hook functions of each plugin 
		foreach ($activatedPlugins as $plugin) {

			$pluginPath = app_path() . "/Plugins/{$plugin->name}";
 			$plugin_class_file = "{$pluginPath}/{$plugin->name}.php";
 
 			require_once $plugin_class_file;
			$plugin_class = "\\{$plugin->name}";
			array_push($plugin_classes, $plugin_class);
			
		}
		
		}
		
		
		foreach($plugin_classes as $plugin_class){
			
			$plugin_class_object = new $plugin_class;

			$dirs = $plugin_class_object->autoload();
			
			foreach ($dirs as $directory) {
 				
 				if(!file_exists($directory)) continue;
 	
  
 				$files = scandir ($directory);
 				
 				foreach ($files as $file) {
  
 					$file_abs_path = "{$directory}/{$file}";
 
 					if (is_file ($file_abs_path)) { require_once $file_abs_path; }
 				}
 				
 			}


			$plugin_class_object->routes();
			$plugin_class_object->hooks(); 
		}
		
		
		
		

	}
	
	
	public static function  pluginsConfigPath(){
		return config_path('plugins.php');
	}
	
	
	public static function syncWithConfig(){
		$plugins = self::getActivatedPlugins();
		
		$arr = array();
		foreach($plugins as $plugin){
			array_push($arr, $plugin->name);
		}
		
		$arrayString = var_export($arr, true);
        $arrayString = "<?php return \n {$arrayString};"; 
        file_put_contents(self::pluginsConfigPath(), $arrayString, LOCK_EX);
	}


	public static function isPluginActivated($name)
	{
		$plugins = config("plugins");
		$plugins = is_null($plugins) ? [] : $plugins;
		return in_array($name, $plugins);
	}

	
	// public static function register () {
		
	// 	spl_autoload_register('\App\Components\Plugin::autoload_class_multiple_directory');

	// 	$activatedPlugins = self::getActivatedPlugins ();

	// 	$base_plugin_path = app_path() . "/Plugins";

	// 	//registering plugins by calling hook functions of each plugin 
	// 	foreach ($activatedPlugins as $plugin) {

	// 		array_push(self::$autoload_dirs, "{$base_plugin_path}/{$plugin->name}");

	// 		$plugin_class = "\\{$plugin->name}";
	// 		$plugin_class_object = new $plugin_class;

	// 		$dirs = $plugin_class_object->autoload();
	// 		self::$autoload_dirs = array_merge(self::$autoload_dirs, $dirs);
	
	// 		$plugin_class_object->routes();
	// 		$plugin_class_object->hooks(); 
	// 	}

	// }



	// protected static $autoload_dirs = [];

	// protected static function autoload_class_multiple_directory ($class_name) {

	// 	$parts = explode('\\', $class_name);

 //    	foreach(self::$autoload_dirs as $path) {

 //        	$file = sprintf('%s/%s.php', $path, end($parts));
        	
 //        	if(is_file($file)) {
 //            	require_once $file;
 //        	} 

 //    	}
	// }






	//plugin view that renders views from only plugin views folder
	public static function view($viewName, $data = array()) {
		
		$arr = explode('/', $viewName);		
		return view($arr[0] . '.views.' . $arr[1], $data);
	}



	// this function returns all activated plugins from database
	public static function getActivatedPlugins () {

		$plugins = Plugins::where('isactivated', 'activated')->get();
		return $plugins;
	}
	
	
	public static function getActivatedPluginsFromFile(){
		
		return config("plugins");
	}

	//this function generates the specific assets based on plugin
	public static function asset($asset = '') {
		return asset('/plugins/'. $asset);
	}




	protected static $csrf_except_url = [];
	
	public static function removeCSRFToken ($route) {
		if (is_string($route)) {
			array_push(self::$csrf_except_url, $route);
		} else if (is_array($route)) {
			self::$csrf_except_url = array_merge(self::$csrf_except_url, $route);
		}
	}

	public static function getCSRFRemoveRoutes () {
		return self::$csrf_except_url;
	}
}
