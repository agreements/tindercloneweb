<?php

namespace App\Components;

use App\Models\Themes;
use Storage;
use App\Models\Plugins;
use App\Models\Ads;
use App\Models\AdsActive;
use App\Models\Settings;

use App\Components\Presenter;

class Theme
{

	//content hooks implementation
	protected static $hook_list = array(
		'scripts' => array(),
		'scriptTags' => array(),
		'styles' => array(),
		'spot' => array(),
		'main_menu' => array(),
		'login' => array(),
		'admin_plugin_menu' => array(),
	);

	protected static $modifier = array();

	public static function hook($hookName, $func)
	{
		if(isset(self::$hook_list[$hookName]))
			array_push(self::$hook_list[$hookName], $func);
		else
		{
			self::$hook_list[$hookName] = array();
			array_push(self::$hook_list[$hookName], $func);
		}
	}

	public static function render_modifier($hookName,$func)
	{
		if(isset(self::$modifier[$hookName]))
			array_push(self::$modifier[$hookName], $func);
		else
		{
			self::$modifier[$hookName] = array();
			array_push(self::$modifier[$hookName], $func);
		}
	}

	public static function runScripts($funcs)
	{
		$scripts = array();
		foreach($funcs as $func)
		{
			$scripts = $func();
			foreach ($scripts as $value) 
			{
				echo '<script src = "'. $value.'"></script>';	
			}
		}
	}


	public static function runCss($funcs)
	{
		$css = array();
		foreach($funcs as $func)
		{
			$css = $func();
			foreach ($css as $value) 
			{
				echo '<link rel = "stylesheet" href = "'. $value.'">';	
			}
		}
	}

	public static function runScriptTags($funcs)
	{
		$script = array();
		foreach($funcs as $func)
		{
			array_push($script,$func());
			foreach ($script as $value) 
			{
				echo '<script>'. $value.'</script>';	
			}
		}
	}


	public static function render($hookName, $args = array())
	{
		if(isset(self::$hook_list[$hookName]))
		{

			if($hookName == 'scripts')
			{
				self::runScripts(self::$hook_list[$hookName]);
				return ;
			}
			elseif($hookName == 'styles')
			{
				self::runCss(self::$hook_list[$hookName]);
				return ;
			}
			elseif($hookName == 'scriptTags')
			{
				self::runScriptTags(self::$hook_list[$hookName]);
				return ;
			}
			

			if( isset(self::$modifier[$hookName]) )
			{
				
				foreach(self::$modifier[$hookName] as $modFunc)
				{
					$main_markup = '';

					$arr = array();
					foreach(self::$hook_list[$hookName] as $contFunc)
					{

						$data = $contFunc();

						if (is_array($data)) {

							foreach ($data as $ar) {
							
								array_push($arr, $ar);	
							}	
						}
						
						
						
					}
					
					$var = $modFunc($arr);
					if(is_object($var))
						echo $var->render();				
					else
						echo  $var;
					
				}
			}
			else
			{
				foreach(self::$hook_list[$hookName] as $func)
				{
					$var = $func($args);
					if(is_object($var))
						echo $var->render();				
					else
						echo $var;
				}
			}
			
		}
	}


	public static function get_activated_theme_by_role ($role) {
	
		//$theme = Themes::where('isactivated', '=', 'activated')->where('role','=', $role)->first();
		//return ($theme) ? $theme->name : ''; 
		
		$theme = unserialize(config("themes.".$role));
	 	return ($theme) ? $theme->name : ''; 	    
	}


	public static function get_plugin ($plugin_name) {

		return $plugin = Plugins::where('name', '=', $plugin_name)->first();
	}


	public static function generate_resources_view_path ($theme_name, $sub_dir, $view_file) {

		if ($sub_dir == ''){

			return base_path()."/resources/views/themes/{$theme_name}/views/{$view_file}.blade.php";
		}
		else {

			return base_path()."/resources/views/themes/{$theme_name}/views/{$sub_dir}/{$view_file}.blade.php";
		} 
			
	}


	//this function loads view for the current activated theme
	public static function view($viewName, $data = array())
	{


		$view_param = explode('.', $viewName);

		$parent_theme_name = self::get_activated_theme_by_role('parent');
		$child_theme_name  = self::get_activated_theme_by_role('child');

		if ($parent_theme_name == '') {

			return 'No Theme activated Currently';
		
		} else {
			
			$presenter = Presenter::view($view_param, $data);
			
			if($presenter["custom"] == true) {
				
				return view($presenter["view"],$presenter["data"]);
			} else if(array_key_exists("view",$presenter)) {
				$data = $presenter["data"];
			}
		
			if ($view_param[0] == 'plugin') {
	
				//if plugin.plugin_name.viewname given
				$plugin = self::get_plugin($view_param[1]);
	
				$child_theme_view_path = self::generate_resources_view_path($child_theme_name, $view_param[1], $view_param[2]);
	
				if ($child_theme_name && file_exists($child_theme_view_path)) {
	
					return view("themes/{$child_theme_name}/views/{$view_param[1]}/{$view_param[2]}", $data);
	
				} else if (file_exists(self::generate_resources_view_path($parent_theme_name, $view_param[1], $view_param[2]))) {
	
					return view( "/themes/{$parent_theme_name}/views/{$view_param[1]}/{$view_param[2]}", $data);
				
				} else if ($plugin->isactivated == "activated") {
	
					return view("{$view_param[1]}/views/{$view_param[2]}", $data);
				}				
						
	
			} else {
	
				//if only
				if ($child_theme_name) {
	
					if (isset($view_param[1]) && file_exists( self::generate_resources_view_path($child_theme_name, $view_param[0], $view_param[1]) ))
					{
						return view( "/themes/{$child_theme_name}/views/{$view_param[0]}/{$view_param[1]}", $data);
					
					} else if(file_exists( self::generate_resources_view_path($child_theme_name, '', $view_param[0]) )) {
	
						return view( "/themes/{$child_theme_name}/views/{$view_param[0]}", $data);
					}
						
				}
	
				return view("themes.{$parent_theme_name}.views.{$viewName}" ,$data);
	
	
			}
		}
	
	}

	//this function generates theme specific assets based on activated theme
	public static function asset($asset = '')
	{

		$parent_theme = self::get_activated_theme_by_role('parent');
		$child_theme = self::get_activated_theme_by_role('child');
		
		$path = public_path("themes/{$child_theme}/{$asset}");

		if($child_theme && file_exists($path)) {
			
			return asset("themes/{$child_theme}/{$asset}");
			
		} else {

			return asset("themes/{$parent_theme}/{$asset}");
		}
            	
	}

	public static function layout ($str = null) {

		$parent_theme = self::get_activated_theme_by_role('parent');
		
		if ($parent_theme) {

			return !is_null($str) 
					? "themes.{$parent_theme}.views.layouts.{$str}"
					: "themes.{$parent_theme}.views.layouts.master";
			
		}
		
	}
}