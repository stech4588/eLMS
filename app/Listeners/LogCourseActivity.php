<?php

namespace App\Listeners;

use App\Models\ActivityLog;
use App\Events\CourseViewed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogCourseActivity
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
    public function handle(CourseViewed $event): void
    {
        ActivityLog::updateOrCreate(
            [
                'user_id' => $event->user->id,
                'course_id' => $event->course->id,
                'activity_type' => 'course_viewed',
            ],
            [
                'last_viewed_at' => now(),
            ]
        );
    }
}
