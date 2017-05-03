<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//for soft delete 
use Illuminate\Database\Eloquent\SoftDeletes;

class SuperpowerHistory extends Model
{
     use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'superpower_history';

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

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function transaction()
    {
        return $this->belongsTo('App\Models\Transaction','trans_table_id');
    }

    public function superpower_package () {
        return $this->belongsTo('App\Models\SuperPowerPackages', 'superpower_package_id')->withTrashed();
    }
}