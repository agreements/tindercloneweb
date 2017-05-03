<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ChatSettingsRepository;
use App\Components\Plugin;

class ChatSettingsController extends Controller 
{

   public function __construct()
   {
        $this->chatSetRepo = app('App\Repositories\ChatSettingsRepository');
   }

   public function getSettings()
   {
        return Plugin::view('WebsocketChatPlugin/chat_settings', ['chatSettings' => $this->chatSetRepo->settings()]);
   }    

   public function postSave(Request $req)
   {
        $this->chatSetRepo->saveSettings([
            "chat_settings_option" => $req->chat_settings_option,
            "chat_initiate_time_bound" => $req->chat_initiate_time_bound,
            "chat_limit" => $req->chat_limit,
        ]);

        return response()->json(["status" => "success"]);
   }


}
