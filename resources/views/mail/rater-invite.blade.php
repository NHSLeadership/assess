@component('mail::message')
You have been invited to complete an assessment.

Please click the button below:

@component('mail::button', ['url' => $url])
Open assessment
@endcomponent

If the button above does not work, please copy and paste the following link into your browser:

{{ $url }}

For further guidance and support, please visit our
[support page](https://support.leadershipacademy.nhs.uk/).

Best regards,
Assessment System Team
W: [leadershipacademy.nhs.uk](https://leadershipacademy.nhs.uk) | Follow us: @NHSLeadership
@endcomponent
