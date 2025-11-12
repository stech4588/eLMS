<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Prompt;
use App\Models\User;
use App\Services\OpenAIService;
use Illuminate\Support\Facades\Mail;
use App\Mail\MarketingEmail;
use Carbon\Carbon;

class SendPromptEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prompts:send-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch prompts, generate text using AI, and send emails to the target audience.';

    /**
     * Execute the console command.
     */
    public function handle(OpenAIService $openAIService)
    {
        $this->info('Starting to send prompt emails...');

        $prompts = Prompt::where('is_active', true)->get();

        foreach ($prompts as $prompt) {
            if ($this->isDue($prompt)) {
                $this->info("Processing prompt: {$prompt->title}");

                $generatedText = $openAIService->generateText($prompt->prompt_text, 3);

                if (str_starts_with($generatedText, 'Error:')) {
                    $this->error("Failed to generate text for prompt: {$prompt->title}. Error: {$generatedText}");
                    continue;
                }

                $users = $this->getTargetAudience($prompt->target_audience);

                foreach ($users as $user) {
                    $receivesEmails = $user->emailNotificationSettings()
                        ->where('key', 'receives_marketing_emails')
                        ->first();
                        
                    if ($receivesEmails && $receivesEmails->value) {
                        Mail::to($user->email)->send(new MarketingEmail($generatedText));
                        $this->info("Email sent to: {$user->email}");
                    }
                }

                $prompt->update(['last_sent_at' => Carbon::now()]);
                $this->info("Finished processing prompt: {$prompt->title}");
            }
        }

        $this->info('All prompt emails have been sent.');
    }

    protected function getTargetAudience(string $audience): \Illuminate\Database\Eloquent\Collection
    {
        switch ($audience) {
            case 'students':
                return User::where('type', 'student')->with('emailNotificationSettings')->get();
            case 'instructors':
                return User::where('type', 'instructor')->with('emailNotificationSettings')->get();
            case 'all':
                return User::whereIn('type', ['student', 'instructor'])->with('emailNotificationSettings')->get();
            default:
                return collect();
        }
    }

    protected function isDue(Prompt $prompt): bool
    {
        if (!$prompt->last_sent_at) {
            return true; // Never sent before
        }

        $lastSent = Carbon::parse($prompt->last_sent_at);

        if ($prompt->trigger_condition === 'daily') {
            // Check if it's the right time today
            $now = Carbon::now();
            $scheduledTime = Carbon::parse($prompt->frequency);
            if ($now->format('H:i') >= $scheduledTime->format('H:i') && $lastSent->isBefore($now->startOfDay())) {
                 return true;
            }
        }

        if ($prompt->trigger_condition === 'weekly') {
            $now = Carbon::now();
            $dayOfWeekMap = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            $scheduledDay = array_search(ucfirst(strtolower($prompt->frequency)), $dayOfWeekMap);
            
            if ($now->dayOfWeek == $scheduledDay && $lastSent->isBefore($now->startOfWeek())) {
                return true;
            }
        }
        
        return false;
    }
}
