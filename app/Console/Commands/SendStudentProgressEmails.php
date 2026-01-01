<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\OpenAIService;
use Illuminate\Support\Facades\Mail;
use App\Mail\PromptGeneratedEmail;
use Carbon\Carbon;
use App\Models\Progress;
use Illuminate\Support\Facades\Log;

class SendStudentProgressEmails extends Command
{
    protected $signature = 'progress:send-feedback';
    protected $description = 'Analyzes daily student progress against their goals and sends a motivational or congratulatory email.';

    public function handle(OpenAIService $openAIService)
    {
        $this->info('Starting to send daily student progress feedback emails...');

        $students = User::where('type', 'student')
                        ->whereNotNull('daily_learning_goal')
                        ->with('emailNotificationSettings')
                        ->get();

        foreach ($students as $student) {
            $this->info("Processing student: {$student->name} (ID: {$student->id})");

            // Calculate total watch time for today in hours
            $today = Carbon::today();
            $watchTimeInSeconds = Progress::where('user_id', $student->id)
                ->whereDate('last_watched_at', $today)
                ->sum('watched_duration');
            
            $watchTimeInHours = $watchTimeInSeconds / 3600;

            $this->info("Student goal: {$student->daily_learning_goal} hours. Today's watch time: {$watchTimeInHours} hours.");

            $prompt = $this->generatePromptForStudent($student, $watchTimeInHours);

            if ($prompt) {
                $this->info("Generating email content for {$student->name}...");
                $generatedText = $openAIService->generateText($prompt, 4);

                if (str_starts_with($generatedText, 'Error:')) {
                    $this->error("Failed to generate text for student {$student->id}. Error: {$generatedText}");
                    continue;
                }

                $goalHours = (int) $student->daily_learning_goal;

                $receivesEmails = $student->emailNotificationSettings()
                    ->where('key', 'receives_prompt_generated_emails')
                    ->first();

                if ($receivesEmails && $receivesEmails->value) {
                    try {
                        Mail::to($student->email)->send(new PromptGeneratedEmail(
                            $generatedText,
                            $student->name,
                            $goalHours,
                            $watchTimeInHours
                        ));
                        $this->info("Email sent to: {$student->email}");
                    } catch (\Exception $e) {
                        Log::error("Failed to send progress feedback email to {$student->email}: " . $e->getMessage());
                        $this->error("Failed to send email to {$student->email}: " . $e->getMessage());
                    }
                }
            } else {
                $this->info("No email required for {$student->name} today.");
            }
        }

        $this->info('Daily student progress feedback emails have been processed.');
    }

    protected function generatePromptForStudent(User $student, float $watchTimeInHours): ?string
    {
        $goalHours = (int) $student->daily_learning_goal;
        if ($goalHours === 4) { // Treat '3+' hours as a 3-hour goal
            $goalHours = 3;
        }

        if ($watchTimeInHours < $goalHours) {
            // Motivational Prompt
            return "My student, {$student->name}, set a goal to study for {$goalHours} hours today, but they only studied for ". round($watchTimeInHours, 2) ." hours. Please write a short, kind, and motivational message of 3-4 lines to encourage them to meet their goal tomorrow. Address them directly but do not use their name. Do not sound like a robot.";
        } elseif ($watchTimeInHours >= $goalHours) {
            // Congratulatory Prompt
            return "My student, {$student->name}, met their goal of studying for {$goalHours} hours today! They actually studied for ". round($watchTimeInHours, 2) ." hours. Please write a short, enthusiastic, and congratulatory message of 3-4 lines praising their hard work. Address them directly but do not use their name. Do not sound like a robot.";
        }

        return null;
    }
}
