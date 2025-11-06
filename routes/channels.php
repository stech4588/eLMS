<?php

use App\Models\Group;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are used
| to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('groups.{groupId}', function ($user, int $groupId) {
    $group = Group::with('members:id')->find($groupId);

    if (!$group) {
        return false;
    }

    return $group->members->contains($user->id);
});

Broadcast::channel('community', function ($user) {
    // Allow any authenticated user; enforce access via page middleware
    return !is_null($user);
});


