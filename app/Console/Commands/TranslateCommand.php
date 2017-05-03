<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Storage;

class TranslateCommand extends Command {
  
    
    protected $signature = 'translate {google_api_key} {source_directory} {destination_directory}';
    protected $description = 'Translate all language files from source destination english to destination directory';
    protected $langs = [];
    protected $googleApiKey = "";
    protected $sourceDirectory = "";
    protected $destinationDirectory = "";
    protected $files = [];
    protected $api_url = "";

    public function handle() 
    {
        
        $this->init();
        $this->startTranslate();
    }



    protected function startTranslate()
    {
        foreach($this->langs as $toLang) {

            foreach($this->files as $file) {


                $enLangArray = include $this->sourceDirectory . "/" . $file;
                $newArray = [];            

                foreach($enLangArray as $key => $value) {

                    echo "translate |{$file}|  en -> {$toLang} : {$value}\n";

                    preg_match("*[{]\d[}]*", $value, $matches);
                    
                    if(empty($matches)) {
                        $newArray[$key] = $this->callApi('en', $toLang, $value);
                    } else {

                        $transChoiceArray = explode("|", $value);
                        $count = count($transChoiceArray);
                        $newString = "";
                        
                        foreach($transChoiceArray as $index => $value) {

                            $string = explode("}", $value)[1];
                            $string = $this->callApi('en', $toLang, $string);

                            $newString .= "|{".$index."} ". $string;
                        }

                        $newArray[$key] = ltrim($newString, '|');

                    }

                }


                $content = $this->buildPHPCode($newArray);
                $this->writeFile($this->destinationDirectory."/". $toLang. "/". $file, $content);
              
            }


        }
            

    }


    protected function setApiURL($fromLang, $toLang, $word)
    {
        $this->fromLang = $fromLang;
        $this->toLang = $toLang;
        $this->word = $word;
        $this->api_url = "https://www.googleapis.com/language/translate/v2?key={$this->googleApiKey}&source={$this->fromLang}&target={$this->toLang}&q=";
        $this->api_url .= urlencode($this->word);
    }



    protected function callApi($fromLang, $toLang, $word)
    {
        $this->setApiURL('en', $toLang, $word);
        $curl = curl_init();    
        curl_setopt($curl, CURLOPT_URL, $this->api_url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_ENCODING, "");
        $response = curl_exec($curl);
        $jsonData = json_decode($response, true);
        
        if(isset($jsonData['data']['translations'][0]['translatedText'])) {
            return $jsonData['data']['translations'][0]['translatedText'];
        } else {
            return "";
        }

    }



    protected function writeFile($filePath, $data)
    {
        $directory = dirname($filePath);

        if(!file_exists($directory)) {
            mkdir($directory);
        }

        file_put_contents($filePath, $data);
    }




    protected function buildPHPCode($array = []) 
    {
        $arrayString = var_export($array, true);
        $arrayString = "<?php return \n {$arrayString};"; 
        return $arrayString;
    }

    

    protected function initLanguageFilesToTranslate()
    {
        $files  = scandir ($this->sourceDirectory);

        foreach($files as $file) {

            if ($file === '.' || $file === '..' || $file  === 'validation.php') continue;
            $this->files[] = $file;
        }
    }



    protected function initSupportedLanguages() 
    {
        /*$dirs  = scandir ($this->sourceDirectory);

        foreach($dirs as $dir) {

            if ($dir === '.' || $dir === '..' || $dir === 'en') continue;
            $this->langs[] = $dir;
        }*/

        $this->langs = ['de', 'es', 'fr', 'hu', 'it', 'nl', 'pl', 'pt', 'ru', 'ja', 'zh-CN'];
 
    }



    protected function init()
    {
        $this->sourceDirectory = $this->argument('source_directory');
        $this->sourceDirectory = rtrim($this->sourceDirectory, '/');
        $this->destinationDirectory = $this->argument('destination_directory');
        $this->destinationDirectory = rtrim($this->destinationDirectory, '/');
        $this->initSupportedLanguages();
        $this->initLanguageFilesToTranslate();
        $this->googleApiKey = $this->argument('google_api_key');
    }

}
