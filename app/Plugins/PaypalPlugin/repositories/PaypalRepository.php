<?php

namespace App\Repositories;

use curl;
// use App\Repositories\ProfileRepository;

class PaypalRepository
{

	//this object holds paypal details

    public function curlfun($post,$url)
    {

        try{    
                 $curl = curl_init();
                 $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
                 curl_setopt($curl, CURLOPT_URL, $url);
                 //The URL to fetch. This can also be set when initializing a session with curl_init().
                 curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                 //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
                 curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
                 //The number of seconds to wait while trying to connect.
                 if ($post != "") {
                 curl_setopt($curl, CURLOPT_POST, 5);
                 curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
                 }
                 curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
                 //The contents of the "User-Agent: " header to be used in a HTTP request.
                 curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
                 //To follow any "Location: " header that the server sends as part of the HTTP header.
                 curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE);
                 //To automatically set the Referer: field in requests where it follows a Location: redirect.
                 curl_setopt($curl, CURLOPT_TIMEOUT, 10);
                 //The maximum number of seconds to allow cURL functions to execute.
                 curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
                 //To stop cURL from verifying the peer's certificate.
                 $contents = curl_exec($curl);

                 curl_close($curl);
                 
                return $contents;
            
            }
            catch(\Exception $e)
             {
                 return response()->json(["error"=> $e->getMessage]);
            }
    }
}
