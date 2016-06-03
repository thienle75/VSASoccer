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
        \App\Console\Commands\Attendance::class,
        \App\Console\Commands\PlayerOfTheMatch::class,
        \App\Console\Commands\PlayerOfTheMatchTitle::class,
        \App\Console\Commands\Ratings::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('soccer:attendance')
            ->wednesdays()
            ->at('6:00');

        $schedule->command('soccer:potm')
            ->wednesdays()
            ->at('19:00');

        $schedule->command('soccer:potm-title')
            ->fridays()
            ->at('17:00');

        $schedule->command('soccer:ratings')
            ->fridays()
            ->at('17:30');
    }
}
