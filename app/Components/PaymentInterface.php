<?php

namespace App\Components;


use Illuminate\Http\Request;

interface PaymentInterface
{
	//abstact methods
	public function gateway_packages($args);
	public function payment_callback($args);
	public function payment_refund($contents);
	
	public function all_packages($gateway_id);
	public function add_gateway_package($gateway_id, $package_id);
	public function remove_gateway_package($gateway_d, $package_id);
}