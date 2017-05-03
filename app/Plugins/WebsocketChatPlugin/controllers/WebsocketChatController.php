<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Admin\UtilityRepository;
use App\Repositories\WebsocketChatRepository as chatRepo;
use Auth;


class WebsocketChatController extends Controller {

    public function __construct(){

        $auth_user = Auth::user();
        
        if ($auth_user) {
            chatRepo::setBlockUserIDs($auth_user->id);
        }
    }

    
    public function deleteContact(Request $req) {
        $auth_user = Auth::user();
        $contact_id = $req->contact_id;

        $response = chatRepo::deleteContact($auth_user->id, $contact_id);
        return response()->json($response);
    }

	
  	public function mapUserSocket (Request $req) {

        $socket_id = $req->socket_id;
        $auth_user = Auth::user();

        chatRepo::mapSocketIDWithUserID($auth_user->id, $socket_id);

        return response()->json(["status" => "success"]);

    }


    public function getChatUsers(Request $req) {

    	$last_contact_id = isset($req->last_contact_id) ? $req->last_contact_id : 0;
    	$auth_user = Auth::user();

    	$chat_data = chatRepo::getChatUsers($auth_user, $last_contact_id);

    	return response()->json(["status" => "success", "chat_users" => $chat_data["chat_users"], "total_contacts_count" => $chat_data["total_contacts_count"], "overall_unread_messages_count" => chatRepo::getOverallUnreadMessagesCount($auth_user->id), ]);
    }


    public function getMessages(Request $req) {

        $last_message_id = $req->last_message_id ? $req->last_message_id : 0;
        $user_id = $req->user_id;
        $auth_user = Auth::user();
        $contact_id = chatRepo::getContactID($auth_user->id, $user_id);

        $messages = chatRepo::getMessages($user_id, $contact_id, $last_message_id);        

        return response()->json(["status" => "success", "messages" => $messages]);

    }


    public function getChatUser(Request $req) {
        $user_id = $req->user_id;
        $auth_user = Auth::user();
        $contact = chatRepo::getContact($auth_user->id, $user_id);
        $chat_data = chatRepo::getChatUser($auth_user, $contact);

        $online = $chat_data->onlineStatus() ? 1 : 0;
        $matched = chatRepo::isMatched($auth_user->id, $user_id);


        return response()->json(["status" => "success", "chat_user" => [
                "user" => $chat_data, 
                "online" => $online,
                "matched" => $matched,
                "is_contacted" => 1,
                "contact_id" => $contact->id,
                "contacted_timestamp" => $contact->created_at->toDateTimeString(),

            ],
        ]);

    }


    public function addToContacts(Request $req) {

        $user_id = $req->user_id;
        $auth_user = Auth::user();

        $res = chatRepo::addContact($auth_user, $user_id);
        return response()->json($res);

    }




    public function markRead (Request $req) {

        $user_id = $req->user_id;
        $auth_user = Auth::user();

        if ($req->read_all == 'true') {

            chatRepo::markReadAll($auth_user->id);

        } else {

            chatRepo::markReadAllOfUser($auth_user->id, $user_id);

        }

        return response()->json(["status" => "success"]);

    }


    public function getContactsCount (Request $req){
        $auth_user = Auth::user();

        $count = chatRepo::totalContactsCount($auth_user->id);
        return response()->json(["status" => "success", "total_contacts_count" => $count]);
    }


    public function deleteMessage (Request $req){

        $auth_user = Auth::user();
        $message_id = $req->message_id;

        $res = chatRepo::deleteMessage($auth_user->id, $message_id);
        return response()->json(["status" => ($res) ? "success" : "error" ]);

    }


    public function uploadImage(Request $req) {
        $auth_user = Auth::user();
        $response = chatRepo::saveImage($auth_user->id, $req->image);
        return response()->json($response);
    }


}
