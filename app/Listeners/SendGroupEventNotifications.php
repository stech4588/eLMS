<?php

namespace App\Listeners;

use App\Events\GroupEventCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\GroupEventNotification;
use App\Notifications\NewGroupEventNotification as NewGroupEventDbNotification;
use App\Models\User;

class SendGroupEventNotifications implements ShouldQueue
{
    use InteractsWithQueue;

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
    public function handle(GroupEventCreated $event): void
    {
        $groupEvent = $event->event;
        $group = $groupEvent->group()->with(['members' => function ($q) {
            $q->withPivot('receive_email_notifications');
        }])->first();

        foreach ($group->members as $member) {
            $user = $member;

            // Don't notify the user who created the event
            if ($user->id === $groupEvent->user_id) {
                continue;
            }

            // Send database notification to all members
            $user->notify(new NewGroupEventDbNotification($groupEvent));

            // Send email notification if enabled
            if ($member->pivot->receive_email_notifications) {
                Mail::to($user->email)->send(new GroupEventNotification($groupEvent, $user));
            }
        }
    }
}
