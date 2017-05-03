<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//for soft delete 
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
use App\Models\Visitor;
use App\Models\User;

class Notifications extends Model
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


    protected static $formatters = array();
    protected static $formatters_type = array();

    public static function clear($type)
    {
        // if($type == 'visitor')
        // {
        //     $visit = Visitor::where('user2','=',Auth::user()->id)->first();
        //     $visit->status = 'seen';
        //     $visit->save();
        // }
        
        $clearNotif = Notifications::where('to_user','=',Auth::user()->id)->where('type','=',$type)->where('status','=','unseen')->get();
        foreach($clearNotif as $clear)
        {
            $clear->status = "seen";
            $clear->save();
        }
    }

    public static function get_count($type)
    {
        $count = Notifications::where('to_user','=',Auth::user()->id)->where('type','=',$type)->where('status','=','unseen')->count();
        return $count;
    }

    public static function latest_notifications($logId){
        $notifications = array();
        $notif = Notifications::where('to_user','=',$logId)->orderBy('created_at', 'desc')->first();
        foreach(self::$formatters_type as $type)
        {
            $notifications[$type] = Notifications::where('to_user','=',$logId)->where('type','=',$type)->where('status','=','unseen')->count();   
        }
        $notif->notifications = $notifications;
        $user = User::find($notif->from_user);
        $notif['name'] = $user->name;
        $notif['age'] = $user->age();
        $notif['pic'] = $user->profile_pic_url();
        // $notif->chat = Notifications::where('to_user','=',$logId)->where('type','=','chat')->where('status','=','unseen')->count();
        // $notif->visitor = Notifications::where('to_user','=',$logId)->where('type','=','visitor')->where('status','=','unseen')->count();
        // $notif->liked = Notifications::where('to_user','=',$logId)->where('type','=','liked')->where('status','=','unseen')->count();
        return Notifications::formatter($notif->type, $notif);
    }

    public static function add_formatter($type,$func)
    {   
        self::$formatters[$type] = $func;
        // array_push(self::$formatters, $func);
        // array_push(self::$formatters_type, $type);
    }

    public static function formatter($type,$notif)
    {
        foreach(self::$formatters as $key => $value)
        {
        if($key == $type)
                return $value($notif);
        }
    }

}
