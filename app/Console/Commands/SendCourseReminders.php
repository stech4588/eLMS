<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Course;
use App\Models\ActivityLog;
use App\Mail\CourseReminder; // We will create this Mailable later
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendCourseReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-course-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Checking for incomplete courses and sending reminders...');

        // Get users who are enrolled in at least one course
        $users = User::whereHas('progressEntries')->with(['progressEntries.video.course', 'emailNotificationSetting'])->get();
        Log::debug('Users with progress entries found: ' . $users->count());

        foreach ($users as $user) {
            $incompleteCourses = [];

            // Group progress entries by course
            $progressByCourse = $user->progressEntries->groupBy(function ($progress) {
                return $progress->video->course->id;
            });

            foreach ($progressByCourse as $courseId => $progressEntries) {
                $course = $progressEntries->first()->video->course;

                if ($course) {
                    Log::debug("  Checking course: {$course->id} - {$course->title}");

                    // Determine if the course is completed based on video progress
                    // For simplicity, we'll consider a course complete if all its videos are marked as completed in progressEntries.
                    // A more robust system would involve checking a dedicated course completion flag or all videos having completed status.
                    $allVideosCompleted = true;
                    foreach ($course->videos as $video) {
                        $videoProgress = $progressEntries->where('video_id', $video->id)->first();
                        if (!$videoProgress || !$videoProgress->completed) {
                            $allVideosCompleted = false;
                            break;
                        }
                    }
                    $courseCompleted = $allVideosCompleted; // This is a simplified check

                    Log::debug("    Course completed status for user {$user->id}, course {$course->id}: " . ($courseCompleted ? 'True' : 'False'));

                    if (!$courseCompleted) {
                        // Find the latest watched video for this course
                        $latestWatchedVideoProgress = $progressEntries->where('activity_type', 'course_viewed') // We need to ensure ActivityLog is used here as before, or refactor
                                                                    ->sortByDesc('last_watched_at')
                                                                    ->first();
                        
                        // However, the ActivityLog approach is better for 'last_viewed_at'
                        $lastViewedLog = ActivityLog::where('user_id', $user->id)
                                                    ->where('course_id', $course->id)
                                                    ->where('activity_type', 'course_viewed')
                                                    ->latest('last_viewed_at')
                                                    ->first();

                        // If the course has been viewed and not completed, and last viewed was more than X days ago
                        if ($lastViewedLog && $lastViewedLog->last_viewed_at->lessThan(Carbon::now()->subMinutes(1))) {
                            Log::debug("    Conditions met for reminder: User {$user->id}, Course {$course->id}.");
                            $incompleteCourses[] = $course;
                        } else {
                            Log::debug("    Conditions NOT met for reminder: User {$user->id}, Course {$course->id}. (Last viewed: " . ($lastViewedLog ? $lastViewedLog->last_viewed_at->toDateTimeString() : 'None') . ", Current time: " . Carbon::now()->toDateTimeString() . ")");
                        }
                    }
                } else {
                    Log::warning("  Progress entry found for user {$user->id} but course not found. Progress ID: {$progressEntries->first()->id}");
                }
            }

            // If there are incomplete courses and the user has been inactive, send an email
            if (!empty($incompleteCourses)) {
                if ($user->canReceiveEmail('receivess_course_reminder_emails')) {
                    $this->info("Sending reminder to {$user->email} for " . count($incompleteCourses) . " incomplete courses.");
                    Log::info("Attempting to send email to {$user->email} for incomplete courses.");
                    Mail::to($user->email)->send(new CourseReminder($user, $incompleteCourses));
                }
            } else {
                Log::debug("No incomplete courses found for user: {$user->id} - {$user->email} that meet reminder criteria.");
            }
        }

        $this->info('Course reminder check complete.');
    }
}
