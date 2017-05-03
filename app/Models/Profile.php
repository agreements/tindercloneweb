<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


//for soft delete 
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'profile';

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
    
    protected $fillable = array('id', 'userid', 'prefer_gender', 'prefer_age', 'prefer_gender_nearby', 'prefer_online_status', 'prefer_distance_nearby', 'popularity', 'aboutme', 'latitude', 'longitude', 'created_at' , 'updated_at', 'deleted_at');
    
    
    public static function boot()
    {
        parent::boot();

        static::saved(function($profile){
	    
            \Cache::put('user_' . $profile->userid . '_profile', serialize($profile->getAttributes()), 10*60);
            
            
        });
    }


    public function appearance()
	{
		//$appear = "$this->height , $this->weight , $this->bodytype , $this->haircolor , $this->eyecolor";
		
		$appear = null;
		
		if($this->height && $this->height != "Will Reveal Later") {
			
			$appear = $this->height;
		}
		if($this->weight && $this->weight != "Will Reveal Later") {
			
			$appear = $appear ." " .$this->weight ;
		}
		if($this->bodytype && $this->bodytype != "Will Reveal Later") {
			
			$appear = $appear ." ".$this->bodytype;
		}
		if($this->haircolor && $this->haircolor != "Will Reveal Later") {
			
			$appear = $appear ." ".$this->haircolor ." Hair Color";
		}
		if($this->eyecolor && $this->eyecolor != "Will Reveal Later") {
			
			$appear = $appear ." ".$this->eyecolor ." Eyes";
		}
		
		/*
if($appear == null || strlen($appear) <= 12)
				return '';
		else
*/
			return $appear;
	}
}