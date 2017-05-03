<?php

namespace App\Repositories;

use App\Repositories\Admin\UtilityRepository;
use App\Models\EmailSettings;
use App\Models\Settings;
use Symfony\Component\Debug\Exception\FatalErrorException;


class EmailPluginRepository {

    protected static $bladeCompiler;

    public static function bladeCompilerInstance()
    {
        if(isset(self::$bladeCompiler)) {
            return self::$bladeCompiler;
        }

        return self::$bladeCompiler = app('Illuminate\View\Compilers\BladeCompiler');
    }
	
    public static function emailContentParser ($content, $user, $other_user, $extra_symbols = []) {
        
        $symlinks = self::getSymlinks($user, $other_user);

        $symlinks = array_merge($extra_symbols, $symlinks);

        //$blade_parsed_content = $content;
        $blade_parsed_content = self::parseBlades ($content, $user, $other_user);

        foreach ($symlinks as $key => $value) {
            $blade_parsed_content = str_replace($key, $value, $blade_parsed_content);   
        }

        return $blade_parsed_content;
    }

    
    public static function parseBlades ($content, $user, $other_user) {
        $bladeCompiler = self::bladeCompilerInstance();
        $compiledString = $bladeCompiler->compileString(html_entity_decode($content, ENT_QUOTES));

        return self::renderRawPHPString($compiledString, ['user' => $user, 'other_user' => $other_user]);
    }

    public static function renderRawPHPString($__php, $__data)
    {
        $obLevel = ob_get_level();
        ob_start();
        extract($__data, EXTR_SKIP);
        try {
            eval('?' . '>' . $__php);
        } catch (Exception $e) {
            while (ob_get_level() > $obLevel) ob_end_clean();
            throw $e;
        } catch (Throwable $e) {
            while (ob_get_level() > $obLevel) ob_end_clean();
            throw new FatalThrowableError($e);
        }
        return ob_get_clean();
    }



    public static function getSymlinks ($user, $other_user) {

        return [

        '@website_name'          => UtilityRepository::get_setting('website_title'),
        '@website_link'          => url(''),
        
        '@name'                  => $user->name,
        '@to_name'               => $user->name,
        '@from_name'             => $other_user->name,
        
        '@email'                 => $user->username,
        '@to_email'              => $user->username,
        '@from_email'            => $other_user->username,
        
        '@profile_link'          => url("profile/{$user->id}"),
        '@to_profile_link'       => url("profile/{$user->id}"),
        '@from_profile_link'     => url("profile/{$other_user->id}"),
        
        '@forgot_password_link'  => url("reset/{$user->id}/{$user->password_token}"),
        '@activate_account_link' => url("sample/activate/{$user->id}/{$user->activate_token}"),

        ];
    }

    public static function getEmailSettings($type)
    {
        return EmailSettings::where('email_type','=',$type)->first();
    }

    public function saveFooterValue($arr)
    {
        Settings::set('footer_text', $arr['footer_text']);
    }
}
