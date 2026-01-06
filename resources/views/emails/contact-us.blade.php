@component('mail::message')
# New Contact Form Submission

You have received a new contact form submission from your website.

## Contact Information

**Name:** {{ $contactData['first_name'] }} {{ $contactData['last_name'] }}

**Email:** {{ $contactData['email'] }}

## Message

{{ $contactData['message'] }}

---

Thank you for your attention.

Best regards,<br>
{{ config('app.name') }}
@endcomponent
