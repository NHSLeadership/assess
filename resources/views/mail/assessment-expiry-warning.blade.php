@component('mail::message')
One of your assessments is due to be deleted on
**{{ $expiresAt->format('j F Y') }}**
in accordance with our data retention policy.

If you still need this assessment, please sign in and choose to keep it.
If no action is taken, the assessment will be deleted automatically.

@component('mail::button', ['url' => config('app.url')])
Sign in to Assessment System
@endcomponent

For further guidance and support, please visit our
[support page](https://support.leadershipacademy.nhs.uk/).

Best regards,
Assessment System Team
W: [leadershipacademy.nhs.uk](https://leadershipacademy.nhs.uk) | Follow us: @NHSLeadership
@endcomponent