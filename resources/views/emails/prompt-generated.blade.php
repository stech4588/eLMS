<x-mail::message>
# Hello, {{ $studentName }}!

<x-mail::panel>
{!! nl2br(e($content)) !!}
</x-mail::panel>

---

### Your Daily Progress Summary

<x-mail::table>
| Goal | Your Progress |
|:-----|:--------------|
| {{ $goalHours == 4 ? '3+' : $goalHours }} Hour(s) | {{ round($watchTimeInHours * 60) }} minutes |
</x-mail::table>

Keep up the great work!

Thanks,<br>
MBM University
</x-mail::message>
