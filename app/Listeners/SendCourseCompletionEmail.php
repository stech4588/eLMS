<?php

namespace App\Listeners;

use App\Events\CourseCompleted;
use App\Mail\CourseCompletionEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

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
        if ($event->user->emailNotificationSetting->receives_course_completion_emails) {
            Mail::to($event->user->email)->send(new CourseCompletionEmail($event->user, $event->course));
        }
    }
}
