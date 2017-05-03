<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Auth;

class MobileAPI {
   
    public function handle($request, Closure $next) {

    	$token = $request->access_token;
    	$user_id = $request->user_id;

    	$user = $this->shouldPassThrough($user_id, $token);
    	
    	if ($user) {

            $request->real_auth_user = clone $user;
            Auth::login($request->real_auth_user, false);
            
    		//storing last request of auth user
	        $user->last_request = date('Y-m-d H:i:s');
	        $user->save();

            unset($user->password);
            $user->gender_text          = trans('custom_profile.'.$user->gender);
            $user->age                  = $user->age();
            $user->superpower_activated = ($user->isSuperPowerActivated()) ? 'true' : 'false';
            $user->superpower_days_left = $user->superpowerdaysleft();
            $user->invisible            = ($user->isInvisible()) ? 'true' : 'false';
            $user->online_status        = ($user->onlineStatus()) ? 'true' : 'false';
            $user->social_links         = $user->get_social_links();
            $user->social_verified      = ($user->is_social_verified()) ? 'true' : 'false';
            unset($user->social_login_links);
            $profile_picture = substr($user->profile_pic_url, 0);
            $user->profile_picture = $profile_picture;
            $profile_pic_url = [
                "thumbnail" => $user->thumbnail_pic_url(),
                "encounter" => $user->encounter_pic_url(),
                "other"     => $user->others_pic_url(),
                "original"  => $user->profile_pic_url(),
            ];
            $user->profile_pic_url = $profile_pic_url;
            $credits = $user->credits->balance;
            unset($user->credits);
            $user->credits = $credits;
            $request->auth_user = $user;

    		return $next($request);
    	}


    	return response()->json([
    		"status" => "error",
    		"error_data" => ["error_text" => "Authentication Error"]
    	]);
        
    }   

    protected function shouldPassThrough ($user_id, $token) {

    	if (!$user_id || !$token) 
    		return false;

    	$user = User::find($user_id);
    	if ($user && $user->access_token && strcmp($user->access_token, $token) == 0) {
    		return $user;
    	}

    	return false;
    }


}
