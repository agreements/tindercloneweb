<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//for soft delete 
use Illuminate\Database\Eloquent\SoftDeletes;

class PhotoAbuseReport extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_photo_abuse_reports';

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

    public function reportingUser()
    {
        return $this->belongsTo('App\Models\User','reporting_user');
    }

    public function reportedUser()
    {
        return $this->belongsTo('App\Models\User','reported_user');
    }

    public function photos()
    {
        return $this->belongsTo('App\Models\Photo','reported_photo');
    }

}