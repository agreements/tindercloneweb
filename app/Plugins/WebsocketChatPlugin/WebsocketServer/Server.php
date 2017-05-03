<?php

use Workerman\Worker;
use Workerman\Lib\Timer;
use PHPSocketIO\SocketIO;
use App\Repositories\WebsocketChatRepository as chatRepo;
use App\Models\ChatContact;
use App\Models\Notifications;
use App\Models\User;
use App\Components\Plugin;

require_once "ServerConfigurationAutoloader.php";

$pushRepo = chatRepo::getPushRepoInstance();

$server_port = $argv[2];


$secure_enabled = chatRepo::getSetting('websocket_secure_mode') == 'true' ? true : false;

if($secure_enabled) {
	
	$context_option['ssl'] = [
		'local_cert' => $cert_file_path,
		"verify_peer" => false,
		"cafile" => $ca_file_path,
	];

	$io = new SocketIO($server_port, $context_option);

} else {
	
	$io = new SocketIO($server_port);
}


date_default_timezone_set('UTC');



$io->on('connection', function($socket) use($io, $pushRepo) {

	echo "\n new user connected";

	/* send socket id to client for socket on connection */
	$socket->emit('connected', ["socket_id" => $socket->id]);
	
	
	/* hooks for websocket */
	Plugin::fire("websocket", ["io" => &$io, "socket" => &$socket, "pushRepo" => &$pushRepo]);
	

	
	/* when socket id and user id mapped then change socket id to corresponding user id 
	   retriving details from websocket_chat_maps table 
	*/
	$socket->on("user_socket_mapped", function($data) use($socket, $io){

		$user = chatRepo::getUserBySocket($socket->id);
		if ($user) { 
			$socket->join("room_{$user->id}"); 

			Plugin::fire('user_socket_mapped', ["user" => &$user]);
			
			$online_setting = chatRepo::userSettingByType($user->id, 'online_status');

			if ($online_setting != '0') {
				$rooms = chatRepo::getContactsUserIDsWithRoom($user->id);

				foreach ($rooms as $room) {
					$io->to($room)->emit('user_online', ["user_id" => $user->id]);
				}
			}

		}
		
	});





	/* when user sends message */
	/* retrive socket id from socket maping table and send message to that user */
	$socket->on('new_message', function($data) use ($io, $socket, $pushRepo){

		Plugin::fire('before_new_message_emitted', ["message" => &$data]);

		/* saving message to database */
		$message = chatRepo::saveMessage($data);

		if($message) {
			$message->total_unread_messages_count = chatRepo::getTotalUnreadMessagesCount($data['contact_id'], $data['from_user']);	
			$message = $message->toArray();
		} else {
			$message = [];
		}
		
		

		/* this event send to whom message is intended for */
		$io->to("room_".$data['to_user'])->emit("new_message_received", $message);


		/* this event send to back to socket user who sent that message */
		if(isset($message['total_unread_messages_count'])) {
			unset($message['total_unread_messages_count']);
		}
		
		$io->to("room_".$data["from_user"])->emit("new_message_sent", $message);

		

		Plugin::fire('new_message_emitted', ["message" => &$message]);



		$user = chatRepo::getUserDetailsForNotification($data['from_user']);
		if(!$user) return;

		$pushRepo->setTitle('New Message');
		$pushRepo->setBody($user->name." has sent you a message.");
		$pushRepo->setIcon($user->profile_pic_url);
		$pushRepo->setClickAction('');
		$pushRepo->setCustomPayload(['user_id' => $user->id, 'user_name' => $user->name, 'notification_type' => 'new_message']);

		$pushRepo->sendPushNotifications($data['to_user']);


	});







	/* handle user typing and typing_stop events */

	$socket->on('typing', function($data) use($io){
		$io->to("room_".$data['to_user'])->emit("typing", $data);
	});

	$socket->on('typing_stop', function($data) use($io){
		$io->to("room_".$data['to_user'])->emit("typing_stop", $data);
	});


	/* end handle user typing and typing_stop events */



	$socket->on('contact_deleted', function($data) use($io, $pushRepo){

		$io->to("room_".$data["to_user"])->emit("contact_deleted", ["contact_id" => $data["contact_id"]]);

		$user = chatRepo::getUserDetailsForNotification($data['to_user']);
		if(!$user) return;
		
		$pushRepo->setTitle('Chat Removed');
		$pushRepo->setBody($user->name." has removed you from chat");
		$pushRepo->setIcon($user->profile_pic_url);
		$pushRepo->setClickAction('');
		$pushRepo->setCustomPayload(['user_id' => $user->id, 'user_name' => $user->name, 'notification_type' => 'contact_deleted']);

		$pushRepo->sendPushNotifications($data['to_user']);

	});


	$socket->on('user_blocked', function($data) use($io){

		$io->to("room_".$data["to_user"])->emit("user_blocked", ["blocked_user_id" => $data["blocked_user_id"]]);

	});

	$socket->on('user_unblocked', function($data) use($io){

		$io->to("room_".$data["to_user"])->emit("user_unblocked", ["unblocked_user_id" => $data["unblocked_user_id"]]);

	});




	/* remove socket from room when user disconnected */
	$socket->on("disconnect", function() use($socket, $io){

		$socketID = $socket->id;

		$user = chatRepo::getUserBySocket($socketID);

		if ($user) { 
			
			$socket->leave("room_{$user->id}"); 

			chatRepo::removeSocketUserMap($socketID);

			echo $user->name . " left\n";
			
			if(!isset($socket->adapter->rooms["room_{$user->id}"]) || count($socket->adapter->rooms["room_{$user->id}"]) < 1) {


				$count = 0;
				$timer_id = Timer::add(1, function() use(&$io, &$socket, $user, &$timer_id, &$count){

					
					if($count == 5) {
						


						if(!isset($socket->adapter->rooms["room_{$user->id}"])) {



							$rooms = chatRepo::getContactsUserIDsWithRoom($user->id);
							foreach ($rooms as $room) {
								$io->to($room)->emit('user_offline', ["user_id" => $user->id]);
							}
							
							$user->last_request = \Carbon\Carbon::now()->subMinutes(10)->toDateTimeString();
							$user->save();
							echo $user->name . " offline\n";


						}

						
						Timer::del($timer_id);
					}

					$count++;

				});
				

			}


		}
	});

});








/* database contacts table new entry listener */
$prev_contact_count = ChatContact::count();
$new_contact_callback = function($io) use(&$prev_contact_count, &$pushRepo){

            	
    $current_count = ChatContact::count();


	//do your stuf inside this if else
	if ($current_count > $prev_contact_count) {


		$new_entries_count = $current_count - $prev_contact_count;

		
		$last_contacts = ChatContact::orderBy('created_at', 'desc')->take($new_entries_count)->get();

		echo "\nnew contact\n";

		foreach($last_contacts as $lastContact) {

			/* send that user has get contact with each other */
    		$io->to("room_".$lastContact->user1)->emit("new_user_contacted", ["user_id" => $lastContact->user2]);
    		$io->to("room_".$lastContact->user2)->emit("new_user_contacted", ["user_id" => $lastContact->user1]);

    		$user = chatRepo::getUserDetailsForNotification($lastContact->user2);
			if(!$user) return;
			
			$pushRepo->setTitle('New Contact');
			$pushRepo->setBody($user->name." has added you in chat");
			$pushRepo->setIcon($user->profile_pic_url);
			$pushRepo->setClickAction('');
			$pushRepo->setCustomPayload(['user_id' => $user->id, 'user_name' => $user->name, 'notification_type' => 'new_user_contacted']);

			$pushRepo->sendPushNotifications($lastContact->user1);


			$user = chatRepo::getUserDetailsForNotification($lastContact->user1);
			if(!$user) return;
			
			$pushRepo->setTitle('New Contact');
			$pushRepo->setBody($user->name." has added you in chat");
			$pushRepo->setIcon($user->profile_pic_url);
			$pushRepo->setClickAction('');
			$pushRepo->setCustomPayload(['user_id' => $user->id, 'user_name' => $user->name, 'notification_type' => 'new_user_contacted']);

			$pushRepo->sendPushNotifications($lastContact->user2);



		}
	}




    $prev_contact_count = $current_count;


};

/* end database contacts table new entry listener */




/* notifications listen to notifications table */
$prev_notif_count = Notifications::count();
$notification_callback = function($io) use(&$prev_notif_count, &$pushRepo){

    $current_count = Notifications::count();



	//do your stuf inside this if else
	if ($current_count > $prev_notif_count) {
		
		$new_entries_count = $current_count - $prev_notif_count;

		$notifications = Notifications::orderBy('created_at', 'desc')->take($new_entries_count)->get();

		echo "\nnew notification\n";
		
		foreach($notifications as $notification) {

			$from_user = User::find($notification->from_user);
			$to_user = User::find($notification->to_user);
				
			if(!$to_user) continue;

			$io->to("room_{$notification->from_user}")
				->emit('notifications', [
					"notification" => $notification,
					"from_user" => $from_user ? $from_user : 0,
					"to_user" => $to_user ? $to_user : 0
				]);
			
			$io->to("room_{$notification->to_user}")
				->emit('notifications', [
					"notification" => $notification,
					"from_user" => $from_user ? $from_user : 0,
					"to_user" => $to_user ? $to_user : 0
				]);


			
			$pushRepo->setTitle('New Notification');
			$pushRepo->setBody('');
			$pushRepo->setIcon(($from_user) ? $from_user->profile_pic_url() : "");
			$pushRepo->setClickAction('');
			$pushRepo->setCustomPayload(['user_id' => $from_user ? $from_user->id : 0, 'user_name' => $from_user ? $from_user->name : "Admin", 'notification_type' => $notification->type]);

			$pushRepo->sendPushNotifications($to_user->id);




		}

	}

    $prev_notif_count = $current_count;

};

/* end notifications listen to notifications table */





$io->worker->onWorkerStart = function() use(&$io, &$notification_callback, &$new_contact_callback, &$pushRepo) {

	$time_interval = 1;
	Timer::add($time_interval, function() use(&$io, &$notification_callback, &$new_contact_callback, &$pushRepo) {

		$new_contact_callback($io);
		$notification_callback($io);

		Plugin::fire("websocket_timer", ["io" => $io,  "pushRepo" => $pushRepo]);

	});


};





//start all workers
Worker::runAll();






