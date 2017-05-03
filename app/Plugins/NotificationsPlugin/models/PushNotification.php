<?php 

namespace App\Plugins\NotificationsPlugin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PushNotification extends Model
{
    use SoftDeletes;
   
    protected $table = 'push_notifications';
    protected $dates = ['deleted_at'];




    public function registerDevice($userID, $deviceID, $deviceToken)
    {

        if(!is_null($deviceID)) {

            $pushNotification = $this->where('user_id', $userID)
                                ->where('device_id', $deviceID)
                                ->first();

        } else {
            $pushNotification = $this->where('user_id', $userID)
                                ->where('device_token', $deviceToken)
                                ->first();
        }

        


        if($pushNotification) {
            $pushNotification->device_token = trim($deviceToken);
            $pushNotification->save();
        } else {
            $pushNotification = new $this;
            $pushNotification->user_id = $userID;
            $pushNotification->device_id = $deviceID;
            $pushNotification->device_token = trim($deviceToken);
            $pushNotification->save();
        }


        return $pushNotification;

    }





    public function deviceTokens($userID)
    {
        $pushNotifications = $this->where('user_id', $userID)->select('device_token')->get()->toArray();
        $deviceTokens = collect($pushNotifications)->flatten()->toArray();

        return $deviceTokens;
    }



}
