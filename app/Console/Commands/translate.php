<?php

    
    run($argv[1]);
    function run($arg)
    {
        
        $curl = curl_init();      
        $english = include '/var/www/html/liteoxide/resources/lang/en/admin.php';

        //$google_key = "AIzaSyCqyLBaN613yodHCjgcEf014xTM2s7RtOg";
        $api_key = "trnsl.1.1.20160611T110622Z.384522ee8b1abe5d.e903f833db845c36e60898cb1f4e2f74809428fc";

        $translate_to = $arg;

        //$api_url = "https://www.googleapis.com/language/translate/v2?key=$google_key&source=en&target=".$translate_to."&q=";

        $api_url = "https://translate.yandex.net/api/v1.5/tr.json/translate?key=$api_key&lang=en-$translate_to&format=plain&text=";
        
        //$curl = new Curl;

        $translation_array = array();

        set_time_limit(0);
        ob_implicit_flush(1);

        foreach($english as $key => $word){ 
        
        	

        	$api_call = $api_url.urlencode($word);
        	echo "Fetching for $word";
        	echo "\n";
        	
        	//$json_data = $curl->simple_get($api_call);


            curl_setopt($curl, CURLOPT_URL, $api_call);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_ENCODING, "");
            $json_data = curl_exec($curl);
            

        	$translation = json_decode($json_data, true);
            //$translation = $translation['data']['translations'][0]['translatedText'];
        	$translation = $translation['text'][0];
        
        	echo $word." => ".$translation;
        	echo "\n";

        	$translation_array[$key] = $translation;
        	
        	
        	sleep(2);
        	if(ob_get_level()>0)
       ob_end_flush(); 



    	}

    	$file = '/var/www/html/liteoxide/resources/lang/'.$translate_to.'/admin.php';

    	echo "Writing to File -> $file";

    	$array = make_array($translation_array);
		file_put_contents($file, $array);

		echo "\n Completed!";
        curl_close($curl);
    	
    }


    function make_array($lang)
	{
		$out = '<?php '."\nreturn array(\n";
		$out .= build_array($lang);
		return $out.');';
	}


    function build_array($lang, $depth = 1)
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
			$out .= str_repeat("\t", $depth)."'".$key."' => '".addcslashes($value, "'\\/")."',\n";
		}
		return $out;
	}

