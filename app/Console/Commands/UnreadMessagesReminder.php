<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Models\User;
use Mail;
use stdCLass;
use App\Components\Plugin;
use App\Models\Message;
use DB;

class UnreadMessagesReminder extends Command
{	
   
    protected $signature = 'unread_messages_reminder';
    protected $description = 'Send email for unread messages';

    public function __construct(User $user, Message $message) 
    {
    	Parent::__construct();
		$this->user    = $user;
		$this->message = $message;
    }

    public function handle()
    {    	

    	$this->user
    		->select($this->selectUserColums())
    		->chunk(100, function($users){

    			foreach($users as $user){
    				
    				$unread_messages_count = $this->unreadMessagesCount($user->id);
    				$online = $this->isOnline($user);

    				if($unread_messages_count > 0 && !$online) {

    					//send email notification
    					$user->unread_messages_count = $unread_messages_count;
    					$this->sendUnreadMessagesReminder($user);
    				}
	
    				
    			}

    		});


        
    }

    public function isOnline ($user) {

        $to_time   = strtotime( date('Y-m-d H:i:s') );
        $from_time = strtotime($user->last_request);
        $minute    = round(abs($to_time - $from_time) / 60);
        
        return ($minute <= 10) ? true : false;
    }

    public function sendUnreadMessagesReminder($user)
    {
        $email_array = new stdCLass;
        $email_array->user = $user;
        $email_array->type = "unread_message_reminder";
        $res = Plugin::fire('send_email', $email_array);
    }

    public function unreadMessagesCount($user_id)
    {
    	return $this->message->where('to_user', $user_id)
    					->where('status', 'unread')
    					->count();
    }

    public function selectUserColums()
    {
    	return [
    		'id', 'name', 'username', 'dob', 'last_request'
    	];
    }

}
