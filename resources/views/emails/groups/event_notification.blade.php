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

@php
    $chatUrl = route('groups.chat', $event->group_id);
@endphp

<x-mail::button :url="$chatUrl">
View Event in Group
</x-mail::button>

@if(!empty($event->call_link))
<x-mail::button :url="$event->call_link">
Join Call
</x-mail::button>
@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
