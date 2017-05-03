<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\Components\Plugin;
use Auth;

use App\Models\Profile;
use App\Models\Photo;
use App\Models\UserSettings;
use App\Models\UserSuperPowers;


//for soft delete 
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    use SoftDeletes;
    
    protected $fillable = [
        'name', 'username', 'password', 'gender',
        'dob', 'city', 'country', 'latitude', 'longitude', 
        'activate_user', 'verified', 'hereto', 'status', 
        'activate_token', 'register_from', 'profile_pic_url', 'language'
    ];
    
    
    protected $hidden = array('password', 'access_token', 'activate_token', 'password_token');
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user';


    //for softdelete
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    //protected $hidden = ['password', 'remember_token'];

    //for created_at and updated_at field
    public $timestamps = true;
    
    
    public $isSuperpower;
    
    public $profile_cached;
    
    public $photos_cached;
    
    public $settings_cached;
    
    public $superpower_cached;
    
    
    
    public function __construct($attributes = array())  {
        parent::__construct($attributes); // Eloquent
        
        
   
	     
        
    }


    public function getRememberToken()
    {
        return $this->remember_token; // not supported
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token'; // not supported
    }

 	/**
  * Overrides the method to ignore the remember token.
  */
     // public function setAttribute($key, $value)
     // {
     //   $isRememberTokenAttribute = $key == $this->getRememberTokenName();
     //   if (!$isRememberTokenAttribute)
     //   {
     //     parent::setAttribute($key, $value);
     //   }
     // }

/*
    public function profile()
    {
        return $this->hasOne('App\Models\Profile','userid');
    } */
    
    public function getProfileAttribute(){
	    
	    if($this->profile_cached){ 
		    return $this->profile_cached;
		    
		    }
		    else{ 
	    $cache =  \Cache::remember('user_' . $this->id . '_profile', 10*60, function()
    {
        return serialize($this->hasMany('App\Models\Profile', 'userid')->get()->first()->getAttributes());
    });
    	$cache = unserialize($cache);
		$profile = new Profile($cache);
		$profile->exists = true;
    	$this->profile_cached = $profile;
    		return $this->profile_cached;
    	}
	    
    } 

    public function userinterests()
    {
        return $this->hasMany('App\Models\UserInterests','userid'); 
    }

    public function spot()
    {
        return $this->hasOne('App\Models\Spotlight','userid');
    }
	/*
    public function riseup()
    {
        return $this->hasOne('App\Models\RiseUp','userid');
    }*/
    
      public function getRiseupAttribute()
	{
		
    return \Cache::remember('user_' . $this->id . '_riseup', 10*60, function()
    {
        return $this->hasMany('App\Models\Riseup', 'userid')->get()->toArray();
    });
    
	}
    
    public function credits()
    {
        return $this->hasOne('App\Models\Credit','userid');
    }

    public function match()
    {
        return $this->hasMany('App\Models\Match','user1');
    }

    public function visitor()
    {
        return $this->hasMany('App\Models\Visitor','user1');
    }

    public function encounter()
    {
        return $this->hasMany('App\Models\Encounter','user1');
    }
    
    /*public function photos()
    {
        return $this->hasMany('App\Models\Photo', 'userid');
        //return $this->cachePhotos;
    } */

    

    public function getPhotosAttribute()
	{
		
		if($this->photos_cached){ 
		    return $this->photos_cached;
		}
		    
	    
        $cache =  \Cache::remember("user_{$this->id}_photos", 10 * 60, function(){
            $photos = $this->hasMany('App\Models\Photo', 'userid')->get();
            return serialize($photos);
        });

    	$photosCollection = unserialize($cache);
    	
    	
    	$this->photos_cached = $photosCollection->reverse();
    	return $this->photos_cached;
	}  


	
    public function age() {
        return (int) ((time() - strtotime($this->dob)) / 3600 / 24 / 365);
        
    }

    public function getFormatedDOB()
    {
        return date('d.m.Y', strtotime($this->dob));
    }

    public function getJoining()
    {
        if($this->created_at == null)
            return null;
        
        return date('d.m.Y', strtotime($this->created_at));
    }

    public function getFormatedUpdatedAt()
    {
        if($this->updated_at == null)
            return null;
        
        return date('D, dS - M - Y h : i : s A', strtotime($this->updated_at));
    }

    public function getFormatedDeletedAt()
    {
        if($this->deleted_at == null)
            return null;
        
        return date('D, dS - M - Y h : i : s A', strtotime($this->deleted_at));
    }

    //saikt's code 28/11/2015
    public function riseupDate() {

        $riseup = $this->riseup;

        if (!$riseup) {

            return 0;
        }
        
        return $riseup->updated_at;
    }

    //this function generates user profile pic url and reutrn full url path
    public function profile_pic_url()
    {
        return asset('uploads/others/original/'. $this->profile_pic_url);
    }

    public function getThumbnailPicUrlAttribute()
    {
        return asset('uploads/others/thumbnails/'. $this->profile_pic_url);
    }
    
     public function thumbnail_pic_url()
    {
        return asset('uploads/others/thumbnails/'. $this->profile_pic_url);
    }

    public function encounter_pic_url()
    {
        return asset('uploads/others/encounters/'. $this->profile_pic_url);
    }

    public function others_pic_url()
    {
        return asset('uploads/others/'. $this->profile_pic_url);
    }

/*
    public function user_super_powers()
    {
        return $this->hasMany('App\Models\UserSuperPowers','user_id');
    }
    */
    
     public function getUserSuperPowersAttribute(){
	    
	    if($this->superpower_cached){ 
		    return $this->superpower_cached;
		    
		    }
		    else{ 
	    $cache =  \Cache::remember('user_' . $this->id . '_superpower', 10*60, function()
    {
	    $c = $this->hasMany('App\Models\UserSuperPowers', 'user_id')->get()->first();
	    
	    if($c){
		    return serialize($c->getAttributes());
	    }
        return serialize(array());
    });
    	$cache = unserialize($cache);
    	if($cache){ 
		$profile = new UserSuperPowers($cache);
		$profile->exists = true;
    	$this->superpower_cached = $profile;
    	
    		return $this->superpower_cached;
    		}
    		else{
	    		return null;
    		}
    	}
	    
    }
    /*
    public function cacheSuperpower()
	{
		
    $cache =  \Cache::remember('user_' . $this->id . '_superpower', 10*60, function()
    {
        return $this->hasMany('App\Models\UserSuperPowers','user_id')->first()->toArray();
    });
    
    return $cache;
    
	} */


    //this funciton will return whether user has activated super power or not
    public function isSuperPowerActivated () {
    	      $superpower = $this->getUserSuperPowersAttribute();
         
         //dd($superpower);
		 
        if ($superpower) {
			
            if ( (Auth::user() && Auth::user()->id == $this->id) && (date_create(date('Y-m-d')) <= date_create($superpower->expired_at)) ) {
			
                $this->isSuperpower = true;
				return $this->isSuperpower;
            } else if ($superpower->hide_superpowers != 1 && (date_create(date('Y-m-d')) <= date_create($superpower->expired_at)) ) {
				
                $this->isSuperpower = true;
				return $this->isSuperpower;
            }
        } 
		else{ 
			
        $this->isSuperpower = false;
        } 
       
        
     $this->isSuperpower = false;
     return $this->isSuperpower;
     
     
       //return $this->isSuperpower;
    }

	public function superpowerdaysleft() {
		
		 $superpower = $this->getUserSuperPowersAttribute();
		 
		 
        if ($superpower) {

			$days = date_create($superpower->expired_at)->diff((date_create(date('Y-m-d'))))->format("%a");
            return $days;
		}
		return "-9999";

		
	}


    //this method will return a user is having invisible mode activated or not
    public function isInvisible () {

        $superpower = $this->user_super_powers;
        //if exists in superpower table
        if ($superpower &&  (date_create(date('Y-m-d')) <= date_create($superpower->expired_at)) && $superpower->invisible_mode == 1) {

            return true;

        } else {

            return false;
        }

    }



    //this function returns online status
    public function onlineStatus () {

        $to_time   = strtotime( date('Y-m-d H:i:s') );
        $from_time = strtotime($this->last_request);
        $minute    = round(abs($to_time - $from_time) / 60);
        $online_status = $this->settings_get('online_status');
        
        
		if ($online_status != 0 && $minute <= 5) {
		
		    return true;
		}
		return false;

    }



    //block user relationships
    public function blocked_users () {

        return $this->hasMany('App\Models\BlockUsers','user1');
    }

    public function users_blocked_me () {

        return $this->hasMany('App\Models\BlockUsers','user2');
    }


    //this method will return blocked users
    public function blocked_by_auth_user () {

        $auth_user_id = Auth::user()->id;

        $block = $this->users_blocked_me()->where('user1', $auth_user_id)->first();

        if ($block) {
            
            return true;
        }

        return false;

    }


    public function blocked_auth_user () {

        $auth_user_id = Auth::user()->id;

        $block = $this->blocked_users()->where('user2', $auth_user_id)->first();

        if ($block) {
            
            return true;
        }

        return false;
    }
/*
    public function settings() {

        return $this->hasMany('App\Models\UserSettings','userid');        
    } */
    
    public function getSettingsAttribute(){
	    

        if($this->settings_cached){ 
            return $this->settings_cached;
        }
            
        
        $cache =  \Cache::remember('user_' . $this->id . '_settings', 10 * 60, function(){
            $settings = $this->hasMany('App\Models\UserSettings', 'userid')->get();
            return serialize($settings);
        });

        $settingsCollection = unserialize($cache);
        
        
        return $this->settings_cached = $settingsCollection;
	    
    }
    

    public function settings_get($key)
    {
	    $user_settings = $this->settings;

        $user_setting = $user_settings->where('key', $key)->first();

        if($user_setting) {
            return $user_setting->value;
        } else {
            return 1;
        }

    }

    public function settings_save($key, $value)
    {
        $set = UserSettings::where("userid",$this->id)->where('key','=',$key)->first(); 
        
        if($set)
        {
            $set->value = $value;
        }
        else
        {
            $set = new UserSettings;
            $set->userid = $this->id;
            $set->key = $key;
            $set->value = $value;
        }
        $set->save();
    }

    //social links code
    public function social_login_links () {

        return $this->hasMany('App\Models\UserSocialLogin','userid');
    }

    public function get_social_links () {

        $links = [];

        foreach ($this->social_login_links as $link) {
            array_push($links, $link->src);
        }

        return $links;
    }
    
    public function socialAccountVerifications()
    {
        $userSocialLogins = $this->social_login_links()->select('src')->get();       
        
        $socialVerifications = [
            "socialVerificationCount" => 0,
            "socialVerificationAccounts" => [
            ]
        ]; 

        foreach($userSocialLogins as $login) {
            $verified = Plugin::fire("{$login->src}_verification");
            
            if(isset($verified[0])) {
                $socialVerifications["socialVerificationCount"] += 1;
                $socialVerifications["socialVerificationAccounts"][$login->src] = $verified[0];
            }
        }

        return $socialVerifications;
    }



    public function is_social_verified() {
	    
	    $count = $this->social_login_links()->count();
	    return ($count > 0) ? true : false;
    }



    protected function incrementSlugNumber($slug) 
    {
        $array = explode("-", $slug);
        return $array[count($array)-1] + 1;
    }

    public function generateSlug($name)
    { 
        $slug_name = str_slug($name);
        $slug = $this->where('id', '<>', $this->id)
                    ->where(function($query) use($slug_name){
                        $query->where('slug_name', '=', $slug_name)
                            ->orWhere('slug_name', 'LIKE', "{$slug_name}-%");
                    })->orderBy('slug_name', 'DESC')
                    ->select('slug_name')
                    ->first();
       
        /* if slug exists and slug-name matches like saikat-dutta and saikat-dutta */
        if($slug && $slug->slug_name === $slug_name) {
            return "{$slug_name}-1";
        } else if ($slug) {

            $number = $this->incrementSlugNumber($slug->slug_name);
            return "{$slug_name}-{$number}";
        } else {
            return $slug_name;
        }
    }


    public function createSlugName()
    {
        if(!$this->name || !$this->slug_name == null) 
            return "";
        $this->slug_name = $this->generateSlug($this->name);
        return $this->slug_name;
    }

    public function createAndSaveSlugName()
    {
        $this->createSlugName();
        $this->save();
        return $this;
    }


    public function recreateSlugNameAndSave()
    {
        if(!$this->name) 
            return "";
        $this->slug_name = $this->generateSlug($this->name);
        $this->save();
        return $this;
    }

}
