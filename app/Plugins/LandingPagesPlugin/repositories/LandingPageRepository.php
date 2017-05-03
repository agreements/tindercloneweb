<?php

namespace App\Repositories;


use App\Plugins\LandingPagesPlugin\LandingPageInterface;
use App\Repositories\Admin\UtilityRepository;
use App\Components\Plugin;
use Storage;

class LandingPageRepository {


	public static function getLandingPages () {
		
		$landing_pages = [];
		$landing_path  = Plugin::path('LandingPagesPlugin/views/landing_pages');
		$dirs          = scandir ($landing_path);	

		foreach ($dirs as $dir) {

            if ($dir == '.' || $dir == '..') continue; 
         
			if (is_dir("{$landing_path}/{$dir}")) {

				array_push($landing_pages, $dir);
			}	
           
        }	

        return $landing_pages;

	}
	

	public static function landingPageInstance ($landing_page) {
        $class = self::include_landing_page_class($landing_page);
        
        $obj = new $class;
        
        if ($obj instanceof LandingPageInterface) {
            return $obj;
        } 
        return null;
    }

    public static function include_landing_page_class($landing_page) {
        $landing_page_class_file = Plugin::path('LandingPagesPlugin/views/landing_pages/'.$landing_page.'/'.$landing_page.'.php');
        include_once $landing_page_class_file;
        return "\\".$landing_page;
    }


    public static function languageFileArray($language, $language_file) {
    	$array = [];
    	try {
    		$array = include base_path("resources/lang/{$language}/{$language_file}.php");
    	} catch (\Exception $e) {}

    	return $array;
    }


    public static function saveContentSections ($contents, $edit_language, $language_file) {
    	$contents_array = [];
    	foreach ($contents as $content) {
    		$contents_array[$content['name']] = $content['value'];
    	}

    	$lang_array = self::languageFileArray($edit_language, $language_file);

    	$array_string = UtilityRepository::updateArrayString($lang_array, $contents_array);
    	return UtilityRepository::saveLanguageFile($edit_language, $language_file, $array_string);
    }


    public static function saveImageSections($images, $edit_language, $language_file) {
        $images_array = [];
        foreach ($images as $image) {
            $images_array[$image['name']] = [
                "url" => $image['value']
            ];
        }

        $storage_image_content_file = base_path("storage/LandingPages/{$language_file}_images.stub");

        $file_content = '';
        try {
            $file_content = file_get_contents($storage_image_content_file);
        } catch (\Exception $e){}

        try {

            if (!file_exists(dirname($storage_image_content_file))) {
                mkdir(dirname($storage_image_content_file), 0777, true);
            }

            
            if ($file_content != "")
                $images_array = array_merge(unserialize($file_content), $images_array);

            file_put_contents($storage_image_content_file, serialize($images_array));
            UtilityRepository::clearCacheViews();
            return true;
        
        } catch (\Exception $e) {
            return false;
        }
            
    }


    public static function saveLinkSections($links, $edit_language, $language_file) {
        $links_array = [];
        foreach ($links as $link) {
            $links_array[$link['name']] = [
                "url" => $link['value']
            ];
        }

        $storage_links_content_file = base_path("storage/LandingPages/{$language_file}_links.stub");

        $file_content = '';
        try {
            $file_content = file_get_contents($storage_links_content_file);
        } catch (\Exception $e){}

        try {

            if (!file_exists(dirname($storage_links_content_file))) {
                mkdir(dirname($storage_links_content_file), 0777, true);
            }

            
            if ($file_content != "")
                $links_array = array_merge(unserialize($file_content), $links_array);

            file_put_contents($storage_links_content_file, serialize($links_array));
            UtilityRepository::clearCacheViews();
            return true;
        
        } catch (\Exception $e) {
            return false;
        }
            
    }


    public static function create_supported_video_extension ($video_type) {

        $video_types = [
            "video/mp4"  => ".mp4",
            "video/ogg" => ".ogg",
            "video/webm"  => ".webm",
        ];

        return (isset($video_types[$video_type])) ? $video_types[$video_type] : '';

    }

    public static function validVideoExtension ($extension) {
        
        $extensions = ['mp4', 'ogg', 'webm'];

        return (in_array($extension, $extensions)) ? true : false;
    } 


    public static function validVideo ($video, &$ext) {

        if ($video == null) {
            return false;
        }

        $extension = $video->getClientOriginalExtension();

        if (!self::validVideoExtension($extension)) {
            return false;
        }


        $ext = self::create_supported_video_extension ($video->getMimeType());

        return true;

    }




    public static function generate_video_filename ($prefix, $extension) {
        return uniqid($prefix). rand(10000000, 99999999) . $extension;
    }

}
