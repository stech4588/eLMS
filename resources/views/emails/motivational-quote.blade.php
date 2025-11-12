<p>Dear Student,</p>
<p>Here's your daily dose of inspiration:</p>

@foreach ($quotes as $quote)
    <p style="font-style: italic; font-weight: bold;">"{{ $quote->content }}"</p>
    <p> - {{ $quote->author }}</p>
    <br>
@endforeach

<p>Keep up the great work and make the most of your learning journey!</p>
<p>Best regards,</p>
<p>Your eLMS Team</p>
