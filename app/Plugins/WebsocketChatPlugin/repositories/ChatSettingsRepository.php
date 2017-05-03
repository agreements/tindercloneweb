<?php

namespace App\Repositories;

class ChatSettingsRepository 
{

   public function __construct()
   {
        $this->settings = app('App\Models\Settings');
   }

   public function settings()
   {
   		return [

   			"chatSettingsOption" => $this->settings->get('chat_settings_option'),
	   		"chatLimit" => $this->settings->get('chat_limit'),
	   		"chatInitiateTimeBound" => $this->settings->get('chat_initiate_time_bound'),

   		];
   }


   public function saveSettings($dataArray)
   {
   		foreach($dataArray as $key => $value) {
   			$this->settings->set($key, $value);
   		}

   		return $this->settings();
   }	


}
