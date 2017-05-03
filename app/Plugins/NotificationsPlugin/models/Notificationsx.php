<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//for soft delete 
use Illuminate\Database\Eloquent\SoftDeletes;

class Notificationsx extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notifications';

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


    protected static $arr = array();

    public static function latest_notifications(){

        $notif = Notifications::where('to_user','=',$logId)->orderBy('created_at', 'desc')->first();
        return Notifications::formatter($notif->type, $notif);
    }

    public static function add_formatter($type,$func)
    {   
        array_push(self::$arr, $func);
    }

    public function formatter($type,$notif)
    {
        foreach(self::$arr as $func)
        {
            return $func($notif);
        }
    }

}
