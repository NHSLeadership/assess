@component('mail::message')

{!! Illuminate\Mail\Markdown::parse($intro) !!}
---

**Feedback for:** {{ $subjectName }}

**Your role:** {{ $role }}

@if(filled($groupName))
**Group:** {{ $groupName }}
@endif

@component('mail::button', ['url' => $url])
Provide feedback
@endcomponent

If the button above does not work copy and paste the following link into your browser:

{{ $url }}

For further guidance and support visit our
[support page](https://support.leadershipacademy.nhs.uk/).

NHS Leadership Academy
@endcomponent
