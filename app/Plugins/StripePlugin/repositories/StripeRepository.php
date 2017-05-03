<?php

namespace App\Repositories;

use Validator;
use App\Models\Settings;
use Hash;
use App\Repositories\CreditRepository;
// use App\Repositories\ProfileRepository;

class StripeRepository
{
	private $creditRepo;
	
	public function __construct()
	{
		$this->creditRepo = app("App\Repositories\CreditRepository");
	}

	public function get_stripe_api_key()
	{
		return Settings::_get('stripe_api_key');
	}

	public function getStripePublishableKey () {

		return Settings::_get('stripe_publishable_key');
	}

}