<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Models\User;
use App\Models\Match;
use Mail;
use stdCLass;
use App\Components\Plugin;

class BirthdayReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    
    protected $signature = 'birthday_reminder';

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
        $birthday = User::where('dob','like','%'.date('m-d'))->get();
        
        if(count($birthday) != 0)
        {
            foreach($birthday as $birth)
            {
                $email_array = new stdCLass;
                $email_array->user = $birth;
                $email_array->type = 'birthday';  
                Plugin::fire('send_email', $email_array);
                
                $mat_users = Match::where('user1','=',$birth->id)->get();

                if($mat_users)
                {
                    foreach($mat_users as $mat)
                    {
                        $user = User::find($mat->user2);
                        $email_array = new stdCLass;
                        $email_array->user = $user;
                        $email_array->user2 = $birth;
                        $email_array->type = "birthday_reminder";
                        Plugin::fire('send_email', $email_array);
                    }
                }
            } //foreach ends
        } // validation ends
    }
}
