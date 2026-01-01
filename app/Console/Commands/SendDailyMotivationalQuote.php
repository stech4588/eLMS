<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Quote;
use App\Models\User;
use App\Mail\MotivationalQuoteMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendDailyMotivationalQuote extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-daily-motivational-quote';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $quotes = Quote::where('is_active', true)->get();

        if ($quotes->isEmpty()) {
            $this->info('No active quotes found to send.');
            return Command::SUCCESS;
        }

        $users = User::where('role_id', 3)->with('emailNotificationSetting')->get(); // Assuming role_id 3 is for students

        if ($users->isEmpty()) {
            $this->info('No student users found to send motivational quotes to.');
            return Command::SUCCESS;
        }

        foreach ($users as $user) {
            if ($user->emailNotificationSetting && $user->emailNotificationSetting->receives_motivational_quote_emails) {
                try {
                    Mail::to($user->email)->send(new MotivationalQuoteMail($quotes));
                } catch (\Exception $e) {
                    Log::error("Failed to send motivational quote email to {$user->email}: " . $e->getMessage());
                    $this->error("Failed to send email to {$user->email}: " . $e->getMessage());
                }
            }
        }

        $this->info('All active motivational quotes sent successfully to all student users.');

        return Command::SUCCESS;
    }
}
