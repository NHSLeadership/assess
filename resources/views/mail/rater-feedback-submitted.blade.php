@component('mail::message')
A rater has submitted their feedback for your assessment.

You can view the progress of your assessment using the button below.

@component('mail::button', ['url' => route('assessment-raters', ['assessmentId' => $assessment->id])])
View assessment
@endcomponent

For further guidance and support visit our
[support page](https://support.leadershipacademy.nhs.uk/).

NHS Leadership Academy
@endcomponent
