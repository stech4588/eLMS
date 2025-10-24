<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;
use App\Services\OpenAIService;
use Illuminate\Support\Facades\Mail;
use App\Mail\WellnessCheckinEmail;

class SendWellnessCheckinEmails extends Command
{
    protected $signature = 'wellness:send-checkins {--email= : Send a test email to the specified address}';
    protected $description = 'Sends motivational or wellness check-in emails to students who have been inactive for 2 or more days.';

    public function handle(OpenAIService $openAIService)
    {
        $this->info('Starting to send wellness check-in emails...');

        $email = $this->option('email');

        if ($email) {
            $user = User::where('email', $email)->first();
            if (!$user) {
                $this->error("User with email {$email} not found.");
                return;
            }

            $this->sendEmail($user, $openAIService, true);
            return;
        }

        $inactiveStudents = User::where('type', 'student')
            ->where('last_login_at', '<', Carbon::now()->subDays(2))
            ->with('emailNotificationSettings')
            ->get();

        foreach ($inactiveStudents as $student) {
            $this->info("Processing inactive student: {$student->name} (ID: {$student->id})");

            $receivesEmails = $student->emailNotificationSettings()
                ->where('key', 'receives_wellness_checkin_emails')
                ->first();

            if ($receivesEmails && $receivesEmails->value) {
                $this->sendEmail($student, $openAIService);
            }
        }

        $this->info('Wellness check-in emails have been processed.');
    }

    private function getPrompts()
    {
        return [
            'wellness' => "Write a short, kind, and motivational message of 3-4 lines to a student named {name} who hasn't been active on our learning platform for a few days. Encourage them to come back and continue their learning journey. Address them directly but do not use their name. Do not sound like a robot.",
            'motivational' => "Write a short, motivational message of 3-4 lines for a student named {name} who might be feeling stuck or unmotivated. Remind them of their goals and that it's okay to take a break, but important to get back on track. Address them directly but do not use their name. Do not sound like a robot. The tone should be encouraging and focus on not giving up."
        ];
    }

    private function sendEmail(User $user, OpenAIService $openAIService, bool $isTest = false)
    {
        $prompts = $this->getPrompts();
        $promptType = array_rand($prompts);
        $promptTemplate = $prompts[$promptType];
        $prompt = str_replace('{name}', $user->name, $promptTemplate);

        if ($isTest) {
            $this->info("Sending a test wellness check-in email to: {$user->email}");
            $this->info("Selected prompt type: {$promptType}");
        } else {
            $this->info("Selected prompt type: {$promptType} for student: {$user->email}");
        }

        $motivationalMessage = $openAIService->generateText($prompt, 4);

        if (str_starts_with($motivationalMessage, 'Error:')) {
            $this->error("Failed to generate text for student {$user->id}. Error: {$motivationalMessage}");
            return;
        }

        Mail::to($user->email)->send(new WellnessCheckinEmail($user->name, $motivationalMessage));
        
        if ($isTest) {
            $this->info("Test email sent successfully to: {$user->email}");
        } else {
            $this->info("Wellness check-in email sent to: {$user->email}");
        }
    }
}
