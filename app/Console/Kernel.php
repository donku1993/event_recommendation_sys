<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\hourlyDataUpdateJob;
use App\Jobs\weeklyDataUpdateJob;
use App\Jobs\monthlyDataUpdateJob;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        $schedule->call(function () {
            dispatch(new hourlyDataUpdateJob());
        })->hourlyAt(5);

        $schedule->call(function () {
            dispatch(new dailyDataUpdateJob());
        })->dailyAt('09:00');

        $schedule->call(function () {
            dispatch(new weeklyDataUpdateJob());
        })->weekly();

        $schedule->call(function () {
            dispatch(new monthlyDataUpdateJob());
        })->monthlyOn(1, '01:00');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
