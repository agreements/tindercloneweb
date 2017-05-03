<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\CreditHistory;
use App\Models\Spotlight;
use App\Models\Settings;
use App\Components\Plugin;
use App\Components\Theme;

class SpotlightRepository
{

	public function __construct(
        Settings $settings, 
        User $user, 
        Spotlight $spotlight, 
        CreditHistory $creditHistory
    )
	{
		$this->settings = $settings;
        $this->user = $user;
        $this->spotlight = $spotlight;
        $this->creditHistory = $creditHistory;
	}
	
    public function addToSpotlight($userId) {

        $spot_credit = $this->settings->get('spotCredits');
        $user = $this->user->find($userId);

        if ($user->credits->balance >= $spot_credit) {

            $this->createOrUpdateSpotlight($userId);

            $user->credits->balance = $user->credits->balance - $spot_credit;
            $user->credits->save();

            $this->createCreditHistory($user->id, "spotlight enabled", $spot_credit);

            return true;

        } else {

            return false;
        }
    }


    public function createCreditHistory($userId, $activity, $credits)
    {
        $cred_history = new $this->creditHistory;
        $cred_history->userid = $userId;
        $cred_history->activity = $activity;
        $cred_history->credits = $credits;
        $cred_history->save();
        return $cred_history;
    }



    public function createOrUpdateSpotlight($user_id)
    {
        $spotlight = $this->spotlight->where('userid', $user_id)->first();

        if ($spotlight) {
            $spotlight->userid = $user_id;
            $spotlight->touch();
        } else {
            $spotlight = new $this->spotlight;
            $spotlight->userid = $user_id;
            $spotlight->save();
        }

        return $spotlight;
    }


    
    //this method will return soplight users
    //removing deactivated users
    public function getSpotUsers () {
        
        $only_superpowers = Settings::_get('spotlight_only_superpowers');

        if ($only_superpowers == 'true') {

            $spots = User::join('user_superpowers', 'user_superpowers.user_id', '=', 'user.id')
                            ->where('user_superpowers.expired_at', '>=', date('Y-m-d h:i:s'))
                            ->where('user.activate_user', '<>', 'deactivated')
                            ->select(['user.id', 'user.name', 'user.profile_pic_url'])
                            ->take(20)
                            ->get();

            $spots = $spots->shuffle();
            $spots = $spots->toArray();

        } else {

            $spots = User::join('spotlight', 'spotlight.userid', '=', 'user.id')
                            ->where('user.activate_user', '<>', 'deactivated')
                            ->orderBy('spotlight.updated_at', 'desc')
                            ->select(['user.id', 'user.name', 'user.profile_pic_url'])
                            ->take(20)
                            ->get()
                            ->toArray();
                            
                            //dd($spots);

        }


        return $spots;


    }

    public function getSpotCredits () {

        $credit = Settings::_get('spotCredits');
        return is_null($credit) ? 0 : $credit;
    }

    public function getSpotlightScreenshotUrl()
    {
        $theme = Theme::get_activated_theme_by_role('parent');
        return $this->settings->get("{$theme}_spotlight_screenshot");
    }
}

