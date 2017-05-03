<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Admin\UtilityRepository as UtilRepo;


class ProfileSettingsController extends Controller {


    public function showSettings() {

        return view('admin.admin_profile_settings', [
            'filter_distance_unit'                 => UtilRepo::get_setting('filter_distance_unit'),
            'filter_distance'                      => UtilRepo::get_setting('filter_distance'),
            'filter_range_min'                     => UtilRepo::get_setting('filter_range_min'),
            'filter_range_max'                     => UtilRepo::get_setting('filter_range_max'),
            'filter_range_max'                     => UtilRepo::get_setting('filter_range_max'),
            'filter_non_superpowers_range_enabled' => UtilRepo::get_setting('filter_non_superpowers_range_enabled'),
            'hide_popularity'                      => UtilRepo::get_setting('hide_popularity'),
            'disable_interest_edit'                => UtilRepo::get_setting('disable_interest_edit'),
            'auto_browser_geolocation'             => UtilRepo::get_setting('auto_browser_geolocation'),
        ]);
    }


    public function saveAdvanceFilterSetting(Request $req) {
        $advance_filter_only_superpowers = $req->mode == 'true' ? 'true' : 'false';
        UtilRepo::set_setting('advance_filter_only_superpowers', $advance_filter_only_superpowers);
        return response()->json(['status' => "success"]);
    }



    public function saveProfileFieldsMode(Request $request)
    {
        $data = $request->all();
        unset($data["_token"]);

        foreach($data as $key => $value) {
            UtilRepo::set_setting($key, $value);
        }
        
        return redirect('admin/settings/profile');
    }

    public function saveFilterRangeSettings(Request $request)
    {
        
        UtilRepo::set_setting('filter_distance_unit', $request->filter_distance_unit);
        UtilRepo::set_setting('filter_distance', $request->filter_distance);
        UtilRepo::set_setting('filter_range_min', $request->filter_range_min);
        UtilRepo::set_setting('filter_range_max', $request->filter_range_max);

        $filter_non_superpowers_range_enabled = $request->filter_non_superpowers_range_enabled == "on" ? 'true' : 'false';

        UtilRepo::set_setting('filter_non_superpowers_range_enabled', $filter_non_superpowers_range_enabled);

        return redirect('admin/settings/profile');
    }

}