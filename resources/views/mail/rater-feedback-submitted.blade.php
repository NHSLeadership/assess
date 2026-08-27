@component('mail::message')
    A rater has submitted their feedback for your assessment.
    <br><br>
    You can view the progress of your assessment using the button below.
    <br><br>
    @component('mail::button', ['url' => route('assessment-raters', ['assessmentId' => $assessment->id])])
        View assessment
    @endcomponent
    <br><br>
    For further guidance and support visit our <a href="https://support.leadershipacademy.nhs.uk/">support page</a>.<br><br>
    Best regards,<br><br>
    Assessment System Team<br><br>
    W: <a href="https://leadershipacademy.nhs.uk">leadershipacademy.nhs.uk</a> | Follow us: @NHSLeadership
@endcomponent
