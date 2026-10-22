{{-- Action button component --}}
@if ($url ?? false)
    <a class="nhsuk-button {{--nhsuk-button--login--}}" href="{{$url ?? ''}}">
        {{ $text ?? '' }} <span class="nhsuk-u-visually-hidden">{{ $text_hidden ?? '' }}</span>
    </a>
@endif
