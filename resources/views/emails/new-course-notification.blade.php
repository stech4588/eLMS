<x-mail::message>
# New Course Alert!

Hello,

A new course, **{{ $course->title }}**, has just been uploaded!

{{ $course->description }}

Click the button below to view the course details and enroll.

<x-mail::button :url="route('courses.show', $course->id)">
View Course
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
