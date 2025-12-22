<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\SendCourseReminders;
use App\Console\Commands\SendDailyMotivationalQuote;
use App\Console\Commands\SendPromptEmails;
use App\Console\Commands\SendStudentProgressEmails;
use App\Console\Commands\SendWellnessCheckinEmails;
use App\Console\Commands\SubscriptionsMonthlyRollover;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by the application.
     *
     * @var array<int, class-string>
     */
    protected $commands = [
        SubscriptionsMonthlyRollover::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command(SendDailyMotivationalQuote::class)->dailyAt('08:00');
        $schedule->command(SendCourseReminders::class)->dailyAt('10:00');
        $schedule->command(SendPromptEmails::class)->everyMinute();
        $schedule->command(SendStudentProgressEmails::class)->dailyAt('19:00');
        $schedule->command(SendWellnessCheckinEmails::class)->daily();
        $schedule->command(SubscriptionsMonthlyRollover::class)->dailyAt('00:15');
    }
}
