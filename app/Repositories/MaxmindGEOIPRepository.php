<?php

namespace App\Repositories;

use GeoIp2\WebService\Client;
use App\Models\Settings;

class MaxmindGEOIPRepository 
{
	
	protected $client          = null;
	protected $settings        = null;
	protected $app_id          = '';
	protected $app_license_key = '';
	protected $enabled         = false;


	public function __construct(Settings $settings) 
	{
		$this->settings = $settings;
		$this->app_id = $this->settings->_get('maxmind_geoip_app_id');
		$this->app_license_key = $this->settings->_get('maxmind_geoip_app_license_key');
		$this->enabled = $this->settings->_get('maxmind_geoip_enabled') == 'true' ? true : false;
		$this->client = new Client($this->app_id, $this->app_license_key);
	}


	public function persist()
	{
		$this->settings->set("maxmind_geoip_app_id", $this->app_id);
		$this->settings->set("maxmind_geoip_app_license_key", $this->app_license_key);
		$this->settings->set(
			"maxmind_geoip_enabled", 
			($this->enabled ? 'true' : 'false')
		);
		$this->client = new Client($this->app_id, $this->app_license_key);
		return $this;
	}


	public function getAppID()
	{
		return $this->app_id;
	}

	public function setAppID($appId) 
	{
		$this->app_id = $appId;
		return $this;
	}

	public function setLicenseKey($app_license_key) {
		$this->app_license_key = $app_license_key;
		return $this;
	}

	public function getLicenseKey()
	{
		return $this->app_license_key;
	}

	public function enabled() {
		return $this->enabled;
	}

	public function enable($enable = true) 
	{
		$this->enabled = $enable;
		return $this;
	}

	public function disable()
	{
		$this->enabled = false;
		return $this;
	}

	public function getClient()
	{
		return $this->client;
	}


	public function removeRegistrationValidatorParamters($arr) 
	{
		if (!$this->enabled) 
			return $arr;

		if (isset($arr['lat']))
		 	unset($arr['lat']);
		
		if (isset($arr['lng']))
			unset($arr['lng']);
		
		if (isset($arr['city']))
			unset($arr['city']);
		
		if (isset($arr['country']))
			unset($arr['country']);

		return $arr;
	}

	public function setCityCountryLatitudeLongitude($data = [])
	{	
		if (!$this->enabled) return $data;

		$this->fetchCurrentLocation();

		$data['city']      = $this->city;
		$data['country']   = $this->country;
		$data['latitude']  = $this->latitude;
		$data['longitude'] = $this->longitude;

		return $data;
	}

	public function fetchCurrentLocation()
	{
		if (!$this->enabled) return false;

		$record = $this->client->city(
			request()->ip()
		);

		$this->city      = $record->city->name;
		$this->country   = $record->country->name;
		$this->latitude  = $record->location->latitude;
		$this->longitude = $record->location->longitude;

		return $this;

	}



	public function updateOnlyUserLocation (&$user) 
	{
		if (!$this->enabled) return $this;

		$user->city = $this->city;
		$user->country = $this->country;
		$user->latitude = $this->latitude;
		$user->longitude = $this->longitude;
		$user->save();
		return $this;
	}

	public function updateOnlyUserProfileLocation(&$user)
	{
		if (!$this->enabled) return $this;

		$user->profile->latitude = $this->latitude;
		$user->profile->longitude = $this->longitude;
		$user->profile->save();
		return $this;
	}


	public function updateUserLocation(&$user)
	{
		if (!$this->enabled) return $this;

		$this->fetchCurrentLocation();
		return $this->updateOnlyUserLocation($user)
			->updateOnlyUserProfileLocation($user);
	}


}

