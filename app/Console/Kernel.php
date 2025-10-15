<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\SendCourseReminders;
use App\Console\Commands\SendDailyMotivationalQuote;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command(SendCourseReminders::class)->daily();
        $schedule->command(SendDailyMotivationalQuote::class)->daily();
    }
}
