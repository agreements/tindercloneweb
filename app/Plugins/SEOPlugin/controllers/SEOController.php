<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Components\Plugin;
use App\Models\Settings;
use Socialite;
use App\Repositories\Admin\UtilityRepository;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;



class SEOController extends Controller
{
   	
    // Route:: /admin/seoSettings
    public function showSettings()
    {
        $meta_description = UtilityRepository::get_setting('meta_description');
        $meta_keywords = UtilityRepository::get_setting('meta_keywords');
        $meta_block = UtilityRepository::get_setting('meta_block');

        return Plugin::view(
            'SEOPlugin/settings', 
            ['meta_description' => $meta_description, 'meta_keywords' => $meta_keywords, 'meta_block' => $meta_block]
        );
    }

    //Route:: /admin/seosettings
    public function saveSettngs  (Request $request) {
        try {

            // $meta_description = Settings::get('meta_description');
            // $meta_keywords = Settings::get('meta_keywords');
            // $meta_block = Settings::get('meta_block');

            UtilityRepository::set_setting ('meta_description', $request->meta_description);
            UtilityRepository::set_setting ('meta_keywords', $request->meta_keywords);
            UtilityRepository::set_setting ('meta_block', $request->meta_block);

            return response()->json(['status' => 'success', 'message' => trans('app.success_seo_set')]);

        } catch (\Exception $e) {

            return response()->json(['status' => 'error', 'message' => trans('app.failed_seo_set')]);
        }
            
    }
}
