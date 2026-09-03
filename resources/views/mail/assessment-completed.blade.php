@component('mail::message')
@if(filled($subject->name))
Dear {{ $subject->name }},
@endif

Thank you for completing your assessment.

We appreciate the time and effort you’ve invested in completing this.

For further guidance and support visit our
[support page](https://support.leadershipacademy.nhs.uk/).

NHS Leadership Academy
@endcomponent
