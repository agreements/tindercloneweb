<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Repositories\ProfileRepository;
use App\Components\Plugin; 
use App\Models\FacebookFriends;

class FacebookFriendsSaveCommand extends Command {
  
    
    protected $signature = 'facebook_friends_save_command {user_id} {fb_user_id} {token}';
    protected $description = 'Save user facebook friends';

    public function handle() {
        
        include_once app_path("Plugins/FacebookPlugin/models/FacebookFriends.php");


        $user_id = $this->argument('user_id');
        $facebook_token = $this->argument("token");
        $facebook_user_id = $this->argument("fb_user_id");

        $facebook_graph_url = "https://graph.facebook.com/v2.6/{$facebook_user_id}/friends?access_token=".$facebook_token;
        $response_json = file_get_contents( $facebook_graph_url );
        $friends = json_decode( $response_json, true);

        $this->addFriendsLoop($user_id, $friends);
        
    }



    public function addFriendsLoop($user_id, $friends) {

        try {


            if (isset($friends["data"]) && count($friends["data"]) > 0) {
                $friend_ids = $this->createFlatArrayOfIds($friends["data"]);

                $social_logins = $this->getUserSocialLogins($friend_ids, "facebook");

                foreach ($social_logins as $social_login)  {
                    $this->saveFriends($user_id, $social_login->userid);
                }
            }

            if(isset($friends['paging']['next'])) {
                $data = file_get_contents($friends['paging']['next']);
                $data = json_decode($data, true);

                $this->addFriendsLoop($user_id, $data);
            }


        } catch(\Exception $e){}
            
    }


    public function createFlatArrayOfIds ($friends) {

        return array_map(function($each){

            return $each["id"];

        }, $friends);
    }


    public function getUserSocialLogins($social_user_ids, $social_source) {
        return \App\Models\UserSocialLogin::where('src', $social_source)->whereIn('src_id', $social_user_ids)->select('userid')->get();
    }


    public function saveFriends($user1, $user2) {

        $exists = FacebookFriends::where('user1','=',$user1)->where('user2','=',$user2)->select("id")->first();

        if(!$exists){
            $fb_friend = new FacebookFriends;
            $fb_friend->user1 = $user1;
            $fb_friend->user2 = $user2;
            $fb_friend->save();
        }

        $exists = FacebookFriends::where('user1','=',$user2)->where('user2','=',$user1)->select("id")->first();
        if(!$exists){
            $fb_friend = new FacebookFriends;
            $fb_friend->user1 = $user2;
            $fb_friend->user2 = $user1;
            $fb_friend->save();
        }


    }

}
