<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Components\Plugin;
use App\Models\Settings;
use Socialite;
use App\Models\User;
use Illuminate\Http\Request;
use App\Repositories\Admin\UtilityRepository;
use Auth;
use App;
use Config;
use stdClass;
use File;

class LanguageController extends Controller
{
   	
    public function __construct()
    {
        $this->langRepo = app('App\Repositories\LanguageRepository');
    }


    // Route:: /admin/languageSettings
    public function showSettings () {

        $lang_path = base_path() . "/resources/lang";

        $dirs = scandir ($lang_path);

        $langs = [];

        /*  searching for valid language dirctory
            if dirctory contains app.php file then only directory wil be
            treated as valid language directory. 
        */
        foreach ($dirs as $dir) {

            if ($dir == '.' || $dir == '..') {

                continue;
            }
            
            $lang = $lang_path . '/' . $dir;

            //pushing valid language directory into array
            if (file_exists($lang . '/app.php')) {

                array_push($langs, $dir);
            }
                 
        }

        //retriving default language
        $defaultLang = UtilityRepository::get_setting('default_language');
        $defaultLangValue = $defaultLang;
       

        $editabe_langs = [];

        
        if ($defaultLangValue) {


            foreach ($langs as $lang) {

                $editabe_langs[$lang] = [];   
                try {
                    $lang_files = [];
                    $lang_files = scandir(base_path()."/resources/lang/".$defaultLangValue);    
                } catch (\Excepiton $e){}
                

                foreach ($lang_files as $lang_file) {
                    if ($lang_file == '.' || $lang_file == '..' || $lang_file == 'validation.php') continue;

                    array_push($editabe_langs[$lang], $lang_file);

                }


            }


        }
       

        return Plugin::view(

            'LanguagePlugin/settings', 
            [
                'langs' => $langs, 
                'defaultLangValue' => $defaultLangValue, 
                'editable' => $editabe_langs, 
            ]
        );

    }


    public function get_edit_language(Request $req) {
        $language = $req->language;
        $language_file = $req->language_file;


        $defaultLangValue = UtilityRepository::get_setting('default_language');

        if ($defaultLangValue) {

            $editabe_terms[$language] = [];   
            $default = include $this->langRepo->languageArray($defaultLangValue, $language_file);
            $language_arr =  include $this->langRepo->languageArray($language, $language_file);
            foreach ($default as $key => $value) {
            
                $edit = new stdClass;
                $edit->mcode = $key;
                $edit->left_lang = $value;
                if (isset ($language_arr[$key])) {
                    $edit->right_lang = $language_arr[$key];
                } else {
                
                    $edit->right_lang = "";
                }
                array_push($editabe_terms[$language], $edit);
            }
            
        }




        return Plugin::view(

            'LanguagePlugin/language_edit', 
            [
                'language' => $language, 
                'language_file' => $language_file,
                'editabe_terms' => $editabe_terms
            ]
        );
    }


    public function post_edit_language (Request $request) {

        
        try {

            $new_words = $request->all();

            $language = $new_words["language_edit_short_name_parameter"];
            $language_file = $new_words["language_file_edit_short_name_parameter"];
            unset($new_words["language_edit_short_name_parameter"]);
            unset($new_words["language_file_edit_short_name_parameter"]);
            unset($new_words["_token"]);

            

            $array = $this->langRepo->make_array($new_words);

            if (!is_writable(base_path()."/resources/lang/".$language."/{$language_file}")) {

                return response()->json(['status' => 'error', 'message' => 'Make '.base_path()."/resources/{$langu}/".$language."/{$language_file} File Writable."]);
            }

            File::put(base_path()."/resources/lang/".$language."/{$language_file}", $array);

        } catch (\Excepiton $e) {

            return response()->json(['status' => 'error', 'message' => trans_choice('admin.language_msg',1)]);
        }
        
    
        return response()->json(['status' => 'success']);
    
    }

    

    public function saveDefaultSettngs (Request $request) {
        
        if ($request->defaultLang == null) {
            
            return response()->json(['status' => 'error', 'message' => trans('app.required_default_lang')]);
        }

        try {
            
            App::setLocale($request->defaultLang);

            UtilityRepository::set_setting('default_language', $request->defaultLang);

            return response()->json([
                'status' => 'success', 
                'message' => 'Default Language ' . $request->defaultLang . ' '.trans('app.save_success')
            ]);
            

        } catch (\Excepiton $e) {

            return response()->json(['status' => 'error', 'message' => trans('app.failed_default_lang')]);
        }
            
    }

    //Route:: /admin/languageSettings
    public function saveSelectedSettngs(Request $request)
    {
        return redirect('/admin/languageSettings');
    }





    public function setLanguageCookie (Request $request) {

        try {


            if ($request->language) {
              
                return response()->json(['status' => 'success'])->withCookie(cookie()->forever('language', $request->language));
              
            }

            return response()->json(['status' => 'error']);

        } catch (\Exception $e) {

            return response()->json(['status' => 'error']);

        }

            
    

    }




}
