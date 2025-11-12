@component('mail::message')
# Group Invitation

{{ $emailBody }}

@component('mail::button', ['url' => $invitationLink])
Accept Invitation
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
