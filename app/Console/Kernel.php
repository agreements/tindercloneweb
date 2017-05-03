<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
        \App\Console\Commands\DatingReminder::class,
        \App\Console\Commands\BirthdayReminder::class,
        \App\Console\Commands\NewUserReminder::class,
        \App\Console\Commands\Popularity::class,
        \App\Console\Commands\FacebookLikesAsInterestCommand::class,
        \App\Console\Commands\FacebookFriendsSaveCommand::class,
        \App\Console\Commands\UnreadMessagesReminder::class,
        \App\Console\Commands\TranslateCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('dating_reminder')
                 ->dailyAt('19:00');
        $schedule->command('newuser_reminder')
                 ->dailyAt('19:00');
        $schedule->command('birthday_reminder')
                 ->dailyAt('18:00');                                  
        $schedule->command('inspire')
                 ->hourly();
        $schedule->command('popularity')
                 ->dailyAt('17:06');

        $schedule->command('unread_messages_reminder')->daily();
    }
}
