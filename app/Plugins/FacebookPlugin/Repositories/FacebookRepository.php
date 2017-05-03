<?php

namespace App\Repositories;

use App\Models\FacebookFriends;
use App\Models\Photo;
use App\Models\SocialLogins;
use App\Models\Settings;
use App\Components\Theme;

class FacebookRepository {


    public function __construct(
        SocialLogins $socialLogins, 
        Photo $photo, 
        FacebookFriends $facebookFriends, 
        Settings $settings
    )
    {
        $this->socialLogins = $socialLogins;
        $this->photo = $photo;
        $this->facebookFriends = $facebookFriends;
        $this->settings = $settings;
        $this->init();
    }


    public function init()
    {
        $this->impot_photo_enabled = $this->settings->get('fb_photos_import') == '1' ? true : false;
        $this->app_id = $this->settings->get('fb_appId');
        $this->secret_key = $this->settings->get('fb_secretkey');

        config([
            'services.facebook.client_id'     => $this->app_id,
            'services.facebook.client_secret' => $this->secret_key,
            'services.facebook.redirect'      => url('/facebook/callback')
        ]);
    }

	public function deleteFacebookUsers ($user_ids) {

		$this->facebookFriends->whereIn('user1', $user_ids)->orWhereIn('user2', $user_ids)->forceDelete();

	}

	public function saveFacebookFriends($user1, $user2)
	{
		$exists = $this->facebookFriends->where('user1', $user1)->where('user2', $user2)->first();

        if(!$exists){
            
            $fb_friend = new $this->facebookFriends;
            $fb_friend->user1 = $user1;
            $fb_friend->user2 = $user2;
            $fb_friend->save();
        }

        $exists = $this->facebookFriends->where('user1','=',$user2)->where('user2','=',$user1)->first();
        if(!$exists){

            $fb_friend = new $this->facebookFriends;
            $fb_friend->user1 = $user2;
            $fb_friend->user2 = $user1;
            $fb_friend->save();
        }


	}

	public function insertPhoto($user_id, $image_id, $photo_name)
	{
		$photo = new $this->photo;

        $photo->userid          = $user_id;
        $photo->source_photo_id = $image_id;
        $photo->photo_source    = 'facebook';
        $photo->photo_url       = $photo_name;
        
        $photo->save();

        return $photo;
	}

    public function loginPriority()
    {
        $social_login = $this->socialLogins->where('name','FacebookPlugin')->select('priority')->first()->toArray();
        return isset($social_login['priority']) ? $social_login['priority'] : 999;
    }


    public function photoImportEnabled()
    {
        return $this->impot_photo_enabled;
    }


    public function addThemeHooks()
    {
        Theme::hook('photos', [$this, 'callback2']);
        Theme::hook('photos', [$this, 'callback1']);
    }

    public function callback1()
    {
        return Theme::view('plugin.FacebookPlugin.fb_photos', ["fb_app_id" => $this->app_id]);
    }

    public function callback2()
    {
        return Theme::view('plugin.FacebookPlugin.facebook_photo_import_modal', []);
    }

}
