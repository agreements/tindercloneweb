<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Profile;
use App\Models\Match;
use App\Models\Encounter;
use App\Models\Visitor;
use App\Models\UserInterests;
use App\Models\Settings;
use App\Models\EmailSettings;
use App\Models\Notifications;
use App\Models\NotificationSettings;
use App\Models\SuperPowerPackages;
use App\Models\UserSuperPowers;
use DB;
use App\Repositories\BlockUserRepository;
use App\Models\Transaction;
use App\Models\SuperpowerHistory;
use App\Components\Plugin;
use App\Models\UserSettings;
use stdClass;

class VisitorRepository
{
    public function __construct()
    {
        $this->blockUserRepo = app("App\Repositories\BlockUserRepository");   
        $this->settings = app('App\Models\Settings');     
    }

     /* this function will return all visitors by the id passed into
    and also no of visitors . you can access using (visitor object)->count 
    */
    public function getAllVisitors($id)
    {
        $visitors = User::join('visitors', 'user.id', '=', 'visitors.user1')
                            ->where('user.activate_user', '!=', 'deactivated')->where("visitors.user2","=",$id)
                            ->orderBy('visitors.updated_at', 'desc')->select(['user.*'])
                            ->paginate(9);
        $visitors->setPath('visit');
        return $visitors;
    }

    public function getTotalVisitorsCount($userID)
    {
        return Visitor::join('user', 'user.id', '=', 'visitors.user1')
                        ->where('user.activate_user', '!=', 'deactivated')
                        ->where("visitors.user2", $userID)
                        ->count();
    }

    public function canSeeVisitors($user)
    {
        $visitorSetting = $this->settings->get('visitor_setting');
       
        return ($visitorSetting == "everyone" 
                || $user->isSuperPowerActivated())
                ? true : false;
    }


    //return user objects
    public function getVisitors ($user_id) {

        $visitors = User::join('visitors', 'user.id', '=', 'visitors.user1')
                            ->where('user.activate_user', '!=', 'deactivated')
                            ->where("visitors.user2","=",$user_id)
                            //->whereRaw("visitors.user1 NOT IN( SELECT user1 FROM blockusers WHERE user2 = {$user_id} )")
                            ->orderBy('visitors.updated_at', 'desc')->select(['user.*'])
                            ->paginate(9);
                            
        $visitors->setPath('visit');
        $visitors->count = count($visitors);
        
        return $visitors;
    }




    public function getVisitingDetails($id)
    {
        $obj = new stdClass;
        $fromDate = date("Y-m-d", strtotime("-7 days")).'%';
        $var = date("Y-m", strtotime("-30 days")).'%';
        $month = Notifications::where('type','=','visitor')->where('to_user','=',$id)->whereBetween('created_at', [$var, date('Y-m-d H:i:s')])->count();
        $week = Notifications::where('type','=','visitor')->where('to_user','=',$id)->whereBetween('created_at', [$fromDate, date('Y-m-d H:i:s').'%'])->count();
        $day = Notifications::where('type','=','visitor')->where('to_user','=',$id)->where('created_at', 'like', date('Y-m-d').'%')->count();
        $obj->month = $month;
        $obj->week = $week;
        $obj->day = $day;
        return $obj;
        
    }



    public function get_difference_visit_counts($id)
    {
        $day1 = Notifications::whereBetween('created_at', [date("Y-m-d", strtotime("-24 hours")).'%', date('Y-m-d H:i:s')])->where('to_user','=',$id)->count();
        $day2 = Notifications::whereBetween('created_at', [date("Y-m-d", strtotime("-24 hours")).'%', date("Y-m-d", strtotime("-48 hours")).'%'])->where('to_user','=',$id)->count();
        $day = $day1 - $day2;
        if($day<=0)
            $day = 0;
        else
            $day = 1;
        $week1 = Notifications::whereBetween('created_at', [date("Y-m-d", strtotime("-7 days")).'%', date('Y-m-d H:i:s')])->where('to_user','=',$id)->count();
        $week2 = Notifications::whereBetween('created_at', [date("Y-m-d", strtotime("-7 days")).'%', date("Y-m-d", strtotime("-14 days")).'%'])->where('to_user','=',$id)->count();
        $week = $week1 - $week2;
        if($week<=0)
            $week = 0;
        else
            $week = 1;
        $month1 = Notifications::whereBetween('created_at', [date("Y-m-d", strtotime("-30 days")).'%', date('Y-m-d H:i:s')])->where('to_user','=',$id)->count();
        $month2 = Notifications::whereBetween('created_at', [date("Y-m-d", strtotime("-30 days")).'%', date("Y-m-d", strtotime("-60 days")).'%'])->where('to_user','=',$id)->count();
        $month = $month1 - $month2;
        if($month<=0)
            $month = 0;
        else
            $month = 1;
        $obj = new stdClass;
        $obj->day = $day;
        $obj->week = $week;
        $obj->month = $month;
        return $obj;
    }


    
}
