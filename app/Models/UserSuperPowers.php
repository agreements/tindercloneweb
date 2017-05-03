<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSuperPowers extends Model
{
    use SoftDeletes;
    
    protected $table = 'user_superpowers';
    protected $dates = ['deleted_at'];
    public $timestamps = true;
    protected $fillable = ['id', 'user_id', 'invisible_mode', 'hide_superpowers', 'expired_at', 'created_at', 'updated_at', 'deleted_at'];

    public static function boot()
    {
       parent::boot();
       static::saved(function($userSuperpower){
	   			
	   			
            \Cache::put('user_' . $userSuperpower->user_id . '_superpower', serialize($userSuperpower->getAttributes()), 10*60);      

       });
       
    }
}