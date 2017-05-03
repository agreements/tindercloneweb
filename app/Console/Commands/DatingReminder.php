<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Models\User;
use Mail;
use stdCLass;
use App\Components\Plugin;

class DatingReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'dating_reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display a dating reminder';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        $fromDate = date("Y-m-d", strtotime("-7 days"));

        $users = User::where('updated_at','<',$fromDate)->get();

        foreach($users as $user)
        {
            $email_array = new stdCLass;
            $email_array->user = $user;
            $email_array->type = "dating_reminder";
            
            Plugin::fire('send_email', $email_array);
            
        }
    }
}
