<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Daily database backup at 2:00 AM
        $schedule->command('backup:database --type=daily --compress')
                 ->dailyAt('02:00')
                 ->withoutOverlapping()
                 ->runInBackground();
        
        // Weekly database backup every Sunday at 3:00 AM
        $schedule->command('backup:database --type=weekly --compress')
                 ->weeklyOn(0, '03:00')
                 ->withoutOverlapping()
                 ->runInBackground();
        
        // Monthly database backup on 1st day at 4:00 AM
        $schedule->command('backup:database --type=monthly --compress')
                 ->monthlyOn(1, '04:00')
                 ->withoutOverlapping()
                 ->runInBackground();
        
        // Daily file backup at 1:00 AM
        $schedule->command('backup:files --type=daily')
                 ->dailyAt('01:00')
                 ->withoutOverlapping()
                 ->runInBackground();
        
        // Weekly file backup every Sunday at 1:30 AM
        $schedule->command('backup:files --type=weekly')
                 ->weeklyOn(0, '01:30')
                 ->withoutOverlapping()
                 ->runInBackground();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
