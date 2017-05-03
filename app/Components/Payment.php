<?php

namespace App\Components;

class Payment
{
	
	public static $payment_classes = array();
	
	public static function register($type,$class) {
	   
	   self::$payment_classes[$type] = $class;
   	}
	
	public static function get_class($type) {
		
		if(array_key_exists($type, self::$payment_classes)) {
			
			return self::$payment_classes[$type];
			
		} else {
			
			return null;
		}
	}
	
}	

Payment::register("credit","App\Repositories\CreditRepository");
Payment::register("superpower","App\Repositories\SuperpowerRepository");