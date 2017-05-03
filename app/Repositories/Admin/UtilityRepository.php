<?php

namespace App\Repositories\Admin;

use Hash;
use DB;
use Artisan;

use App\Models\Admin;
use App\Models\Settings;

class UtilityRepository {

	public static function session_set ($key, $value) {
		session([$key => $value]);
	}

	public static function session_get ($key) {
		return session($key);
	}

	public static function session_forget($key) {
		session()->forget($key);
	}


	public static function get_setting ($key) {
		return Settings::_get($key);
	}

	public static function set_setting ($key, $value) {
		return Settings::set($key, $value);
	}

	 public static function create_supported_image_extension ($image_type) {

        $image_types = [
			"image/jpg"  => ".jpg",
			"image/jpeg" => ".jpg",
			"image/png"  => ".png",
      "image/bmp"  => ".bmp",
			"image/gif"  => ".gif"
        ];

        return (isset($image_types[$image_type])) ? $image_types[$image_type] : '';

    }

    public static function validImageExtension ($extension) {
        
        $extensions = ['jpg', 'jpeg', 'png', 'bmp', 'gif'];

        return (self::in_arrayi($extension, $extensions)) ? true : false;
    } 


    public static function in_arrayi($needle, $haystack)
    {
      return in_array(strtolower($needle), array_map('strtolower', $haystack));
    }



    public static function validImageSize ($image) {
        
        $max_image_file_size = self::get_setting('max_file_size') * 1024 * 1024;

        return ($image->getClientSize() <= $max_image_file_size) ? true  : false;
    }


    public static function validImage ($image, &$ext) {

        if ($image == null) {
            return false;
        }

        if(!getimagesize($image)) {
            return false;
        }

        $extension = $image->getClientOriginalExtension();

        if (!self::validImageExtension($extension)) {
            return false;
        }

        if (!self::validImageSize($image)) {
            return false;
        }

        $ext = self::create_supported_image_extension ($image->getMimeType());

        return true;

    }




    public static function generate_image_filename ($prefix, $extension) {
    	return uniqid($prefix). rand(10000000, 99999999) . $extension;
    }

    public static function add_settings ($data) {
        foreach ($data as $key => $value) {
            Settings::set($key, $value);
        }
    }

    public static function clearCacheViews () { Artisan::call('view:clear'); }

    public static function copyFolder($source, $destination) 
	{ 
       //Open the specified directory

       $directory = opendir($source); 
       
       //Create the copy folder location
       if(!file_exists($destination))
       		mkdir($destination, 0777);

       //Scan through the folder one file at a time
       while(($file = readdir($directory)) != false) 
       {
       		if($file == '.' || $file == '..')
       			continue;

       		if(is_dir($source. '/' .$file))
       		{
       			self::copyFolder($source . '/' . $file, $destination . '/' . $file);
       		}
       		else
       		{
       			//Copy each individual file 
	            if(!copy($source.'/' .$file, $destination.'/'.$file));
       		}
       } 

	}




    public static function buildArrayString ($array) {
        
        if (is_array($array)) {
            return self::makeArray($array);
        }

        return '';
    }

    public static function makeArray ($array) {
        $string = "<?php \n";
        $string .= "return [ \n";
        $string .= self::buildArray($array);
        $string .= "\n];";

        return $string;
    }


    public static function buildArray ($array) {

        $key_value_string = '';

        foreach ($array as $key => $value) {

            $value    = str_replace('$', '\$', $value);
            $value    = str_replace("\'", "'", $value);
            $key_value_string .=  '"'.$key.'" => "'.$value.'",'. "\n";
        }

        return $key_value_string;
    }


    public static function updateArrayString ($old_array, $new_array_to_be_added) {
        if (is_array($old_array) && is_array($new_array_to_be_added)) {
            $new_array = array_merge($old_array, $new_array_to_be_added);
            return self::makeArray($new_array);
        }

        return '';
    }

    public static function saveLanguageFile ($language, $language_file, $array_string) {
        
        try {
          
          $path = base_path("resources/lang/{$language}/{$language_file}.php");
          
          if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0777, true);
          }

          file_put_contents($path, $array_string);
          return true;

        } catch (\Exception $e) {dd(dirname($path));
            return false;
        }
    }

    public static function languageFileArray($language, $language_file) {
      $array = [];
      try {
        $array = include base_path("resources/lang/{$language}/{$language_file}.php");
      } catch (\Exception $e) {}

      return $array;
    }

}