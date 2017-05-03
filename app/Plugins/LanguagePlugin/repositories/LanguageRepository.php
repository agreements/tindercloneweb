<?php

namespace App\Repositories;

use App\Components\Plugin;


class LanguageRepository
{
    private $blockUserRepo;
    public function __construct()
    {
        $this->blockUserRepo = app("App\Repositories\BlockUserRepository");
    }
    
    //this method returns all supported languages
    public function getSupportedLanguages () {
        

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

        return $langs;
 
    }


    public function make_array($lang)
    {
        $out = '<?php '."\nreturn array(\n";
        $out .= $this->build_array($lang);
        return $out.');';
    }


    public function build_array($lang, $depth = 1)
    {
        $out = '';
        foreach ($lang as $key => $value)
        {
            if (is_array($value))
            {
                $out .= str_repeat("\t", $depth)."'".$key."' => array(\n";
                $out .= build_array($value, ++$depth)."\n";
                $out .= str_repeat("\t", --$depth)."),\n";
                $depth = 1;
                continue;
            }
            $out .= str_repeat("\t", $depth)."'".$key."'  => '".addcslashes($value, "'\\/")."',\n";
        }
        return $out;
    }




    public function languageArray($language, $languageFile)
    {
        $langFilePath = base_path("/resources/lang/{$language}/{$languageFile}");

        $this->createEmptyLangDirectory($langFilePath);
        $this->createEmptyLangFile($langFilePath);

        return $langFilePath;
    }

    public function createEmptyLangDirectory($path)
    {
        if(!file_exists(dirname($path))) {
            mkdir(dirname($path));
        }
    }

    public function createEmptyLangFile($path)
    {
        if(!file_exists($path)) {

            $emptyr_array_string = $this->make_array([]);
            file_put_contents($path, $emptyr_array_string);
        }
    }

}
