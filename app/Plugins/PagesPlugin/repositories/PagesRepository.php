<?php

namespace App\Repositories;

use App\Components\Plugin;
use App\Models\Pages;
use App\Models\Interests;
use App\Models\UserInterests;
use App\Models\User;
use Storage;

class PagesRepository {

	public function getPageByTitle($title)
	{
		return Pages::where('title', '=', $title)->first();
	}

	public function getPageByRoute($route)
	{
		return Pages::where('route', '=', $route)->first();
	}

	public function getInterestBySlug($slug)
	{
		$interest = Interests::where('slug',$slug)->first();
		return $interest;
	}

	public function getUsersBySlug($slug)
	{
		$interest = Interests::where('slug',$slug)->first();

		$users = $this->getUsersByInterest($interest->id);
		$users->count = count($users);
		return $users;
	}

	public function getUsersByInterest($id)
	{
		$users_interest = UserInterests::where('interestid',$id)->paginate(16);
		return $users_interest;
	}

	public function getUsersByCity($city)
	{
		$arr = explode('-', $city);
		$str = '';
		foreach($arr as $val)
		{
			$val[0] = strtoupper($val[0]);
			$str.=$val.' ';
		}
		$str = rtrim($str);
		$users = User::where('city',$str)->paginate(16);
		return array($users,$str);
	}

	public function getUsersByFilter($arr)
	{
		if($arr['gender'] == 'both')
			$users = User::where('city',$arr['city'])->where('hereto',$arr['hereto']);
		else
			$users = User::where('city',$arr['city'])->where('hereto',$arr['hereto'])->where('gender',$arr['gender']);
		// dd(date("Y-m-d", strtotime("-".$arr['from_age']." years")));

		if(isset($arr['from_age']) && isset($arr['to_age']))
			$users = $users->whereBetween('dob', [date("Y-m-d", strtotime("-".$arr['to_age']." years")),date("Y-m-d", strtotime("-".$arr['from_age']." years"))])->paginate(16);
		
		return $users;
	}

	public function save_settings($arr)
	{
		$page = new Pages;
        $page->title = $arr['title'];
        $page->body = $arr['body'];
        $page->route = $arr['route'];

        $page->save();
	}

	public function updatePage($arr)
	{
		$banner = Pages::withTrashed()->where('id', '=' ,$arr['id'])->first();

          if ($banner) {

              $banner->title = $arr['title'];
              $banner->body = $arr['body'];
              $banner->route = $arr['route'];
              $banner->save();

              return true;

          } else {

              return false;
          }
	}

	public function deletePage($arr)
	{
		$banner = Pages::where('id','=',$arr['id'])->forceDelete();
	}

	public function activatePage($arr)
	{
		Pages::withTrashed()->find($arr['page_id'])->restore();
	}

	public function deactivatePage($arr)
	{
		Pages::find($arr['page_id'])->delete();
	}

}
