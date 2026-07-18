<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
    protected $middlewareGroups = [
        'web' => [
            // ...existing middleware
            \App\Http\Middleware\CaptureReferralCode::class,
        ],
    ];
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('bmp:retry-events')->everyFiveMinutes();
    }
}
