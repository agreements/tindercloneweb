<?php
namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Auth;
use App\Repositories\EncounterRepository;
use App\Repositories\ChatRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\SuperpowerRepository;
use App\Repositories\UserRepository;
use App\Repositories\CreditRepository;
use App\Repositories\PeopleNearByRepository;
use App\Repositories\Admin\GeneralManageRepository;

use App\Models\Settings;
use App\Repositories\MaxmindGEOIPRepository;

class GlobalComposer
{

    public function __construct(Settings $settings, MaxmindGEOIPRepository $maxmindGEOIPRepo) 
    {   
        $this->settings = $settings;
        $this->maxmindGEOIPRepo = $maxmindGEOIPRepo;
        $this->encounterRepo = app('App\Repositories\EncounterRepository');
        $this->creditRepo = app("App\Repositories\CreditRepository");
        $this->profileRepo = app("App\Repositories\ProfileRepository");
    }

    public function compose(View $view)
    {
        $this->shareGenderField($view);
        $this->shareCommonAfterAuthVariables($view);
        $this->shareCommonVariables($view);
    }

    protected function shareGenderField($view)
    {
        $view->with("gender", $this->genderField());
    }

    protected function genderField()
    {
        if(isset($this->gender_field)) {
            return $this->gender_field;
        }

        return $this->gender_field = (new GeneralManageRepository)->getGenderField();
    }

    protected function shareCommonAfterAuthVariables($view)
    {
        $auth_user = Auth::user();

        if(!$auth_user) return $this;

        //$view->with('encounter_list', $this->encounterList($auth_user));
        $view->with('credit', $this->authUserBalance($auth_user));
        $view->with("user_score", $this->authUserScore($auth_user));
        
        //$view->with('superpower_packages', app("App\Repositories\SuperpowerRepository")->getSuperpowerPackages());
        //$view->with("fields", (new PeopleNearByRepository)->getCustomFields()); 
        
        $view->with("percent", $this->authUserCompletePercentage($auth_user));
        //$view->with("title", app("App\Repositories\UserRepository")->getTitle());

    }

    protected function authUserCompletePercentage($auth_user) {
        
        if(isset($this->auth_user_complete_percentage)) {
            return $this->auth_user_complete_percentage;
        }

        return $this->auth_user_complete_percentage = $this->profileRepo->profileCompletePercent($auth_user);
    }

    protected function authUserScore($auth_user) {
        
        if(isset($this->auth_user_score)) {
            return $this->auth_user_score;
        }

        return $this->auth_user_score = $this->profileRepo->calculate_score($auth_user->id);
    }

    protected function authUserBalance($auth_user)
    {
        if(isset($this->auth_user_balance)) {
            return $this->auth_user_balance;
        }

        return $this->auth_user_balance = $this->creditRepo->getBalance($auth_user->id);
    }

    /*protected function encounterList($auth_user)
    {
        if(isset($this->encounter_list)) {
            return $this->encounter_list;
        }

        return $this->encounter_list = $this->encounterRepo->nextEncounterUser($auth_user, true);
    }*/


    



    protected function shareCommonVariables($view)
    {   
        $view->with('auth_user', Auth::user());
        $view->with('website_favicon', $this->settings->get('website_favicon'));
        $view->with('website_backgroundimage', $this->settings->get('website_backgroundimage'));
        $view->with('website_title', $this->settings->get('website_title'));
        $view->with('website_logo', $this->settings->get('website_logo'));
        $view->with('website_outerlogo', $this->settings->get('website_outerlogo'));
        $view->with('currency', $this->settings->get('currency'));
        $view->with('credits_module_available', $this->settings->get('credits_module_available'));
        $view->with('spotlight_only_superpowers', $this->settings->get('spotlight_only_superpowers'));
        $view->with('peoplenearby_only_superpowers', $this->settings->get('peoplenearby_only_superpowers'));
        
        $view->with('maxmind_geoip_enabled', $this->maxmindGEOIPRepo->enabled());
        $view->with('profile_visitor_details_show_mode', $this->settings->get('profile_visitor_details_show_mode'));
        $view->with('profile_interests_show_mode', $this->settings->get('profile_interests_show_mode'));
        $view->with('profile_about_me_show_mode', $this->settings->get('profile_about_me_show_mode'));
        $view->with('profile_score_show_mode', $this->settings->get('profile_score_show_mode'));
        $view->with('profile_map_show_mode', $this->settings->get('profile_map_show_mode'));
        $view->with('advance_filter_only_superpowers', $this->settings->get('advance_filter_only_superpowers'));
        $view->with('hide_popularity', $this->settings->get('hide_popularity'));
        $view->with('auto_browser_geolocation', $this->settings->get('auto_browser_geolocation'));
        $view->with('disable_interest_edit', $this->settings->get('disable_interest_edit'));
    }

}