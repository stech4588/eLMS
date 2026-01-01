<?php

namespace App\Listeners;

use App\Events\CourseCompleted;
use App\Mail\CourseCompletionEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendCourseCompletionEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CourseCompleted $event): void
    {
        if ($event->user->canReceiveEmail('receives_course_completion_emails')) {
            try {
                Mail::to($event->user->email)->send(new CourseCompletionEmail($event->user, $event->course));
            } catch (\Exception $e) {
                Log::error("Failed to send course completion email to {$event->user->email}: " . $e->getMessage());
            }
        }
    }
}
