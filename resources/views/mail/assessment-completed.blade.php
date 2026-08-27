@component('mail::message')
    @if($user->first_name)
        Dear {{ $user->first_name }},
    @endif
    <br><br>
    Thank you for completing your assessment.<br><br>
    We appreciate the time and effort you’ve invested in completing this.<br><br>
    For further guidance and support visit our <a href="https://support.leadershipacademy.nhs.uk/">support page</a>.<br><br>
    Best regards,<br><br>
    Assessment System Team<br><br>
    W: <a href="https://leadershipacademy.nhs.uk">leadershipacademy.nhs.uk</a> | Follow us: @NHSLeadership
@endcomponent