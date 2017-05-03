<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Repositories\Admin\UtilityRepository;
use App\Repositories\SpotlightRepository;
use App\Repositories\ProfileRepository;
use App\Components\Plugin;


class SpotlightApiController extends Controller {

    protected $spotlightRepo;    
    protected $profileRepo;    
    public function __construct() {
        $this->spotlightRepo = app('App\Repositories\SpotlightRepository');
        $this->profileRepo = app('App\Repositories\ProfileRepository');
    }

    public function spotlight (Request $req) {

        $auth_user = $req->real_auth_user;
        $user_credit_balance = $auth_user->credits->balance;
        $spotlight_credits = $this->spotlightRepo->getSpotCredits();

        $spotlights = $this->spotlightRepo->getSpotUsers();
        $spotlight_users = [];

        foreach ($spotlights as $spot) {

            $user = new \stdClass;
            $user->id = $spot['id'];
            $user->name = $spot['name'];
            $profile_pic_url = [
                "thumbnail" => url("uploads/others/thumbnails/{$spot['profile_pic_url']}"),
                "encounter" => url("uploads/others/encounters/{$spot['profile_pic_url']}"),
                "other"     => url("uploads/others/{$spot['profile_pic_url']}"),
                "original"  => url("uploads/others/original/{$spot['profile_pic_url']}"),
            ];
            $user->profile_picture_url = $profile_pic_url;
            $user->profile_picture_name = $spot['profile_pic_url'];

            array_push($spotlight_users, $user);
        }

        return response()->json([ 
            "status" => "success",
            "success_data" => [
                "spotlight_credits"=> $spotlight_credits,
                "user_credit_balance" => $user_credit_balance,
                "spotlight_users" => $spotlight_users,
                "success_text" => "Spotlight users retrived successfully."
            ]
        ]);
            
    }



    public function addToSpotlight (Request $req) {

        $auth_user = $req->real_auth_user;
        $success = $this->spotlightRepo->addToSpotlight($auth_user->id);
        

        if ($success) {
            Plugin::fire('spotlight_pay');  

            return response()->json([
                "status" => "success",
                "success_data" => [
                    "user_credit_balance" => $auth_user->credits->balance,
                    "success_text" => "Added to Spotlight successfully."
                ]
            ]);
        }
          
        return response()->json([
            "status" => "error",
            "success_data" => [
                "success_text" => "Not enoungh credit balance."
            ]
        ]);
                              
                
    }

}
