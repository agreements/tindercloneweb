<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\UtilityRepository;
use Illuminate\Http\Request;
use App\Components\Plugin;


class GoogleMapPluginController extends Controller {

    public function showSettings() {
        $google_map_key = UtilityRepository::get_setting('google_map_api_key');
        return Plugin::view('GoogleMapPlugin/settings', ['google_map_key' => $google_map_key]);
    }

    public function saveSetting (Request $req) {
        $google_map_key = $req->google_map_key;
        UtilityRepository::set_setting('google_map_api_key', $google_map_key);
        return response()->json([
            "status" => "success",
            "message" => trans('GoogleMapPlugin.save_success')
        ]);
    }

}



        

        

        


        

        

        
        