<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Admin\UtilityRepository;
use App\Repositories\WebsocketChatRepository as chatRepo;


class WebsocketChatApiController extends Controller
{
	public function __construct(Request $req)
    {

       $auth_user = $req->real_auth_user;
        
        if ($auth_user) {
            chatRepo::setBlockUserIDs($auth_user->id);
        }
    }

    public function mapUserSocket (Request $req) 
    {

        $socket_id = $req->socket_id;
        $auth_user = $req->real_auth_user;

        if(is_null($socket_id)) {
            return response()->json([
                "status" => "error",
                "error_type" => "SOCKET_ID_INVALID",
                "error_text" => "Socket id is required"
            ]);
        }


        chatRepo::mapSocketIDWithUserID($auth_user->id, $socket_id, 'mobile');

        return response()->json([
            "status" => "success", 
            "success_type" => "USER_SOCKET_MAPPED",
            "base_chat_images_url" => url('uploads/chat')
        ]);

    }


    public function getChatUsers(Request $req) 
    {

        $auth_user = $req->real_auth_user;
        $last_contact_id = isset($req->last_contact_id) ? $req->last_contact_id : 0;
        

        $chat_data = chatRepo::getChatUsers($auth_user, $last_contact_id);

        return response()->json([
            "status" => "success", 
            "success_type" => ($last_contact_id == 0) ? "FIRST_20_USERS_RETRIVED" : "MORE_20_CHAT_USERS_RETRIVED",
            "chat_users" => collect($chat_data["chat_users"])->take(2), 
            "total_contacts_count" => $chat_data["total_contacts_count"], 
            "overall_unread_messages_count" => chatRepo::getOverallUnreadMessagesCount($auth_user->id), 
        ]);
    }

    public function getChatUser(Request $req) 
    {

        $user_id = $req->other_user_id;
        $auth_user = $req->real_auth_user;

        $contact = chatRepo::getContact($auth_user->id, $user_id);


        if(is_null($contact)) {
            return response()->json([
                "status" => "error",
                "error_type" => "NO_CONTACT_FOUND",
            ]);
        }



        $chat_data = chatRepo::getChatUser($auth_user, $contact);

        $online = $chat_data->onlineStatus() ? 1 : 0;
        $matched = chatRepo::isMatched($auth_user->id, $user_id);


        return response()->json([
            "status" => "success", 
            "success_type" => "CHAT_USER_RETRIVED",
            "chat_user" => [
                "user" => $chat_data, 
                "online" => $online,
                "matched" => $matched,
                "is_contacted" => 1,
                "contact_id" => $contact->id,
                "contacted_timestamp" => $contact->created_at->toDateTimeString(),

            ],
        ]);

    }


    public function addToContacts(Request $req) 
    {

        $user_id = $req->other_user_id;
        $auth_user = $req->real_auth_user;

        if(is_null($user_id)) {
            return response()->json([
                "status" => "error",
                "error_type" => "OTHE_USER_ID_INVALID",
                "error_text" => "other_user_id is required"
            ]);
        }



        try {
            $res = chatRepo::addContact($auth_user, $user_id);
                
        } catch(\Exception $e) {
            return response()->json([
                "status" => "error",
                "error_type" => "UNKNOWN_ERROR",
                "error_text" => $e->getMessage() . "file : ". $e->getFIle() ."on line : " . $e->getLine()
            ]);
        }
        
        return response()->json($res);

    }


    public function uploadImage(Request $req) 
    {
        $auth_user = $req->real_auth_user;

        if(is_null($req->image)) {
            return response()->json([
                "status" => "error", 
                "error_type" => "IMAGE_INVALID",
                "error_text" => "image param is reqired"
            ]);
        }


        $response = chatRepo::saveImage($auth_user->id, $req->image);

        if($response['status'] == "success") {
            $response['image_url'] = url('uploads/chat/'. $response['image']);
        }


        return response()->json($response);
    }



    public function getMessages(Request $req) 
    {

        $last_message_id = $req->last_message_id ? $req->last_message_id : 0;
        $user_id = $req->other_user_id;
        $auth_user = $req->real_auth_user;

        $contact_id = chatRepo::getContactID($auth_user->id, $user_id);


        if(is_null($user_id) || !$contact_id) {
            return response()->json([
                "status" => "error",
                "error_type" => "INVALID_OTHER_USER_ID",
                "error_text" => "other_user_id required",
            ]);
        }

        $messages = chatRepo::getMessages($user_id, $contact_id, $last_message_id);        

        return response()->json([
            "status" => "success", 
            "success_type" => ($last_message_id == 0) ? "LAST_20_MESSAGES_RETRIVED" : "MORE_20_MESSAGES_RETRIVED",
            "messages" => $messages->take(-5)
        ]);

    }


    public function getContactsCount (Request $req)
    {
        $auth_user = $req->real_auth_user;

        $count = chatRepo::totalContactsCount($auth_user->id);
        return response()->json(["status" => "success", "total_contacts_count" => $count]);
    }




    public function markRead (Request $req) 
    {

        $auth_user = $req->real_auth_user;

        if ($req->read_all == 'true') {

            chatRepo::markReadAll($auth_user->id);
            return response()->json([
                "status" => "success", 
                "success_type" => "MARK_READ_ALL_MESSAGES",
                "success_text" => "All messages marked read" 
            ]);

        } else {

            chatRepo::markReadAllOfUser($auth_user->id, $req->other_user_id);

            return response()->json([
                "status" => "success", 
                "success_type" => "MARK_READ_ALL_MESSAGES_OF_USER_ID_{$req->other_user_id}",
                "success_text" => "All messages marked read" 
            ]);
        }

    }


    public function deleteMessage (Request $req)
    {

        $auth_user = $req->real_auth_user;
        $message_id = $req->message_id;

        $res = chatRepo::deleteMessage($auth_user->id, $message_id);

        if($res) {
            return response()->json([
                "status" => 'success',
                "success_type" => "MESSAGE_DELETED",
                "success_text" => "Message deleted"
            ]);
        } else {

            return response()->json([
                "status" => 'error',
                "error_type" => "FAILED_TO_DELETE_MESSAGE",
                "error_text" => "Failed to delete message"
            ]);

        }

    } 

    public function deleteContact(Request $req) 
    {
        $auth_user = $req->real_auth_user;
        $contact_id = $req->contact_id;

        $response = chatRepo::deleteContact($auth_user->id, $contact_id);
        return response()->json($response);
    }



    public function getServerDetails()
    {
        return response()->json([
            "status" => 'success',
            "details" => chatRepo::serverDetails()
        ]);
    }

}