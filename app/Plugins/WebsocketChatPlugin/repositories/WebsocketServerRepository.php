<?php

namespace App\Repositories;

use App\Repositories\Admin\UtilityRepository;
use App\Components\Plugin;
use App\Models\Settings;

class WebsocketServerRepository {

	public function __construct()
	{
		$this->settings = app('App\Models\Settings');
	}

   	public function startServer() {

   		$chat_server_port = $this->settings->get('websocket_chat_server_port');
   		$server_php_path = $this->settings->get('wesocket_php_path');
   		$server_php_path = rtrim($server_php_path, '/');

		$server_running = $this->server_running();
		if($server_running) {
			return false;
		}


		$server_file_path = $this->serverEntryFilePath();
		shell_exec($server_php_path." $server_file_path start $chat_server_port > /dev/null 2>/dev/null & echo $! > ".$this->process_id_store_path()); 

		return true;
	}


	public  function serverStatus() {

		$chat_server_port = $this->settings->get('websocket_chat_server_port');
   		$server_php_path = $this->settings->get('wesocket_php_path');
   		$server_php_path = rtrim($server_php_path, '/');

   		$server_file_path = $this->serverEntryFilePath();

   		$output = exec($server_php_path." $server_file_path status $chat_server_port"); 

   		return $output;
	}



	public function server_running () {
		$process_id = $this->get_process_id();
		if($process_id > 0) return true;
		else return false;
	}

	public function serverEntryFilePath () {
		return Plugin::path("WebsocketChatPlugin/WebsocketServer/Server.php");
	}

	public function process_id_store_path () {
		$path = base_path()."/storage/WebsocketChatPlugin/process_id.txt";
		if (!file_exists(dirname($path))) {
			mkdir(dirname($path), 0777, true);
			file_put_contents($path, "0");
		}
		return $path;
	}

	public function get_process_id () {
		$path = $this->process_id_store_path();
		try {
			$process_id = intval(file_get_contents($path));
			return $process_id;
		} catch (\Exception $e) {
			return 0;
		}
	}


	public function server_stop() {
		$server_running = $this->server_running();
		if($server_running) {

			$chat_server_port = $this->settings->get('websocket_chat_server_port');
	   		$server_php_path = $this->settings->get('wesocket_php_path');
	   		$server_php_path = rtrim($server_php_path, '/');

	   		$server_file_path = $this->serverEntryFilePath();



			$output = exec($server_php_path." $server_file_path stop $chat_server_port"); 
			file_put_contents($this->process_id_store_path(), "0");
			return true;
		}

		return false;
	}


   	
	public function copyDatabaseConfiguration() {

		$db_config = config('database');

		$this->writeArrayToFile($db_config['connections']['mysql'], Plugin::path('WebsocketChatPlugin/WebsocketServer/database.php'));
	}
   	

	public function writeArrayToFile($arry, $file) {
        try {
            $string = var_export($arry, true);
            $string = "<?php return \n".$string . ";"; 
            file_put_contents($file, $string, LOCK_EX);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function serverSettings()
    {
    	$server_port = $this->settings->get('websocket_chat_server_port');
  		$server_php_path = $this->settings->get('wesocket_php_path') 
  							? $this->settings->get('wesocket_php_path')
  							: $this->settings->get('php_path');

  		$websocket_domain = $this->settings->get('websocket_domain') 
  							? $this->settings->get('websocket_domain')
  							: $this->settings->get('domain');

  		$websocket_secure_mode = $this->settings->get('websocket_secure_mode');
  		$websocket_crt = $this->settings->get('websocket_crt');
  		$websocket_key = $this->settings->get('websocket_key');
  		$websocket_ca = $this->settings->get('websocket_ca');

  		$running = $this->server_running();
  		$server_status = $this->serverStatus();
  	
  		
  		return [
  			"server_port" => $server_port,
  			"server_status" => $server_status,
  			"server_php_path" => $server_php_path,
  			"server_running" => $running ? 'true' : 'false',
  			"server_domain" => $websocket_domain,
  			"secure_mode" => $websocket_secure_mode,
  			"websocket_crt" => $websocket_crt,
  			"websocket_key" => $websocket_key,
  			"websocket_ca" => $websocket_ca,
  		];
    }

    public function saveServerSettings($configs)
    {
    	$this->copyDatabaseConfiguration();

    	foreach($configs as $key => $value) {
    		$this->settings->set($key, $value);
    	}

    	$this->generateAndSaveCertificatePem($configs['websocket_crt'], $configs['websocket_key']);
    	$this->generateAndSaveImtermediate($configs['websocket_ca']);

    	return true;
    }


    public function generateAndSaveCertificatePem($crt_string, $key_string)
    {	

    	$certificate_pem_string = "{$crt_string}\n{$key_string}";
    	file_put_contents($this->certificatePath(), $certificate_pem_string);
    }

    public function certificatePath()
    {
    	return storage_path('certificate.pem');
    }

    public function intermediatePath()
    {
    	return storage_path('intermediate.ca');
    }

    public function generateAndSaveImtermediate($intermediate_string)
    {
    	file_put_contents($this->intermediatePath(), $intermediate_string);
    }

}

