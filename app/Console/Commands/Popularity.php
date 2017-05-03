<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Models\User;
use App\Models\Matches;
use App\Models\Visitor;
use App\Models\Profile;
use Mail;
use App\Repositories\ProfileRepository;

class Popularity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $profileRepo;
    
    // public function __construct(ProfileRepository $profileRepo)
    // {
    //     $this->profileRepo = $profileRepo;
    // }

    protected $signature = 'popularity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display a birthday reminder';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->profileRepo = app("App\Repositories\ProfileRepository");
        $city = User::groupBy('city')->get();
        $maxCount = array();
        $maxPopular = array();
        $count = 1;
        foreach($city as $c)
        {
            $arr = array();
            $users = User::where('city','=',$c->city)->get();
            foreach($users as $user)
            {
                $visit_count = Visitor::where('user2','=',$user->id)->count();
                $arr[$user->id] = $visit_count;
            }
            arsort($arr);
            foreach($arr as $key => $value)
            {
                $exists = User::where('id','=',$key)->whereBetween('created_at', [date("Y-m-d", strtotime("-7 days")).'%', date('Y-m-d H:i:s')])->first();
                if($count == 1)
                    {
                        if($value == 0)
                            $maxCount[$c->city] = 1;
                        else
                            $maxCount[$c->city] = $value;
                    }
                if($count <= 5)
                {
                    array_push($maxPopular, $key);
                    $profile = Profile::where('userid','=',$key)->first();
                    if($value == 0)
                        $profile->popularity = 0;
                    elseif($exists)
                        $profile->popularity = 50;
                    else
                        $profile->popularity = 100;
                    $profile->save();
                }
                $count++;
            }
            $count = 1;
        }
        $users = User::whereNotIn('id',$maxPopular);
        foreach($maxCount as $key => $value)
        {
            $citywise_users = $users->where('city','=',$key)->get();
            foreach($citywise_users as $user)
            {
                $popularity = $this->profileRepo->calculate_popularity($user->id , $value);
                $user->profile->popularity = $popularity;
                $user->profile->save();
            }
        }
        
    }
}
