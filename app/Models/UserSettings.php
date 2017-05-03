<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//for soft delete 
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSettings extends Model
{
     use SoftDeletes;
   
    protected $table = 'user_settings';
    protected $dates = ['deleted_at'];
    public $timestamps = true;

    protected $fillable = ['id','userid', 'key' , 'value', 'created_at', 'updated_at', 'deleted_at'];
    
    public static function boot()
    {
        parent::boot();

        static::saved(function($user_settings){
            
            $settings = User::find($user_settings->userid)->settings;
           
            $settings = $settings->reject(function($item) use($user_settings){
                return $user_settings->id == $item->id;
            });
        
            $settings->push($user_settings);
        
            \Cache::put('user_' . $user_settings->userid . '_settings', serialize($settings), 10 * 60); 

            \Cache::put('user_' . $user_settings->userid . '_settings_'.$user_settings->key, serialize($user_settings), 10*60);           
            
        });


        static::deleting(function($user_settings){
            
            $settings = User::find($user_settings->userid)->settings;

            $settings = $settings->reject(function($item) use($user_settings){
                return $user_settings->id == $item->id;
            });

            \Cache::put('user_' . $user_settings->userid . '_settings', serialize($settings), 10 * 60);    

            \Cache::forget('user_' . $user_settings->userid . '_settings_'.$user_settings->key);                  

        });        


    }


    
    
    
    public function get_setting($user_id, $key){
	    
	    $cache = \Cache::remember('user_' . $user_id . '_settings_'.$key, 10*60, function() use($user_id, $key) {
            
            $user_settings = UserSettings::where('userid', $user_id)->where('key', $key)->first();
        
            return serialize($user_settings);
        
        });


        if($cache) {

            $cached_user_setting = unserialize($cache);
            return $cached_user_setting;
        } else {
            return null;
        }
    		
    }

  
}