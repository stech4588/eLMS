<?php

namespace App\Providers;

use App\Events\CourseCompleted;
use App\Listeners\SendCourseCompletionEmail;
use App\Listeners\UpdateLastLoginAt;
use App\Events\GroupEventCreated;
use App\Listeners\SendGroupEventNotifications;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Login::class => [
            UpdateLastLoginAt::class,
        ],
        \App\Events\CourseViewed::class => [
            \App\Listeners\LogCourseActivity::class,
        ],
        CourseCompleted::class => [
            SendCourseCompletionEmail::class,
        ],
        GroupEventCreated::class => [
            SendGroupEventNotifications::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
