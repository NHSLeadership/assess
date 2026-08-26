@component('mail::message')
    A rater has submitted their feedback for your assessment.
    <br><br>
    You can view the progress of your assessment using the button below.
    <br><br>
    @component('mail::button', ['url' => route('assessment-raters', ['assessmentId' => $assessment->id])])
        View assessment
    @endcomponent
    <br><br>
    For further guidance and support visit our [support page](https://support.leadershipacademy.nhs.uk/).<br><br>
    Best regards,<br><br>
    Assessment System Team<br><br>
    W: [leadershipacademy.nhs.uk](https://leadershipacademy.nhs.uk) | Follow us: @NHSLeadership
@endcomponent
