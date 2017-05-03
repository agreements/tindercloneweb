<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Profile;
use App\Models\Match;
use App\Models\Encounter;
use App\Models\Visitor;
use App\Models\Photo;
use App\Models\RiseUp;
use App\Models\Package;
use App\Models\Fields;
use App\Models\CreditHistory;
use App\Models\UserPreferences;
use App\Models\UserFields;
use App\Models\FieldOptions;
use App\Repositories\Admin\UtilityRepository;
use Hash;
use DB;
use App\Models\SuperPowerPackages;
use Auth;
use App\Repositories\BlockUserRepository;
use App\Models\Settings;
use \Illuminate\Pagination\LengthAwarePaginator as LengthAwarePaginator;

class PeopleNearByRepository
{
	
	public static $store = array();
	
	private $blockUserRepo;
	public function __construct()
	{
		$this->blockUserRepo = app("App\Repositories\BlockUserRepository");
		$this->settings = app('App\Models\Settings');
	}

	// public function getFilteredPeople($id, $users)
	// {

	// 	$arr = $this->getFilterValues($id);
		
	// 	$user_ids = array();
	// 	// dd($users->get());
	// 	foreach($arr[0] as $key => $value)
	// 	{
	// 		$userf = UserFields::where('field_id',$key)->where('value',$value)->get();	
	// 		foreach($userf as $f)
	// 		{
	// 			array_push($user_ids, $f->user_id);
	// 		}
	// 	}
		
	// 	foreach($arr[1] as $key => $value)
	// 	{

	// 		$userf = UserFields::where('field_id',$key)->whereBetween('value',$value)->get();	
	// 		foreach($userf as $f)
	// 		{
	// 			array_push($user_ids, $f->user_id);
	// 		}	
			
	// 	}

	// 	$user_ids = array_unique($user_ids);

	// 	$users = $users->whereIn('user.id',$user_ids);
		
	// 	return $users;
	// }

	// public function getFilterValues($id)
	// {
	// 	$arr[0] = array();
	// 	$arr[1] = array();

	// 	$filters = UserPreferences::where('user_id',$id)->get();
		
	// 	foreach($filters as $filter)
	// 	{
	// 		if($filter->getFieldType() == 'range')
	// 		{
	// 			$arr[1][$filter->field_id] = [];

	// 			$temp = explode('-', $filter->prefer_value);
	// 			array_push($arr[1][$filter->field_id], $temp[0]);
	// 			array_push($arr[1][$filter->field_id], $temp[1]);
				
	// 		}
	// 		else	
	// 			$arr[0][$filter->field_id] = $filter->prefer_value;
	// 	}

	// 	return $arr;
	// }

	// public function isFilterPresent($id)
	// {
	// 	$check = UserPreferences::where('user_id',$id)->first();
	// 	if($check)
	// 		return true;
	// 	else
	// 		return false;
	// }

	public function getCustomFields()
	{
		$fields = app("App\Models\Fields")->getOnSearch();
		return $fields;
	}

	public function paginate($users, $curpage)
	{
		if ($curpage > 0) {
			$curpage -= 1;
		}		

		//$total = $users->count();
		$perpage = 10;

		//$users = $users->slice ( ($curpage * $perpage), $perpage);
		//$users = new LengthAwarePaginator ($users, $total, $perpage);

		$users = $users->skip($curpage * $perpage)->paginate($perpage);

		
		$users->setPath('peoplenearby');
		return $users;
	}
/*
	public function getNearbyPeoples ($log_user_id, $gender, $ageLower, $ageUpper, $distance = 1) {

		$log_user = User::find ($log_user_id);
		$log_user_lat = $log_user_lng = 0;

		if ($log_user->profile->latitude && $log_user->profile->longitude) {
			$log_user_lat = $log_user->profile->latitude;
			$log_user_lng = $log_user->profile->longitude;
		} else if ($log_user->latitude && $log_user->longitude) {
			$log_user_lat = $log_user->latitude;
			$log_user_lng = $log_user->longitude;
		}

		$users = $this->getUsersByRadious($log_user_lat, $log_user_lng, $distance, 'km');


		//filtering age note: age range have to be set always
		$dob_range = array();
		$end   = date('Y-m-d', strtotime("-{$ageUpper} year"));
		$start = date('Y-m-d', strtotime("-{$ageLower} year"));
		array_push($dob_range, $end);
		array_push($dob_range, $start);

		$blockedIds = $this->blockUserRepo->getAllBlockedUsersIds ($log_user_id);
		array_push($blockedIds, $log_user_id);

		$users = $users->whereBetween('dob', $dob_range)
						->where('activate_user', '<>', 'deactivated') //removing all deactivated users
						->whereNotIn ( 'user.id', $blockedIds);

		//filter users with gender
		$genders = explode(",", $gender);
		if (strlen($gender) > 0) {
			$users = $users->whereIn('gender', $genders);
		}

		$users = $this->applyAdvanceFilter($log_user, $users);



		$peoplenearby_only_superpowers = UtilityRepository::get_setting('peoplenearby_only_superpowers');

		if ($peoplenearby_only_superpowers == 'true') {

			$users = $users->leftJoin('user_superpowers', function($join) {
									$join->on('user_superpowers.user_id', '=', 'user.id')
										->where('user_superpowers.expired_at', '>=', date('Y-m-d h:i:s'));
										
					})->orderBy('user_superpowers.updated_at', 'desc')->select('user.*');

		} else {

			$users = $users->leftJoin('riseup', 'riseup.userid', '=', 'user.id')
						->orderBy('riseup.updated_at', 'desc')
						->select('user.*');

		}





		

		$online_users_ids = [];
		$online_status = ($log_user->profile->prefer_online_status == "online") ? true : false;
		if ($online_status) {
			foreach ($users->get() as $user) {
				if ($user->onlineStatus()) {
					array_push($online_users_ids, $user->id);
				}
			}
			$users = $users->whereIn('user.id', $online_users_ids);
		} 		
		
		return $users;
	}
	
	*/
	
	
		public function getNearbyPeoples ($log_user_id, $gender, $ageLower, $ageUpper, $distance = 1, $riseup_superpower = true) {

		$log_user = User::find ($log_user_id);
		$log_user_lat = $log_user_lng = 0;

		if ($log_user->profile->latitude && $log_user->profile->longitude) {
			$log_user_lat = $log_user->profile->latitude;
			$log_user_lng = $log_user->profile->longitude;
		} else if ($log_user->latitude && $log_user->longitude) {
			$log_user_lat = $log_user->latitude;
			$log_user_lng = $log_user->longitude;
		}
		
		//filtering age note: age range have to be set always
		$dob_range = array();
		$end   = date('Y-m-d', strtotime("-{$ageUpper} year"));
		$start = date('Y-m-d', strtotime("-{$ageLower} year"));
		array_push($dob_range, $end);
		array_push($dob_range, $start);
		
		$blockedIds = $this->blockUserRepo->getAllBlockedUsersIds ($log_user_id);
		array_push($blockedIds, $log_user_id);

		
		$online_status = ($log_user->profile->prefer_online_status == "online") ? true : false;

		$users = $this->getUsersByRadius(
			$log_user_lat, $log_user_lng, 
			$distance, 
			$this->settings->get('filter_distance_unit'), 
			$gender, $dob_range, 
			$blockedIds, 
			$online_status
		);


		

		

		

		$users = $this->applyAdvanceFilter($log_user, $users);

		

		$peoplenearby_only_superpowers = UtilityRepository::get_setting('peoplenearby_only_superpowers');

		if ($peoplenearby_only_superpowers == 'true') {

			$users = $users->leftJoin('user_superpowers', function($join) {
									$join->on('user_superpowers.user_id', '=', 'user.id')
										->where('user_superpowers.expired_at', '>=', date('Y-m-d h:i:s'));
										
					})->orderBy('user_superpowers.updated_at', 'desc')
					->select(['user.id', 
							'user.name', 
							'user.dob', 
							'user.last_request', 
							'user.city',
							'user.country',
							'user.profile_pic_url',
							//DB::raw('riseup.updated_at as riseup_updated'),
							'profile.popularity' 
						]);

		} else {

			$users = $users->leftJoin('riseup', 'riseup.userid', '=', 'user.id')
						->orderBy('riseup.updated_at', 'desc')
						->select(['user.id', 
							'user.name', 
							'user.dob', 
							'user.last_request', 
							'user.city',
							'user.country',
							'user.profile_pic_url',
							DB::raw('riseup.updated_at as riseup_updated'),
							'profile.popularity' 
						]);

		}
		
		
				
		
		return $users;
	}
	
	
	public static function getUsersByRadius($lat, $lng, $distance, $unit, $gender, $dob_range, $blockedIds, $online_status) {
		    
		$users = User::whereBetween('dob', $dob_range)
					->whereNotIn( 'user.id', $blockedIds)
					->where('activate_user', '<>', 'deactivated');
		
		$genders = explode(",", $gender);
		if (strlen($gender) > 0) {
			$users = $users->whereIn('gender', $genders);
		}				 
						 
		$online_users_ids = [];
		
		if ($online_status) {
			foreach ($users->select(['id', 'last_request'])->get() as $user) {
				if ($user->onlineStatus()) {
					array_push($online_users_ids, $user->id);
				}
			}
			$users = $users->whereIn('user.id', $online_users_ids);
		} 
		
		//$users = $users->where('activate_user', '<>', 'deactivated');
				//$users = $users->whereBetween('dob', $dob_range);		
		
		//filter users with gender
		// $genders = explode(",", $gender);
		// if (strlen($gender) > 0) {
		// 	$users = $users->whereIn('gender', $genders);
		// }
		
		
     
		$nearbyusers = [];
		$varR        = ($unit == "km") ? 111.045 : 69;

		$userLat     = number_format($lat, 6, '.', '');
		$userLng     = number_format($lng, 6, '.', '');

		$minlng      = $userLng - ($distance / abs(cos(deg2rad($userLat)) * $varR));
		$maxlng      = $userLng + ($distance / abs(cos(deg2rad($userLat)) * $varR));
		$minlat      = $userLat - ($distance / $varR);
		$maxlat      = $userLat + ($distance / $varR);
		
		$minlng      = number_format($minlng, 6, '.', '');
		$maxlng      = number_format($maxlng, 6, '.', '');
		$minlat      = number_format($minlat, 6, '.', '');
		$maxlat      = number_format($maxlat, 6, '.', '');

      
		return $users->join('profile', 'user.id', '=', 'profile.userid')
			->where(function ($query) use ($minlat, $maxlat, $minlng, $maxlng) {
				$query->where (function ($query) use ($minlat, $maxlat, $minlng, $maxlng) {
	                $query->whereBetween('user.latitude', [$minlat, $maxlat])
            			  ->whereBetween('user.longitude', [$minlng, $maxlng]);  
	        	})
				->orWhere (function ($query) use($minlat,$maxlat,$minlng,$maxlng) {
		                $query->whereBetween('profile.latitude', [$minlat, $maxlat])
                		->whereBetween('profile.longitude', [$minlng, $maxlng]);  
		        });
		});
       
    }


	public function applyAdvanceFilter ($user, $users) {
		
		$user_preferences = $this->getUserPreferences($user->id);
		$user_ids = [];
		$counter = 0;
		foreach ($user_preferences as $user_preference) {
			if ($user_preference->prefer_value != '') {
				$counter++;
			}
			$field = $user_preference->field;
			if ($field->on_search_type == 'range') {

				$prefer_value_array = explode("-", $user_preference->prefer_value);
				if (count($prefer_value_array) == 2) {
					
					$option_ids = $range_option_ids = [];
					$range_options = FieldOptions::whereBetween('name', $prefer_value_array)
													->where('field_id', $field->id)
													->get();

					foreach ($range_options as $option) {
						array_push($range_option_ids, $option->id);
					}

					$matched_users_fields = UserFields::whereIn('value', $range_option_ids)
											->where('field_id', $field->id)->get();
					foreach ($matched_users_fields as $field) {
						array_push($user_ids, $field->user_id);
					}
				}
				
			} else if ($field->on_search_type == 'dropdown' && strlen($user_preference->prefer_value) > 0) {
				$prefer_value_array = explode(",", $user_preference->prefer_value);
				$option_ids = [];
				foreach ($field->field_options as $option) {
					if (in_array($option->code , $prefer_value_array))
						array_push($option_ids, $option->id);
				}
				
				$matched_users_fields = UserFields::whereIn('value', $option_ids)
													->where('field_id', $field->id)
													->get();
				foreach ($matched_users_fields as $field) {
					array_push($user_ids, $field->user_id);
				}

			}	
		}
		$user_ids = array_unique($user_ids);
		if ($counter > 0)
			$users = $users->whereIn('user.id', $user_ids);
		
		return $users;
	}



	//This methdo will serach users withing given km radious
    //return users

    public static function getUsersByRadious($lat, $lng, $distance) {
    
		$nearbyusers = [];
		$varR        = (app('App\Models\Settings')->get('filter_distance_unit') == "km") ? 111.045 : 69;

		$userLat     = number_format($lat, 6, '.', '');
		$userLng     = number_format($lng, 6, '.', '');

		$minlng      = $userLng - ($distance / abs(cos(deg2rad($userLat)) * $varR));
		$maxlng      = $userLng + ($distance / abs(cos(deg2rad($userLat)) * $varR));
		$minlat      = $userLat - ($distance / $varR);
		$maxlat      = $userLat + ($distance / $varR);
		
		$minlng      = number_format($minlng, 6, '.', '');
		$maxlng      = number_format($maxlng, 6, '.', '');
		$minlat      = number_format($minlat, 6, '.', '');
		$maxlat      = number_format($maxlat, 6, '.', '');

      
		return User::join('profile', 'user.id', '=', 'profile.userid')
			->where(function ($query) use ($minlat, $maxlat, $minlng, $maxlng) {
				$query->where (function ($query) use ($minlat, $maxlat, $minlng, $maxlng) {
	                $query->whereBetween('user.latitude', [$minlat, $maxlat])
            			  ->whereBetween('user.longitude', [$minlng, $maxlng]);  
	        	})
				->orWhere (function ($query) use($minlat,$maxlat,$minlng,$maxlng) {
		                $query->whereBetween('profile.latitude', [$minlat, $maxlat])
                		->whereBetween('profile.longitude', [$minlng, $maxlng]);  
		        });
		});
       
    }



	public function getRiseupCredits () {

		return Settings::_get('riseupCredits');
	}



	//this fucntion payes for rise up of $userid
	public function payRiseUp($userid, $credits) {
		
		$user = User::find($userid);

		$isExistedToRiseup = RiseUp::where('userid','=',$userid)->first();

		if ($isExistedToRiseup) {

			$isExistedToRiseup->userid = $userid;
			$isExistedToRiseup->touch();

		} else {

			$riseUp = new RiseUp;
			$riseUp->userid = $userid;
			$riseUp->save();
		}

		$user->credits->balance = $user->credits->balance - $credits;
		$user->credits->save();

		$cred_history = new CreditHistory;

		$cred_history->userid   = $userid;
		$cred_history->activity = "riseup payment success";
		$cred_history->credits  = $credits;

		$cred_history->save();

         
	}



	public function setFilter ($user, $filter) {

		$user->profile->prefer_gender          = $filter['prefer_gender'];
		$user->profile->prefer_age             = $filter['prefer_age'];
		$user->profile->prefer_distance_nearby = $filter['prefer_distance'];        
        $user->profile->save();

        return $user;
    }


   public function getPreferedGenders ($user) {
        $gender_field     = app('App\Repositories\Admin\GeneralManageRepository')->getGenderField();
        $prefer_genders   = explode(',', $user->profile->prefer_gender);
        $prefered_genders = [];

        foreach ($gender_field->field_options as $option) {
            $prefered_genders[$option->code] = (in_array($option->code, $prefer_genders)) ? 1 : 0;
        }

        return $prefered_genders;
    }

    public function getAllSearchFields () {
        $sections = app("App\Repositories\ProfileRepository")->get_fieldsections();
        $fields   = [];

        foreach ($sections as $section) {
            foreach ($section->fields as $field) {
                if($field->on_search == 'yes') {
                    array_push($fields, $field);
                }
            }
        }
        return $fields;
    }


    public function getFieldOptions ($field, $prefer_value) {

        $options = [];
        if ($field->on_search_type == 'range') {

            $prefer_value_array = explode("-", $prefer_value);
            foreach ($field->field_options as $option) {
                $option->isSelected = (in_array($option->name, $prefer_value_array)) ? true : false;
                array_push($options, $option);
            }

        } else if ($field->on_search_type == 'dropdown') {

            $prefer_value_array = explode(",", $prefer_value);
            foreach ($field->field_options as $option) {
                $option->isSelected = (in_array($option->code, $prefer_value_array)) ? true : false;
                array_push($options, $option);
            }
        }
        
        return $options;
    }



    public function savePreferenceFields ($user_id, $user_preferences) {

		foreach($user_preferences as $field_id => $perfer_value) {
			
			$user_preference = UserPreferences::where('user_id', $user_id)
												->where('field_id', $field_id)
												->first();
			
			if($user_preference) {
				$user_preference->prefer_value = $perfer_value;
			} else {
				$user_preference               = new UserPreferences;
				$user_preference->user_id      = $user_id;
				$user_preference->field_id     = $field_id;
				$user_preference->prefer_value = $perfer_value;
			}

			$user_preference->save();
		}

		return UserPreferences::where('user_id', $user_id)->get();
	}
	


	public static function deleteFieldFromUserPreferences ($field_id) {
		UserPreferences::where('field_id', $field_id)->forceDelete();
	}
	
	public function getUserPreferences ($user_id) {
		return UserPreferences::where('user_id', $user_id)->get();
	}
	/*
	public function getUserPreferences ($user_id) {
	
		return  \Cache::remember('user_' . $user_id. '_userpreference', 10*60, function() use($user_id)
    {
        return UserPreferences::where('user_id', $user_id)->get()->toArray();
    }); 
    
    
		
	}*/
/*
	public function getPreference ($user_id, $field_id) {
		return UserPreferences::where('user_id', $user_id)
				->where('field_id', $field_id)
				->first();
	}
*/	
	
	public function getPreference ($user_id, $field_id) {
			
		if(isset(self::$store[$user_id]) && isset(self::$store[$user_id][$field_id])) {
			return self::$store[$user_id][$field_id];
		} else {

			$userPreference = UserPreferences::where('user_id', $user_id)
											->where('field_id', $field_id)
											->first();

			return self::$store[$user_id][$field_id] = $userPreference;

		}			
				
	}
			
		/*
		return \Cache::remember('user_' . $user_id. '_userpreference_'.$field_id, 10*60, function() use($user_id, $field_id)
    {
        return UserPreferences::where('user_id', $user_id)
				->where('field_id', $field_id)
				->first();
    });
    
    */
		
	
}
