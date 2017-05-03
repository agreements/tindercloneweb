<?php
	
	namespace App\Events;
	
	
	
	class TestEvent {
		
		
		public function test($args = array()){
			
			dd("Test Fired");
		}
	}