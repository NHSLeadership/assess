@component('mail::message')
@if($user->first_name)
Dear {{ $user->first_name }},
@endif

Thank you for completing your assessment.

We appreciate the time and effort you’ve invested in completing this.

For further guidance and support visit our
[support page](https://support.leadershipacademy.nhs.uk/).

NHS Leadership Academy
@endcomponent
