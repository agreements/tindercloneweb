<?php

namespace App\Repositories;

use App\Repositories\Admin\UtilityRepository;
use \Illuminate\Support\Facades\DB;
use App\Models\ChatSocketMap;
use App\Models\ChatContact;
use App\Models\Message;
use App\Models\User;
use App\Models\Photo;
use App\Models\Encounter;
use App\Models\UserSettings;
use App\Models\Settings;
use App\Models\Match;

class WebsocketChatRepository {

	public static function getSetting($key)
	{
		$setting = Settings::where('admin_key', $key)->first();
		return ($setting) ? $setting->value : "";
	}


	public static $blockUserIDs = [];
	public static function setBlockUserIDs($user_id) {
		self::$blockUserIDs = app("App\Repositories\BlockUserRepository")->getAllBlockedUsersIds($user_id);
	}


	public static function deleteChats($user_ids)
	{
		Message::whereIn('from_user', $user_ids)->orWhereIn('to_user', $user_ids)->forceDelete();
	}

	public static function deleteContacts($user_ids)
	{
		ChatContact::where('user1', $user_ids)->orWhereIn('user2', $user_ids)->forceDelete();
	}


	public static function deleteContact($user_id, $contact_id) {

		$contact = self::allowToDelete($user_id, $contact_id);
		
		if(!$contact) {
			return ["status" => "error", "error_type" => "NOT_AUTHORISE_TO_DELETE", 'error_text' => 'Not authorise to delete'];
		}
		
		try {

			\Illuminate\Support\Facades\DB::transaction(function () use($contact) {
			   Message::where('contact_id', $contact->id)->delete();
			   $contact->delete();
			});

			return ["status" => "success", 'success_type' => "CONTACT_DELETED", 'success_text' => "Contact deleted"];
		} catch(\Exception $e) {
			return ["status" => "error", "error_type" => "UNKNOWN_ERROR", 'error_text' => $e->getMessage()];
		}

	}

	public static function allowToDelete($user_id, $contact_id) {
		return ChatContact::where(function($query) use($user_id, $contact_id){
			$query->where('user1', $user_id)->orWhere('user2', $user_id);
		})->where('id', $contact_id)->first();
	}

	public static function saveImage($user_id, $image){

		if (UtilityRepository::validImage($image, $ext)) {
			$filename = UtilityRepository::generate_image_filename("{$user_id}_", $ext);
			$path = base_path("public/uploads/chat");
			$image->move($path, $filename);
			return ["status" => "success", 'success_type' => "IMAGE_UPLOADED" ,"image" => $filename];
		}

		return ["status" => "error", 'error_type' => "INVALID_IMAGE_FILE", 'error_text' => "invalid image"];
	}

	



	public static function markReadAll($user_id) {

		Message::where('to_user', $user_id)->where('status', 'unread')->update(['status' => 'read']);

	}


	public static function deleteMessage($user_id, $message_id){
		$message = Message::where('id', $message_id)->where(function($query) use($user_id){
			$query->where('from_user', $user_id)->orWhere('to_user');
		})->first();

		if ($message) {
			$message->delete();
			return true;
		}

		return false;
	}


	public static function markReadAllOfUser($user1, $user2) {
		Message::where('from_user', $user2)->where('to_user', $user1)->orWhere('contact_id', 0)->where('status', 'unread')->update(['status' => 'read']);
	}



	public static function addContact($user1, $user2, $source = false) {

		$user2 = User::find($user2);

	  	// $chat_limit = UtilityRepository::get_setting('limit_chat');

	   // 	if(!$user1->isSuperPowerActivated() && self::contactsCountOfToday($user1->id) >= $chat_limit) {
		  //  	return ["status" => "error", "error_type" => "USER1_LIMIT"];
	   // 	}

	   	/*if(!$user2->isSuperPowerActivated() && self::contactsCountOfToday($user2->id) >= $chat_limit) {
		   	return ["status" => "error", "error_type" => "USER2_LIMIT"];
	   	}*/
		   
	   	$contact = self::getContact($user1->id, $user2->id);

	   	if ($contact) {
	   		$contact_obj = $contact;
	   		return ["status" => "error", "error_type" => "ALREADY_CONTACTED", 'contact' => $contact];
	   	}

		
		$contact = new ChatContact();
	   	$contact->user1 = $user1->id;
	   	$contact->user2 = $user2->id;
	   	$contact->source = ($source) ? $source : $user1->id;
	   	$contact->save();

	   	$contact_obj = $contact;

	   	return ["status" => "success", "success_type" => 'NEW_CONTACT', 'contact' => $contact];
	   	
   	}


   	public static function contactsCountOfToday($user_id) {
   		return ChatContact::where(function($query) use($user_id){
   			$query->where('user1', $user_id)->orWhere('user2', $user_id);
   		})->where(function($query){
   			$query->whereNotIn('user1', self::$blockUserIDs)->whereNotIn('user2', self::$blockUserIDs);
   		})->where("created_at",">=",date_create(date('Y-m-d')))->count();
   	}




	public static function saveMessage($data) {

		try {

			$message = new Message;
			$message->from_user = $data['from_user'];
			$message->to_user = $data['to_user'];
			$message->contact_id = $data['contact_id'];
			$message->status = 'unread';
			$message->type = $data['message_type'];

			if ($data['message_type'] == 0) {
				$message->text = $data['message_text'];
			} else if ($data['message_type'] == 2) {
				$message->meta = $data['message_text'];
			}

			$message->save();

			if($data['message_type'] == 2) {
				$message->image_url = "uploads/chat/{$data['message_text']}";
			}

			return $message;

		} catch (\Exception $e) {
			return null;
		}
			
	}

	
	public static function mapSocketIDWithUserID ($user_id, $socket_id, $socket_map_for = "web") {

	    /*$chat_socket_map = ChatSocketMap::where('user_id', $user_id)->where('socket_map_for', '=',$socket_map_for)->first();
	    
	    if ($chat_socket_map) {
	      $chat_socket_map->socket_id = $socket_id;
	    } else {
	      $chat_socket_map = new ChatSocketMap;
	      $chat_socket_map->user_id = $user_id;
	      $chat_socket_map->socket_id = $socket_id;
	      $chat_socket_map->socket_map_for = $socket_map_for;
	    }

	    $chat_socket_map->save();
	    return $chat_socket_map;*/


	    $chatSocketMap = ChatSocketMap::where("socket_id", $socket_id)->first();

	    if(!$chatSocketMap) {
	    	$chatSocketMap = new ChatSocketMap;
	    	$chatSocketMap->user_id = $user_id;
	    	$chatSocketMap->socket_id = $socket_id;
	    	$chatSocketMap->socket_map_for = $socket_map_for;
	    	$chatSocketMap->save();
	    }

	    return $chatSocketMap;


	}



	public static function removeSocketUserMap($socketID) 
	{
		$chatSocketMap = ChatSocketMap::where('socket_id', $socketID)->first();

		// $user = self::getUserBySocket($socketID);

		if($chatSocketMap) {
			$chatSocketMap->forceDelete();
		}

		// return $user;
	}




	public static function getMessages ($user_id, $contact_id, $last_message_id) {

		if ($last_message_id == 0) {

			$messages = Message::where('contact_id', $contact_id)->where(function($query)use($user_id){
				$query->where('from_user', $user_id)->orWhere('to_user', $user_id);
			})->where(function($query){
				$query->whereNotIn('from_user', self::$blockUserIDs)->whereNotIn('to_user', self::$blockUserIDs);
			})->orderBy('created_at', 'desc')->take(20)->get();

		} else {

			$messages = Message::where('contact_id', $contact_id)->where(function($query)use($user_id){
				$query->where('from_user', $user_id)->orWhere('to_user', $user_id);
			})->where(function($query){
				$query->whereNotIn('from_user', self::$blockUserIDs)->whereNotIn('to_user', self::$blockUserIDs);
			})->where('id', '<', $last_message_id)->orderBy('created_at', 'desc')->take(20)->get();
		}

		foreach($messages as $message) {
			$message->time_ago = $message->created_at->diffForHumans();
            if ($message->type == 2) {
                $message->image_url = url("uploads/chat/".$message->meta);
            }
        }

        return $messages->reverse();

	}


	public static function getContact($user1, $user2){

		return ChatContact::where(function($query) use($user1, $user2){

			$query->where('user1', $user1)->where('user2', $user2)->orWhere(function($query)use($user1, $user2){
				$query->where('user1', $user2)->where('user2', $user1);
			});

		})->where(function($query){
			$query->whereNotIn('user1', self::$blockUserIDs)->whereNotIn('user2', self::$blockUserIDs);
		})->first();

	}

	public static function getContactID ($user1, $user2) {

		$contact = self::getContact($user1, $user2);
		if ($contact) {
			return $contact->id;
		} else {
			return 0;
		}

	}



	public static function getTotalPhotosCount($user_id) {
		return Photo::where('userid', $user_id)->count();
	}

	protected static $chatInitCountOfTodayStore = [];
	public static function chatInitCountOfToday($userID)
	{

		if(isset(self::$chatInitCountOfTodayStore[$userID])) {
			return self::$chatInitCountOfTodayStore[$userID];
		} else {

			$userIDs = Message::where('from_user', $userID)
								->where('created_at', 'like', date('Y-m-d').' %')
								->groupBy('to_user')
								->select('to_user')
								->get()
								->toArray();

			$userIDs = collect($userIDs)->flatten()->toArray();

			$count = Message::where('from_user', $userID)
								->whereIn('to_user', $userIDs)
								->groupBy('to_user')
								->select(\DB::raw('to_user, count(*) as total'))
								->havingRaw('total = 1')
								->get()
								->count();


			return self::$chatInitCountOfTodayStore[$userID] = $count;
		}
	}

	public static function canInitiateChat(&$user, &$contact, &$error_type)
   	{	
   		$error_type = "";

   		if(self::getTotalMessagesCount($contact->id) > 0) {
   			return true;
   		}


   		$chat_settings_option = UtilityRepository::get_setting('chat_settings_option');
   		$chat_limit = UtilityRepository::get_setting('chat_limit');
   		$chat_initiate_time_bound = UtilityRepository::get_setting('chat_initiate_time_bound');

   		if($chat_settings_option == 'match_only') {

   			if(!$user->isSuperPowerActivated() && $contact->created_at < self::calculateTimestampBySubstractDays($chat_initiate_time_bound)) {
   				$error_type = "CHAT_INIT_HOURS_EXPIRED";
   				return false;
   			}

   		} else {
   			self::chatInitCountOfToday($user->id);
   			if(!$user->isSuperPowerActivated() && self::chatInitCountOfToday($user->id) >= $chat_limit) {
   				$error_type = "CHAT_LIMIT_OF_DAY";
   				return false;
	   		}
   		}
	   	
   		return true;
	   	
   	}


   	public static function calculateTimestampBySubstractDays($hours)
   	{
   		$timestamp = date('Y-m-d H:i:s', strtotime("-{$hours} hours", strtotime(date('Y-m-d H:i:s'))) );
   		
		return $timestamp;
   	}



   	public static function isMatched($firstUserID, $secondUserID) 
   	{
   		return Match::where('user1', $firstUserID)->where('user2', $secondUserID)->count() ? 1 : 0;
   	}



	public static function getChatUsers ($authUser, $last_contact_id) {

		$chat_users = [];

		$contacts_count = self::totalContactsCount($authUser->id);

		/* if contacts count if more that 0 then take users from contact table otherwise take from spotlight */
		if ($contacts_count > 0) {

			if ($last_contact_id > 0) {
				
				$contacts = ChatContact::where(function($query) use($authUser){
					$query->where('user1', $authUser->id)->orWhere('user2', $authUser->id);
				})->where(function($query){
					$query->whereNotIn('user1', self::$blockUserIDs)->whereNotIn('user2', self::$blockUserIDs);
				})->where('id', '<', $last_contact_id)->orderBy('created_at', 'desc')->take(20)->get();

			} else {
				$contacts = ChatContact::where(function($query) use($authUser){
					$query->where('user1', $authUser->id)->orWhere('user2', $authUser->id);
				})->where(function($query){
					$query->whereNotIn('user1', self::$blockUserIDs)->whereNotIn('user2', self::$blockUserIDs);
				})->orderBy('created_at', 'desc')->take(20)->get();
			}
		

			foreach ($contacts as $contact) {

				$contact_user = self::getChatUser($authUser, $contact);

				if ($contact_user == null) continue;

	        	$online = $contact_user->onlineStatus() ? 1 : 0;
    			$matched = self::isMatched($authUser->id, $contact_user->id);
    			
	      		array_push($chat_users, [
	      			"user" => $contact_user, 
	      			"online" => $online, 
	      			'matched' => $matched, 
	      			"is_contacted" => 1, 
	      			"contact_id" => $contact->id,
	      			"contacted_timestamp" => $contact->created_at->toDateTimeString(),
	      		]);
	        }


		} else {

			$only_superpowers = UtilityRepository::get_setting('spotlight_only_superpowers');

	        $users = ($only_superpowers == 'true') ? self::getUsersFromSuperpower($authUser->id) : self::getUsersFromSpotlight($authUser->id);

	        foreach ($users as $user) {

	        	$user->profile_picture = $user->thumbnail_pic_url();

	        	$online = $user->onlineStatus() ? 1 : 0;
	        	$matched = 0;
	        	$user->age = $user->age();

	        	$user->isTyping = false;

	        	$user->last_msg = "";
	        	$user->last_msg_type = "";

	        	$user->messages = [];
	        	$user->total_messages_count = 0;
	        	$user->total_unread_messages_count = 0;


	        	if ($user->last_msg == "") {
		    		$user->total_photos_count = self::getTotalPhotosCount($user->id);
		    		$user->score = self::calculate_score($user->id);
		    	}


	      		array_push($chat_users, ["user" => $user, "online" => $online, 'matched' => $matched, "is_contacted" => 0, "contact_id" => 0]);


	        }
        

		}

		return ["chat_users" => $chat_users, "total_contacts_count" => $contacts_count];
    
        
   	}


   	public static function getUsersFromSpotlight($user_id) {

   		$chat_settings_option = UtilityRepository::get_setting('chat_settings_option');

   		if($chat_settings_option == 'match_only') {
   			return [];
   		}

   		return User::join('spotlight', 'spotlight.userid', '=', 'user.id')
                    ->where('user.activate_user', '<>', 'deactivated')
                    ->where('user.id', "!=", $user_id)
                    ->whereNotIn('user.id', self::$blockUserIDs)
                    ->orderBy('spotlight.updated_at', 'desc')
                    ->select('user.*')
                    ->take(4)
                    ->get();
   	}



   	public static function getUsersFromSuperpower($user_id) {

   		$chat_settings_option = UtilityRepository::get_setting('chat_settings_option');

   		if($chat_settings_option == 'match_only') {
   			return [];
   		}

   		return User::join('user_superpowers', 'user_superpowers.user_id', '=', 'user.id')
   					->where('user.id', "!=", $user_id)
                    ->where('user_superpowers.expired_at', '>=', date('Y-m-d h:i:s'))
                    ->where('user.activate_user', '<>', 'deactivated')
                    ->whereNotIn('user.id', self::$blockUserIDs)
                    ->select('user.*')
                    ->take(4)
                    ->get();
	}

	protected static $totalMessagesCountStore = [];
	public static function getTotalMessagesCount($contact_id) {

		if(isset(self::$totalMessagesCountStore[$contact_id])) {
			return self::$totalMessagesCountStore[$contact_id];
		} else {
			
			return self::$totalMessagesCountStore[$contact_id] = Message::where('contact_id', $contact_id)
																	->where(function($query){
																		$query->whereNotIn('from_user', self::$blockUserIDs)
																			->whereNotIn('to_user', self::$blockUserIDs);
																	})->count();
		}

	}

	protected static $totalUnreadMessagesCountStore = [];
	public static function getTotalUnreadMessagesCount($contact_id, $user_id) {

		return  Message::where('contact_id', $contact_id)->where('from_user', $user_id)
						->where(function($query){
							$query->whereNotIn('from_user', self::$blockUserIDs)->whereNotIn('to_user', self::$blockUserIDs);
						})->where('status', 'unread')->count();

	}

	protected static $overallUnreadMessagesCountStore = [];
	public static function getOverallUnreadMessagesCount($user_id) {

		if(isset(self::$overallUnreadMessagesCountStore[$user_id])) {
			return self::$overallUnreadMessagesCountStore[$user_id];
		} else {

			$count =  Message::where('to_user', $user_id)
						->where(function($query){
							$query->whereNotIn('to_user', self::$blockUserIDs);
						})->where('status', 'unread')->count();

			return self::$overallUnreadMessagesCountStore[$user_id] = $count;
		}

	}


	public static function totalContactsCount($user_id){
		return ChatContact::where(function($query) use($user_id){
			$query->where('user1', $user_id)->orWhere('user2', $user_id);
		})->where(function($query){
			$query->whereNotIn('user1', self::$blockUserIDs)->whereNotIn('user2', self::$blockUserIDs);
		})->count();
	}


   	public static function getLastMessage ($contact_id) {
   		$message = Message::where('contact_id', $contact_id)->orderBy('created_at', 'desc')->first();
   		$last_msg = '';
   		$message_type = 0;

   		if ($message) {
   			if ($message->type == 2) { $last_msg = $message->meta; $message_type = 2; }
   			else if ($message->type == 0) $last_msg = $message->text;
   			else {$last_msg = $message->text; $message_type = $message->type;}
   		}
   		
   		
   		return ['message_type' => $message_type, 'last_msg' => $last_msg];
   	}	



   	public static function getUserBySocket($socket_id) {
   		return User::join('websocket_chat_maps', 'websocket_chat_maps.user_id', '=', 'user.id')
   				->where('websocket_chat_maps.socket_id', $socket_id)->select('user.*')
   				->first();
   	}


   	public static function formatUserData($user) {

    	$user->profile_picture = $user->thumbnail_pic_url();
    	$user->original_picture = $user->profile_pic_url();
    	$user->other_picture = $user->others_pic_url();

    	$user->age = $user->age();

    	unset($user->remember_token);

    	return $user;
   	}




   	public static function getChatUser ($authUser, $contact) {

		$other_user_id = ($contact->user1 == $authUser->id) ? $contact->user2 : $contact->user1;
    	$contact_user = User::find($other_user_id);


    	if (!$contact_user || $contact_user->activate_user == 'deactivated') {
    		return null;
    	}

    	$contact_user->profile_picture = $contact_user->thumbnail_pic_url();
    	$contact_user->age = $contact_user->age();

    	$contact_user->isTyping = false;

    	$last_msg_data = self::getLastMessage($contact->id);
    	$contact_user->last_msg = $last_msg_data['last_msg'];
    	$contact_user->last_msg_type = $last_msg_data['message_type'];


    	$contact_user->messages = [];
    	$contact_user->total_messages_count = self::getTotalMessagesCount($contact->id);
    	$contact_user->total_unread_messages_count = self::getTotalUnreadMessagesCount($contact->id, $other_user_id);

    	if ($contact_user->last_msg == "") {
    		$contact_user->total_photos_count = self::getTotalPhotosCount($contact_user->id);
    		$contact_user->score = self::calculate_score($contact_user->id);
    	}

    	$error_type = "";
    	if(self::canInitiateChat($authUser, $contact, $error_type)) {
    		$contact_user->can_init_chat = true;
    	} else {
    		$contact_user->can_init_chat = false;
    		$contact_user->init_chat_error_type = $error_type;
    	}


	    return $contact_user;
        
   	}


   	public static function calculate_score ($user_id){
   		$total_encounters_get = Encounter::where('user2', $user_id)->count();
		$total_likes_get = Encounter::where('user2', $user_id)->where('likes', 1)->count();

		$score = ($total_encounters_get == 0) ? 0 : (($total_likes_get / $total_encounters_get) * 10);

		return $score;
   	}



   	public static function getContactsUserIDsWithRoom ($user_id) {
   		$user_ids = ChatContact::where(function($query) use($user_id){
   			$query->where('user1', $user_id)->orWhere('user2', $user_id);
   		})->select([DB::raw("(CASE WHEN user1 = ".$user_id . " THEN concat('room_',user2) ELSE concat('room_',user1) END) as user_id")])->get()->toArray();


   		if (empty($user_ids)) return [];
   		$user_ids = array_flatten($user_ids);
   		return array_values($user_ids);
   	}


   	/* this method checks user connected time ago or not */
   	public static function userConnectedTimeAgo ($user, &$online_setting, $time = 0) {
   		$to_time = strtotime(gmdate("Y-m-d H:i:s", time()));
        $from_time = strtotime($user->last_request);
        $minute = round(abs($to_time - $from_time) / 60);
        //$online_setting = $user->settings_get('online_status');

        if ($minute <= $time) {
        	return true;
        }


   		return false;

   	}


   	public static function userSettingByType($userId, $type)
   	{
   		$userSetting = UserSettings::where('userid', $userId)->where('key', $type)->first();
   		return $userSetting ? $userSetting->value : '';
   	}


   	// public static function userConnectedTimeAgoWithChatSocketMap($user, $time = 0) {
   	// 	$chat_socket_map = ChatSocketMap::where('user_id', $user->id)->first();
   	// 	if($chat_socket_map) {

   	// 		$to_time = strtotime(gmdate("Y-m-d H:i:s", time()));
	   //      $from_time = strtotime($chat_socket_map->updated_at);
	   //      $minute = round(abs($to_time - $from_time) / 60);

	   //      if ($minute <= $time) {
	   //      	return true;
	   //      }

   	// 	}
   	// 	return false;
   	// }

   	protected static $timerIDs = [];

   	public static function getTimerID ($userID) {

   		return isset(self::$timerIDs[$userID]) ? self::$timerIDs[$userID] : 0;



   		/*$chat_socket_map = ChatSocketMap::where('user_id', $userID)->where('socket_id', $socketID)->first();
   		if ($chat_socket_map && $chat_socket_map->offline_timer_id != '') {
   			return $chat_socket_map->offline_timer_id;
   		}

   		return '';*/
   	}


   	public static function setTimerID($userID, $timerID) {

   		self::$timerIDs[$userID] = $timerID;


   		/*$chat_socket_map = ChatSocketMap::where('user_id', $userID)->where('socket_id', $socketID)->first();
   		if($chat_socket_map) {
   			$chat_socket_map->offline_timer_id = $timerID;
   			$chat_socket_map->save();
   			return $chat_socket_map;
   		}
   		return false;*/
   	}


   	public static function delTimerID($userID)
   	{
   		if(isset(self::$timerIDs[$userID])) {
   			unset(self::$timerIDs[$userID]);
   		}

   	}



   	public static function serverDetails()
   	{
   		$details = [];
   		$details['domain'] = UtilityRepository::get_setting('websocket_domain');
   		$details['port'] = (int)UtilityRepository::get_setting('websocket_chat_server_port');
   		$details['url'] = "{$details['domain']}:{$details['port']}";
   		return $details;
   	}



   	public static function getPushRepoInstance()
   	{
   		return app('App\Plugins\NotificatinsPlugin\Repositories\PushNotificationRepository');
   	}


   	public static function getUserDetailsForNotification($userID)
   	{
   		$user = User::where('id', $userID)->select(['id', 'name', 'slug_name', 'profile_pic_url'])->first();
   		if($user) {
   			$user->profile_pic_url = $user->thumbnail_pic_url();
   			return $user;
   		}

   		return null;
   	}


}

