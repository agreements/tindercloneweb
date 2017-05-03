<?php

namespace App\Repositories;

use App\Components\Plugin;
use App\Models\Ads;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;
use App\Components\Theme;

class AdsRepository
{
	public function __construct(Ads $ads, Settings $settings)
	{
		$this->ads = $ads;		
		$this->settings = $settings;		
	}

	//this method shuld not be called before Auth set
	public function shoudShowAdd() 
	{
		return (!$this->hide_add_superpowers || !$this->isAuthSuperpowerActivated()) 
			? true : false;
	}

	//this method shuld not be called before Auth set
	public function isAuthSuperpowerActivated()
	{
		if(isset($this->authSuperpowerActive)) {
			return $this->authSuperpowerActive;
		}

		$auth_user = Auth::user();

		if($auth_user && $auth_user->isSuperPowerActivated()) {
			$this->authSuperpowerActive = true;
			return $this->authSuperpowerActive;
		}

		$this->authSuperpowerActive = false;
		return false;
	}


	public function initializeAds()
	{
		$this->hide_add_superpowers = $this->settings->get('show_add') == 'true' ? true : false;

		$this->adds = $this->addsByIds(
			$addIds = $this->addIds()		
		);

		return $this;
	}

	protected function addIds()
	{
		$addIds[] = $this->left_sidebar_add = $this->settings->get('left-sidebar-ad');
		$addIds[] = $this->right_sidebar_add = $this->settings->get('right-sidebar-ad');
		$addIds[] = $this->bottom_add = $this->settings->get('bottom-ad');
		$addIds[] = $this->top_add = $this->settings->get('top-ad');
		return $addIds;
	}

	public function addsByIds($ids)
	{
		return $this->ads->whereIn('id', array_unique($ids))->select(['id', 'code'])->get();
	}

	public function registerHooks()
	{
		return $this->registerRightAddHook()
					->registerLeftAddHook()
					->registerBottomAddHook()
					->registerTopAddHook();
	}

	protected function registerBottomAddHook()
	{
		if(!$this->bottom_add) return $this;
	
		Theme::hook('bottom-ad', function(){
			if($this->shoudShowAdd())
				return $this->adds->where('id', intval($this->bottom_add))->first()->code;
		});

		return $this;
	}

	protected function registerTopAddHook()
	{
		if(!$this->top_add) return $this;
	
		Theme::hook('top-ad', function(){
			if($this->shoudShowAdd())
				return $this->adds->where('id', intval($this->top_add))->first()->code;
		});

		return $this;
	}

	protected function registerLeftAddHook()
	{
		if(!$this->left_sidebar_add) return $this;
	
		Theme::hook('left-sidebar-ad', function(){
			if($this->shoudShowAdd())
				return $this->adds->where('id', intval($this->left_sidebar_add))->first()->code;
		});

		return $this;
	}

	protected function registerRightAddHook()
	{
		if(!$this->right_sidebar_add) return $this;
	
		Theme::hook('right-sidebar-ad', function(){
			if($this->shoudShowAdd())
				return $this->adds->where('id', intval($this->right_sidebar_add))->first()->code;
		});

		return $this;
	}

	public function getAllAds()
	{
		return $this->ads->orderBy('created_at', 'desc')->get();
	}

	public function getAdById($id)
	{
		return $this->ads->where('id','=',$id)->first();
	}

	public function getAdByName($name)
	{
		return $this->ads->where('name', '=', $name)->first();
	}

	public function getSetAds()
	{
		$active = new \stdClass;

		$active->leftsidebar  = $this->settings->get('left-sidebar-ad');
		$active->rightsidebar = $this->settings->get('right-sidebar-ad');
		$active->topbar       = $this->settings->get('top-ad');
		$active->bottombar    = $this->settings->get('bottom-ad');
		$active->show_add     = $this->settings->get('show_add');

        return $active;
	}

	public function setAds($arr)
	{
		$this->settings->set('top-ad', $arr['topbar']);
        $this->settings->set('left-sidebar-ad', $arr['leftsidebar']);
        $this->settings->set('right-sidebar-ad', $arr['rightsidebar']);
        $this->settings->set('bottom-ad', $arr['bottombar']);

        $this->settings->set('show_add', $arr['show_add']);

	}

	public function createAd($name , $htmlcode)
	{
		$banner = new $this->ads;
        $banner->name = $name;
        $banner->code = $htmlcode;
        
        $banner->save();
	}
	
}
