<?php

use App\Components\PluginInstall;
use App\Models\PaymentGateway;

class PaypalPluginInstall extends PluginInstall
{
	public function install()
	{
		$this->createEntryPaymentGatewaysTable();
	}

	public function createEntryPaymentGatewaysTable()
	{
		$stripeGateWay = new PaymentGateway;
		$stripeGateWay->name = 'paypal';
		$stripeGateWay->type = 'non-stored';
		$stripeGateWay->save();
	}

	public function uninstall()
	{
		
	}

}