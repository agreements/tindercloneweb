<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//for soft delete 
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class FacebookFriends extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'facebook_friends';

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

}
