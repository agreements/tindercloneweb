<?php

namespace App\Repositories\Admin;

use Hash;
use DB;
use Artisan;
use App\Models\EmailSettings;
use App\Repositories\Admin\UtilityRepository;

class EmailSettingRepository {


    public function getSMTPSettings() {

        $settings             = new \stdClass;
        $settings->host       = UtilityRepository::get_setting('smtp_host');
        $settings->port       = UtilityRepository::get_setting('smtp_port');
        $settings->from       = UtilityRepository::get_setting('smtp_from');
        $settings->username   = UtilityRepository::get_setting('smtp_username');
        $settings->name       = UtilityRepository::get_setting('smtp_name');
        $settings->password   = UtilityRepository::get_setting('smtp_password');
        $settings->encryption = UtilityRepository::get_setting('smtp_encryption');

        return $settings;
    }

    public function getMANDRILLSettings () {

        $settings           = new \stdClass;
        $settings->host     = UtilityRepository::get_setting('mandrill_host');
        $settings->port     = UtilityRepository::get_setting('mandrill_port');
        $settings->username = UtilityRepository::get_setting('mandrill_username');
        $settings->password = UtilityRepository::get_setting('mandrill_password');

        return $settings;
    }

    public function getMailDriver() { return UtilityRepository::get_setting('mail_driver'); }


    public function isSMTPSettingsExist() {

        $smtp_settings = $this->getSMTPSettings();

        foreach($smtp_settings as $key => $value) {
            if ($value == '') return false;
        }
        return true;
    }

    public function setSMTPDriver() {

        if ($this->isSMTPSettingsExist()) {
            
            UtilityRepository::set_setting('mail_driver', 'smtp');
            return true;
        }
        
        return false;   
    }


    public function isMANDRILLSettingsExist () {

        $mandrill_settings = $this->getMANDRILLSettings();
        
        foreach($mandrill_settings as $key => $value) {
            if ($value == '') return false;
        }

        return true;                                        
    }

    public function setMANDRILLDriver() {

        if ($this->isMANDRILLSettingsExist()) {
            
            UtilityRepository::set_setting('mail_driver', 'mandrill');
            return true;
        }
        
        return false;
    }


    public static function getEmailTemplatePath () {
        return base_path() . "/resources/views/emails";
    }

    public static function getEmailTemplates () {
        
        $templates = [];
        $email_template_path = self::getEmailTemplatePath();
        
        $files = scandir ($email_template_path);
        foreach ($files as $file) {
           if ($file == '.' || $file == '..' || is_dir($file))  {
                continue;
            }
            array_push($templates, $file);
        }

        return $templates;
    }

    public static function chars_to_replace () {
        return [' ', ' ', '\\', '/', '*', '.'];
    }

    public static function create_mail_id ($title) {
        return str_replace(self::chars_to_replace(), '', $title);
    }

    

    public static function getEmailSetting ($email_type) {
        return EmailSettings::where('email_type', $email_type)->first();
    }


    public function saveEmailSettings($sub, $body,$content_type,$email_type) {

        $email_setting = EmailSettings::where('email_type', $email_type);

        if ($email_setting->first()) {

            $email_setting->update([
                "subject" => $sub,
                "content" => $body,
                "content_type" => $content_type,
            ]);

        } else {

            $email_setting->insert([
                "subject" => $sub,
                "content" => $body,
                "content_type" => $content_type,
                "email_type" => $email_type,
            ]);
        }
    }

}