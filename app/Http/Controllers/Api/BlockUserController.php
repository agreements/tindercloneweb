<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Repositories\BlockUserRepository;

class BlockUserController extends Controller {
    
    protected $blockRepo;
    public function __construct () {
        $this->blockRepo = app("App\Repositories\BlockUserRepository");
    }


    //this function shows block users views
    public function getBlockUsers (Request $req) {
        
        $auth_user = $req->real_auth_user;
        $blocks = $this->blockRepo->getBlockedUsers($auth_user->id);
        
        $count = $blocks->count;

        foreach ($blocks->users as $user) {
            unset($user->password);
            $user->activated = substr($user->activate_user, 0);
            unset($user->activate_user);
            $user->age = $user->age();
        }

        return response()->json([
            "status" => "success",
            "success_data" => [
                "block_users_count" => $count,
                "block_users" => $blocks->users,
                "success_text" => "Blocked users list retrived successfully."
            ]
        ]);
    }


    
    public function blockUser (Request $req) {

        $auth_user = $req->real_auth_user;
        $block_user_id = $req->block_user_id;

        if (!$block_user_id) {
            return response()->json([
                "status" => 'error', 
                'error_data' => [
                    "error_text" => 'User block_user_id is required.',
                ]
            ]);
        }

        if ($auth_user->id == $block_user_id) {
            return response()->json([
                "status" => 'error', 
                'error_data' => [
                    "error_text" => 'You cant block yourself.',
                ]
            ]);
        }

        $this->blockRepo->blockUser ($auth_user->id, $block_user_id);

        return response()->json([
            'status' => 'success',
             'success_data' => [
                "success_text" => "User blocked successfully."
             ]
        ]);
        
    }



    public function unBlockUser (Request $req) {

        $auth_user = $req->real_auth_user;
        $blocked_user_id = $req->blocked_user_id;

        if (!$blocked_user_id) {
            return response()->json([
                "status" => 'error', 
                'error_data' => [
                    "error_text" => 'User blocked_user_id is required.',
                ]
            ]);
        }

        if ($auth_user->id == $blocked_user_id) {
            return response()->json([
                "status" => 'error', 
                'error_data' => [
                    "error_text" => 'You cant unblock yourself.',
                ]
            ]);
        }


        $this->blockRepo->unblockUser ($auth_user->id, $blocked_user_id);
       
        return response()->json([
            'status' => 'success',
             'success_data' => [
                "success_text" => "User unblocked successfully."
             ]
        ]);
         
    }
	
}
