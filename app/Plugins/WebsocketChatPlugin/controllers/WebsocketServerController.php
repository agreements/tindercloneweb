<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Components\Plugin;


class WebsocketServerController extends Controller {
    
    public function __construct()
    {
        $this->serverRepo = app('App\Repositories\WebsocketServerRepository');
    }
	
  	public function serverSettings() {
  	
  		return Plugin::view('WebsocketChatPlugin/admin_server_settings', $this->serverRepo->serverSettings());
  	} 

  	public function saveChatServerSettings (Request $req) {

        $this->serverRepo->saveServerSettings([
            "websocket_chat_server_port" => $req->websocket_chat_server_port,
            "wesocket_php_path" => $req->wesocket_php_path,
            "websocket_domain" => $req->websocket_domain,
            "websocket_crt" => $req->websocket_crt,
            "websocket_key" => $req->websocket_key,
            "websocket_ca" => $req->websocket_ca,
            "websocket_secure_mode" => $req->websocket_secure_mode == 'on' ? 'true' : 'false',
        ]);

  		return response()->json(["status" => "success"]);
  	}


  	public function startChatServer () {
  		
  		$status = $this->serverRepo->startServer();
  		$status = $status ? 'success' : 'error';

        $server_status = $this->serverRepo->serverStatus();

  		return response()->json([
  			"status" => $status, 
  			"output" => $server_status
  		]);
  	}


  	public function stopChatServer () {

  		if ($this->serverRepo->server_stop())
  			return response()->json(["status" => "success"]);
  		else 
  			return response()->json(["status" => "error"]);
  	}

}
