<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Repositories\UserRepository;
use App\Models\User;
use Mail;
use stdCLass;
use App\Components\Plugin;

class NewUserReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $userRepo;
    
    protected $signature = 'newuser_reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display new user reminder';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->userRepo = app("App\Repositories\UserRepository");
        $newUsers = User::where('created_at','like',date('Y-m-d').'%')->get();
        $varR = 111.045;
        $arr = array();
        $distance =100;
        foreach($newUsers as $newUser)
        {
            $userLat = number_format($newUser->latitude, 6, '.','');
            $userLng = number_format($newUser->longitude, 6, '.','');
            $minlng = $userLng-($distance/abs(cos(deg2rad($userLat))*$varR));
            $maxlng = $userLng+($distance/abs(cos(deg2rad($userLat))*$varR));
            $minlat = $userLat-($distance/$varR);
            $maxlat= $userLat+($distance/$varR);            
            $minlng = number_format($minlng, 6, '.', '');
            $maxlng = number_format($maxlng, 6, '.', '');
            $minlat = number_format($minlat, 6, '.', '');
            $maxlat = number_format($maxlat, 6, '.', '');    
            $users = User::whereBetween('latitude', [$minlat, $maxlat])->whereBetween('longitude', [$minlng, $maxlng])->get();
            foreach($users as $user)
            {
                array_push($arr, $user);    
            }
            
        }
        
        $arr = array_unique($arr);

        foreach($arr as $user)
        {
            $email_array = new stdCLass;
            $email_array->user = $user;
            $email_array->type = "new_user_reminder";
            
            Plugin::Fire('send_email', $email_array);
        }
    }
}
