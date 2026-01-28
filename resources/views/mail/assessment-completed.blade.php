@component('mail::message')
    @if($user->first_name)
        Dear {{ $user->first_name ?? 'Staff' }},
    @endif
    <br><br>
    Thank you for completing your assessment.<br><br>
    We appreciate the time and effort you’ve invested in completing this.<br><br>
    For further guidance and support, please visit our [support page](https://support.leadershipacademy.nhs.uk/).<br><br>
    Best regards,<br><br>
    Assessment System Team<br><br>
    W: [leadershipacademy.nhs.uk](https://leadershipacademy.nhs.uk) | Follow us: @NHSLeadership
@endcomponent