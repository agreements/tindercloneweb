<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Components\Plugin;
use App\Repositories\Admin\UtilityRepository;
use Illuminate\Http\Request;



class GoogleAnalyticController extends Controller
{
    
    public function showSettings () {
        
        $google_accountNumber = UtilityRepository::get_setting('google_accountNumber');

        return Plugin::view('GoogleAnalyticPlugin/settings', ['google_accountNumber' => $google_accountNumber]);
    }


    //this funciton saves/updates gogole pluign login credential settings
    //Route:: /admin/pluginsettings/google
    public function saveSettngs (Request $request) {
        
        try {

            UtilityRepository::set_setting('google_accountNumber', $request->google_accountNumber);

            return response()->json(['status' => 'success', 'message' => trans('app.success_google_analytic_set')]);

        } catch (\Exception $e) {
            
            return response()->json(['status' => 'error', 'message' => trans('app.failed_google_analytic_set')]);
        }

    }
}
