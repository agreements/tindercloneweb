<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Repositories\ProfileRepository;
use App\Components\Plugin; 

class FacebookLikesAsInterestCommand extends Command {
  
    
    protected $signature = 'facebook_likes_as_interest {user_id} {fb_user_id} {token}';
    protected $description = 'Save user facebook likes as user interests';

    public function handle() {
        
        $user_id = $this->argument('user_id');
        $facebook_token = $this->argument("token");
        $facebook_user_id = $this->argument("fb_user_id");

        $facebook_graph_url = "https://graph.facebook.com/v2.6/{$facebook_user_id}/likes?access_token=".$facebook_token;

        $likes = file_get_contents( $facebook_graph_url );
        $this->addLikesLoop($user_id, json_decode($likes, true));
        
    }


    public function addLikesLoop($user_id, $likes) {

        try {

            foreach ($likes['data'] as $like) {

                $this->addFacebookLikesAsInterest($user_id, $like['name']);
            }

            if(isset($likes['paging']['next'])) {
                $data = file_get_contents($likes['paging']['next']);
                $data = json_decode($data, true);

                $this->addLikesLoop($user_id, $data);
            }


        } catch(\Exception $e){}
            
    }



    public function addFacebookLikesAsInterest($user_id, $interest) {

        $profileRepo = app("App\Repositories\ProfileRepository");

        if (!$profileRepo->isUserInterestExist($user_id, $interest)) {

            $interest_object = $profileRepo->findInterest($interest);

            if ($interest_object) {

                $profileRepo->addToUserInterests($user_id, $interest_object->id);
                $interest_id = $interest_object->id;

                return "only_user_interest_added";

            } else {

                $interest = $profileRepo->addToInterests($interest);
                $profileRepo->addToUserInterests($user_id, $interest->id);
                $interest_id = $interest->id;
                Plugin::fire('interest_added', $interest);

                return "both_interest_added";
            }

        }

        return "user_interest_exists";

    }


}
