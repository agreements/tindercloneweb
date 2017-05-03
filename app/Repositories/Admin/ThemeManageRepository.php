<?php

namespace App\Repositories\Admin;

use App\Components\ThemeInterface;
use Storage;
use App\Models\Themes;
use App\Repositories\Admin\UtilityRepository;

class ThemeManageRepository {


    public function __construct(Themes $themes) {
        $this->themes = $themes;
    }

    public function getLoadedThemes() {

        $dirs = Storage::directories('resources/views/themes/');

        $loaded_parent_themes = $loaded_childthemes = [];

        foreach($dirs as $dir) {

            $theme_name = $this->get_theme_name($dir);
            $theme_file = "{$dir}/{$theme_name}.php";

            if (Storage::has($theme_file)) {

                $themeClass = "{$theme_name}\\{$theme_name}";
                
                require_once base_path()."/{$theme_file}";
                    
                $themeObj = new $themeClass; 
                
                if ($themeObj instanceof ThemeInterface) {
                    
                    $themeObj->name = $theme_name;

                    //checking installed or not
                    $theme = $this->themes->Where('name', $theme_name)->first();

                    if (!$theme) {

                        $themeObj->isInstalled = false;

                        if (!method_exists($themeObj, 'child')) { 
                            array_push($loaded_parent_themes, $themeObj);
                        } else {
                            array_push($loaded_childthemes, $themeObj);
                        }
                    }

                    
                }
            }
                
        }

        return array($loaded_parent_themes,$loaded_childthemes);
    }

    protected function get_theme_name ($dir_path) {
       return explode('/', $dir_path)[3];
    }


    public function getThemes() {

        $themes = $childThemes = [];

        array_push($themes, $this->themes->Where('role', 'parent')->get());
        array_push($childThemes, $this->themes->Where('role', 'child')->get());

        return [$themes,$childThemes];
    }


    protected function getScreenshotFile ($theme_name) {
        
        $image_types = ['.jpg', '.png', '.bmp'];
        $path = "resources/views/themes/{$theme_name}/screenshot";

        foreach ($image_types as $image_type) {
            $image_file = $path . $image_type; 
            if (Storage::has($image_file)) {
                return [$image_file, str_replace('.', "image/", $image_type)];
            }

        }
        return '';
    }

    public function getScreenshot ($theme_name) {
        $file = $this->getScreenshotFile($theme_name);

        if (is_array($file)){
            return [Storage::get($file[0]), $file[1]];
        }
    }

    public function get_activated_theme_by_role ($role) {
	    
        return $this->themes->where('isactivated', 'activated')->where('role', $role)->first();
    }


    protected function deactivate_previous_parent_theme () {
        $this->themes->where('isactivated', 'activated')
                ->where('role', 'parent')
                ->update(['isactivated' => 'deactivated']);
    }

    protected function deactivate_previous_child_theme () {
        $this->themes->where('isactivated', 'activated')
                ->where('role', 'child')
                ->update(['isactivated' => 'deactivated']);
    }

    protected function activate_theme_by_id ($theme_id) {
        $this->themes->where('id', $theme_id)->update(['isactivated' => 'activated']);
    }

    protected function get_theme_by_id ($theme_id) {
        return $this->themes->where('id', $theme_id)->first();
    }

    public function activateParentTheme ($theme_id) {

        try {
            
            $this->deactivate_previous_parent_theme();
            $this->deactivate_previous_child_theme();
            $this->activate_theme_by_id($theme_id);

            $this->syncWithConfig();

            return true;

        } catch (\Exception $e) { return false; }
        
    }



    public function activateChildTheme ($child_theme_id, $child_theme_name) {

        try {

            $activated_parent_theme = $this->get_activated_theme_by_role('parent');
            $requested_child_theme = $this->get_theme_by_id ($child_theme_id);

            if ($activated_parent_theme && $requested_child_theme) {

                $childThemeClass = "{$child_theme_name}\\{$child_theme_name}";
                require_once base_path()."/resources/views/themes/{$child_theme_name}/{$child_theme_name}.php";

                $childThemeObj = new $childThemeClass;

                if (method_exists($childThemeObj, 'child') && $childThemeObj->child() == $activated_parent_theme->name) {

                        $this->deactivate_previous_child_theme();
                        $this->activate_theme_by_id($child_theme_id);
                }

            }

            $this->syncWithConfig();
            return true;

        } catch (\Exception $e) { return false; }
 

    }



    public function deactivateChildTheme () {

        try {

             $this->deactivate_previous_child_theme();
             $this->syncWithConfig();
             return true;

        } catch (\Exception $e) { return false; }
       
    }



    public function createThemeDir ($theme) {
        Storage::makeDirectory("/public/themes/{$theme}", 0777);
    }

    public function copyAssets ($theme) {

        $src  = base_path()."/resources/views/themes/{$theme}/assets";
        $dest = public_path()."/themes/{$theme}";

        //create themes directory in public folder if no exists
        $theme_path = public_path().'/themes';

       if(!file_exists($theme_path)) {
            mkdir($theme_path, 0777);
       }

        if(file_exists($src))
        {
            UtilityRepository::copyFolder($src, $dest); 
        }       
        
    }

    public function addTheme ($data) {
        $this->themes->insert($data);
    }



    public function saveSpotlightImage ($spotlight_image, $theme_name) {

        $ext = UtilityRepository::create_supported_image_extension($spotlight_image->getMimeType());
        
        if (!$ext) { return false; }

        $fileName = UtilityRepository::generate_image_filename('', $ext);
        $file     = file_get_contents($spotlight_image);

        Storage::put("public/themes/{$theme_name}/screenshots/{$fileName}", $file);
        UtilityRepository::set_setting("{$theme_name}_spotlight_screenshot", url("themes/{$theme_name}/screenshots/{$fileName}"));
    }

    public function saveRiseupImage ($riseup_image, $theme_name) {

        $ext = UtilityRepository::create_supported_image_extension($riseup_image->getMimeType());
        
        if (!$ext) { return false; }
        
        $fileName = UtilityRepository::generate_image_filename('', $ext);
        $file     = file_get_contents($riseup_image);

        Storage::put("public/themes/{$theme_name}/screenshots/{$fileName}", $file);
        UtilityRepository::set_setting("{$theme_name}_riseup_screenshot", url("themes/{$theme_name}/screenshots/{$fileName}"));
        
    }
    
    public static function  themesConfigPath(){
		return config_path('themes.php');
	}
    
    public function syncWithConfig(){
	    
	    $parent_theme       = $this->get_activated_theme_by_role('parent');
        $child_theme        = $this->get_activated_theme_by_role('child');
        
        $arr = array();
        $arr["parent"] = serialize($parent_theme);
        $arr["child"] = serialize($child_theme);
		$arrayString = var_export($arr, true);
        $arrayString = "<?php return \n {$arrayString};"; 
        file_put_contents(self::themesConfigPath(), $arrayString, LOCK_EX);
        
    }

}