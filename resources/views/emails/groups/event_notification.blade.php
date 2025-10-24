<x-mail::message>
# New Event in Your Group: {{ $event->group->name }}

Hello {{ $user->name }},

A new event has been created in your group, **{{ $event->group->name }}**.

**Event:** {{ $event->name }}
**Starts at:** {{ $event->start_time->format('M d, Y g:i A') }}

@if($event->description)
**Description:**
{{ $event->description }}
@endif

You can view the group chat for more details.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
