<?php

namespace App\Repositories\Admin;

use Hash;
use DB;
use Artisan;
use Storage;
use App\Repositories\Admin\UtilityRepository;
use App\Repositories\ProfileRepository;

use App\Models\PhotoAbuseReport;
use App\Models\UserAbuseReport;
use App\Models\User;
use App\Models\Fields;

class GeneralManageRepository {

    public function saveDefaultImage ($key, $value) {

        $arr = explode('_', $key);

         if (UtilityRepository::validImage($value, $ext)) {

            $fileName = UtilityRepository::generate_image_filename(end($arr), $ext);
            app("App\Repositories\ProfileRepository")->save_resize_photo($value, $fileName);
            $prev_default = UtilityRepository::get_setting('default_'.end($arr));
            User::where('profile_pic_url', $prev_default)->update(['profile_pic_url' => $fileName]);
            UtilityRepository::set_setting('default_'.end($arr), $fileName);

            return true;
        }

        return false; 
         
    }

    public function save_max_file_size($file_size)
    {
        UtilityRepository::set_setting('max_file_size', $file_size);
    }

    public function getGenderField()
    {
        $field = app("App\Models\Fields")->getGenderField();
        return $field;
    }

    public function saveLogo ($file) {

        if (UtilityRepository::validImage($file, $ext)) {

            $fileName = UtilityRepository::generate_image_filename('logo_', $ext);

            $path = self::get_logo_path();
            $file->move($path, $fileName);
             
            UtilityRepository::set_setting('website_logo', $fileName);

            return self::get_logo_url($fileName);
        }

        throw new \Exception('No file');        
    }

    public function saveOuterLogo($file) {

        if (UtilityRepository::validImage($file, $ext)) {

            $fileName = UtilityRepository::generate_image_filename('logo_', $ext);

            $path = self::get_logo_path();
            $file->move($path, $fileName);
             
            UtilityRepository::set_setting('website_outerlogo', $fileName);

            return self::get_logo_url($fileName);
        }

        throw new \Exception('No file');        
    }


    public static function get_logo_path () {

        $path = public_path() . '/uploads/logo';
        if (!file_exists($path)) { mkdir($path); }
        
        return $path;
    }

    public static function get_logo_url ($filename) {
        return asset('uploads/logo/'.$filename);
    }




    public function favicon ($file) {

        if (UtilityRepository::validImage($file, $ext)) {

            $fileName = UtilityRepository::generate_image_filename('favicon', $ext);

            $path = self::get_favicon_path();
            $file->move($path, $fileName);
             
            UtilityRepository::set_setting('website_favicon', $fileName);

            return self::get_favicon_url($fileName);
        }

        throw new \Exception('No file');    
    }


    public static function get_favicon_path () {

        $path = public_path() . '/uploads/favicon';
        if (!file_exists($path)) { mkdir($path); }
        
        return $path;
    }

    public static function get_favicon_url ($filename) {
        return asset('/uploads/favicon/'.$filename);
    }



    public function backgroundImage ($file) {

        if (UtilityRepository::validImage($file, $ext)) {

            $fileName = UtilityRepository::generate_image_filename('backgroundimage', $ext);

            $path = self::get_background_image_path();
            $file->move($path, $fileName);
             
            UtilityRepository::set_setting('website_backgroundimage', $fileName);

            return self::get_background_image_url($fileName);
        }

        throw new \Exception('No file');    

    }


    public static function get_background_image_path () {

        $path = public_path() . '/uploads/backgroundimage';
        if (!file_exists($path)) { mkdir($path); }
        
        return $path;
    }

    public static function get_background_image_url ($filename) {
        return asset('uploads/backgroundimage/'.$filename);
    }


    public function getLimitSetting () {

        $arr = array();
        
        array_push($arr, UtilityRepository::get_setting('limit_encounter'));
        array_push($arr, UtilityRepository::get_setting('limit_chat'));
        
        return $arr;
    }


    public function testHttpsMode()
    {
        $response = file_get_contents(secure_asset('admin/test-https'));
        $json = json_decode($response);
        if(isset($json->test_https_message) && $json->test_https_message === 'HTTPS_TEST_OK') {
            return true;
        }

        throw new \Exception('HTTPS_MODE_TEST_FAILED');
    }


    public function enableSecureMode($domain)
    {
        
        UtilityRepository::clearCacheViews();

        $domain = rtrim($domain, '/');
        $parts = parse_url($domain);
        if($parts['scheme'] !== "https") {
            throw new \Exception(trans('admin.https_required'));
        } 

        $this->testHttpsMode();



        UtilityRepository::set_setting('secure_mode', 'true');
        UtilityRepository::set_setting('domain', $domain);


        $secure_htaccess = Storage::get("app/Installer/secure_htaccess.stub");

        if (Storage::has("public/.htaccess")) 
            Storage::delete("public/.htaccess");

        
        $secure_htaccess =  str_replace("@{{domain}}@", $domain, $secure_htaccess);

        Storage::put("public/.htaccess", $secure_htaccess);
    }

    public function disableSecureMode()
    {
        UtilityRepository::set_setting('secure_mode', 'false');

        $unsecure_htaccess = Storage::get("app/Installer/unsecure_htaccess.stub");

        if (Storage::has("public/.htaccess")) 
            Storage::delete("public/.htaccess");

        Storage::put("public/.htaccess", $unsecure_htaccess);
    }

}