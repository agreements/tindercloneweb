<?php

namespace App\Components;

//this class is used to load plugin classes
use Illuminate\Support\ClassLoader;

//use of Plugins model
use App\Plugins;

//use of storage path
use Storage;

//used to register and fire event
use Event;
use Mail;


//This Plugin class in the main class 
//that does every thing for our plugin system

class Email extends Mail
{

	//this method is used to get and set Laravel's $event object
	//this method is being called from EventServiceProvider boot method
	public static function send($msg,$subject,$user)
	{
		Mail::raw($msg, function($message) use ($user,$subject) {	
				$message->from("test@squareloop.me", "Your Application");
	            $message->to($user->username, $user->name)->subject($subject);
			}); 
	}

}
