<?php

namespace App\Console;

use App\Console\Commands\ImportSquare;
use App\Console\Commands\RenewTokens;
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
        ImportSquare::class,
        RenewTokens::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(ImportSquare::class)->dailyAt('00:00')->withoutOverlapping();
        // Run renew tokens every 15 days
        $schedule->command(RenewTokens::class)->monthlyOn(1, '23:00')->withoutOverlapping();
        $schedule->command(RenewTokens::class)->monthlyOn(15, '23:00')->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
