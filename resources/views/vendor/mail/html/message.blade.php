@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url'),])
{{--{{ env('MAIL_FROM_NAME', 'NHS Leadership Academy') }}--}}
@endcomponent
@endslot

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
{{--@isset($subcopy)--}}
{{--@slot('subcopy')--}}
{{--@component('mail::subcopy')--}}
{{--{{ $subcopy }}--}}
{{--@endcomponent--}}
{{--@endslot--}}
{{--@endisset--}}

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
© {{ date('Y') }} NHS England
@endcomponent
@endslot
@endcomponent
