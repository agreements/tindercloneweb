<?php 

namespace App\Plugins\NotificatinsPlugin\Repositories;

class PushNotificationRepository
{
	public function __construct() 
	{
		$this->pushNotification = app('App\Plugins\NotificationsPlugin\Models\PushNotification');
		$this->settings = app('App\Models\Settings');

		$this->init();
	}


	protected function init()
	{
		$this->project_number = $this->settings->get('firebase_push_notification_project_number');
		$this->web_api_key = $this->settings->get('firebase_push_notification_web_api_key');
		$this->android_api_key = $this->settings->get('firebase_push_notification_android_api_key');
		$this->ios_api_key = $this->settings->get('firebase_push_notification_ios_api_key');

		$this->setGooglePushNotificationURL('https://fcm.googleapis.com/fcm/send');


		return $this;
	}



	public function getProjectNumber()
	{
		return $this->project_number;
	}


	public function setProjectNumber($projectNumber)
	{
		$this->project_number = $projectNumber;
		return $this;
	}



	public function getWebApiKey()
	{
		return $this->web_api_key;
	}



	public function setWebApiKey($webAPIKey)
	{
		$this->web_api_key = $webAPIKey;
		return $this;
	}



	public function getAndroidApiKey()
	{
		return $this->android_api_key;
	}



	public function setAndroidApiKey($androidAPIKey)
	{
		$this->android_api_key = $androidAPIKey;
		return $this;
	}



	public function getIosApiKey()
	{
		return $this->ios_api_key;
	}


	public function setIosAPIKey($iosAPIKey)
	{
		$this->ios_api_key = $iosAPIKey;
		return $this;
	}




	public function persist()
	{
		$this->settings->set('firebase_push_notification_project_number', $this->project_number);
		$this->settings->set('firebase_push_notification_web_api_key', $this->web_api_key);
		$this->settings->set('firebase_push_notification_android_api_key', $this->android_api_key);
		$this->settings->set('firebase_push_notification_ios_api_key', $this->ios_api_key);

		return $this;
	}



	public function registerDevice($userID, $deviceID, $deviceToken)
	{
		return $this->pushNotification->registerDevice($userID, $deviceID, $deviceToken);
	}


	public function deviceTokens($userID) 
	{
		return $this->pushNotification->deviceTokens($userID);
	}



	public function getGooglePushNotificationURL()
	{
		if(isset($this->googlePushNotificationURL)) {
			return $this->googlePushNotificationURL;
		}

		return '';
	}


	public function setGooglePushNotificationURL($URL)
	{
		$this->googlePushNotificationURL = $URL;
		return $this;
	}




	public function setTitle($title) 
	{
		$this->notification_title = $title;
		return $this;
	}

	public function getTitle() 
	{
		if(isset($this->notification_title)) {
			return $this->notification_title;
		}

		return '';
	}


	public function setBody($body)
	{
		$this->notification_body = $body;
		return $this;
	}

	public function getBody()
	{
		if(isset($this->notification_body)) {
			return $this->notification_body;
		}

		return '';
	}


	public function setIcon($icon)
	{
		$this->notification_icon = $icon;
		return $this;
	}

	public function getIcon()
	{
		if(isset($this->notification_icon)) {
			return $this->notification_icon;
		}

		return '';
	}


	public function setClickAction($actionURL)
	{
		$this->notification_action_url = $actionURL;
		return $this;
	}

	public function getClickAction()
	{
		if(isset($this->notification_action_url)) {
			return $this->notification_action_url;
		}

		return '';
	}



	public function setCustomPayload($array = []) 
	{
		$this->customPayload = $array;
		return $this;
	}


	public function getCustomPayload()
	{
		if(isset($this->customPayload)) {
			return $this->customPayload;
		}

		return [];
	}



	public function sendPushNotifications($userID)
	{
		$deviceTokens = $this->deviceTokens($userID);

		$responses = [];

		$responses['web'] = $this->sendWebPushNotifications($deviceTokens);		
		$responses['android'] = $this->sendAndroidPushNotifications($deviceTokens);		
		return $responses;
	}



	protected function buildNotification()
	{
		return [
			'title' => $this->getTitle(),
			'body' => $this->getBody(),
			'icon' => $this->getIcon(),
			'click_action' => $this->getClickAction(),
			'custom_data' => $this->getCustomPayload(),
		];
	}


	protected function sendAndroidPushNotifications($deviceTokens = []) {


	    $fields = [
			'registration_ids' => $deviceTokens,
	        'data' => [
	            "notification" => $this->buildNotification()
	        ]
	    ];

	    $fields = json_encode ( $fields );

	    $headers = array (
	        'Authorization: key=' . $this->getAndroidApiKey(),
	        'Content-Type: application/json'
	    );

	    return $this->postURL(
	    	$this->getGooglePushNotificationURL(),
	    	$headers,
	    	$fields
	    );
	}




	protected function sendWebPushNotifications($deviceTokens = []) {


	    $fields = [
			'registration_ids' => $deviceTokens,
	        'data' => [
	            "notification" => $this->buildNotification()
	        ]
	    ];

	    $fields = json_encode ( $fields );

	    $headers = array (
	        'Authorization: key=' . $this->getWebApiKey(),
	        'Content-Type: application/json'
	    );

	    return $this->postURL(
	    	$this->getGooglePushNotificationURL(),
	    	$headers,
	    	$fields
	    );
	}




	protected function postURL($url, $headers, $fields)
	{
		try {

			$ch = curl_init ();
		    curl_setopt ( $ch, CURLOPT_URL, $url );
		    curl_setopt ( $ch, CURLOPT_POST, true );
		    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
		    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

		    $result = curl_exec ( $ch );
		    curl_close ($ch);
		    return $result;


		} catch(\Exception $e) {
			return $e->getMessage();
		}
	}

}