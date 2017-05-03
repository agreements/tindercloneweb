<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Admin\SocialLoginsRepository;
use App\Repositories\Admin\UtilityRepository;

class SocialLoginsController extends Controller 
{
    
    public function __construct (SocialLoginsRepository $socialLoginsRepo) 
    {
        $this->socialLoginsRepo = $socialLoginsRepo;
    }

    public function show() 
    {

        $social_logins      = $this->socialLoginsRepo->getAllSocialLogins();
        $no_social_logins   = UtilityRepository::get_setting('no_social_logins');
        $only_social_logins = UtilityRepository::get_setting('only_social_logins');

        return view('admin.social_logins_settings', [
            'social_logins'      => $social_logins,
            'no_social_logins'   => $no_social_logins,
            'only_social_logins' => $only_social_logins
        ]);
    }

    public function save_priority(Request $request) 
    {
        $only_social_logins = $request->only_social_logins == 'on' ? 'true' : 'false';

        UtilityRepository::set_setting('only_social_logins', $only_social_logins);
        UtilityRepository::set_setting('no_social_logins', $request->no_social_logins);

        $arr = $request->all();
        
        unset($arr['_token']);
        unset($arr['no_social_logins']);
        unset($arr['only_social_logins']);

        $this->socialLoginsRepo->save_priority($arr);
        return response()->json(["status" => 'success']);
    }

}