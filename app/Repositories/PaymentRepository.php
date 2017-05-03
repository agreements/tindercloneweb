<?php

namespace App\Repositories;

use App\Models\Payment as PaymentModel;
use App\Models\PaymentGateway;
use App\Models\Transaction;

use App\Components\Payment;

class PaymentRepository
{
	
	public function __construct(Transaction $transaction,PaymentModel $payment, PaymentGateway $paymentGateway){
		
		$this->payment = $payment;
		$this->paymentGateway = $paymentGateway;
		$this->transaction = $transaction;
	}
	
	
	public function payment_type($type)
	{
		$payment = $this->payment->where('name', '=', $type)->first();
		return $payment;
	}
	
	public function payment_callback($contents) {
		
		$trans = $this->transaction;

		$trans->gateway = $contents['gateway'];
		$trans->transaction_id = $contents['transaction_id'];
		$trans->amount = $contents['amount'];

		if ($contents['status'] == 'SuccessWithWarning' || $contents['status'] == 'Success' || $contents['status'] == 'succeeded') {
			
			$trans->status = 'success';
			$trans->save();
			
			$contents["transTable_id"] = $trans->id;
		} else
        {
            $trans->status = 'failure';
            $trans->save();
        }
		
		$typeController = app(Payment::get_class($contents["feature"]));
		$packages = $typeController->payment_callback($contents);
		
	}
	
	public function payment_refund($contents) {
		
		 $trans = $this->transaction;

		$trans->gateway = $contents['gateway'];
		$trans->transaction_id = $contents['transaction_id'];
		$trans->amount ="-".$contents['amount'];

		if ($contents['status'] == 'SuccessWithWarning' || $contents['status'] == 'Success' || $contents['status'] == 'succeeded') {
			
			$trans->status = 'success';
			$trans->save();
			
			$contents["transTable_id"] = $trans->id;
		} else
        {
            $trans->status = 'failure';
            $trans->save();
        }
		
		$typeController = app(Payment::get_class($contents["feature"]));
		$packages = $typeController->payment_refund($contents);
		
	}
	
	public function stored_payment_packages($gateway) {
		
		$payments = $this->payment->where("type","stored")->get();
		
		$gateway = $this->paymentGateway->where("name",$gateway)->first();
		
		foreach($payments as $payment) {
			
			$name = $payment->name;
			
			$typeController = app(Payment::get_class($name));
			$payment->packages = $typeController->all_packages($gateway->id);			
		}
		
		return $payments; 
	}
	
	public function add_gateway_package($type,$gateway,$package_id) {
		
		$gateway = $this->paymentGateway->where("name",$gateway)->first();
		
		$typeController = app(Payment::get_class($type));
		$typeController->add_gateway_package($gateway->id,$package_id);
		
	}
	
	public function remove_gateway_package($type,$gateway,$package_id) {
		
		$gateway = $this->paymentGateway->where("name",$gateway)->first();
		
		$typeController = app(Payment::get_class($type));
		$typeController->remove_gateway_package($gateway->id,$package_id);
		
	}
	
}
