@props([
    'signposts' => collect(),
    'title' => 'Guidance',
    'bannerId' => '',
    'group' => true, // when true: one banner with a foreach inside content
    'pdf' => false,
])
@if(!$pdf)
    <style>
        .nhsuk-notification-banner__content > * {
            max-width: 100% !important;
            width: 100% !important;
        }
    </style>
@endif
@if ($signposts->isEmpty())
    {{-- nothing to render --}}
@else
    @php
        $baseId = $bannerId ?: 'nhsuk-notification-banner';
    @endphp

    @if ($group)
        {{-- Single banner containing all signposts --}}
        <div
                class="nhsuk-notification-banner"
                role="region"
                aria-labelledby="{{ $baseId }}-0"
                data-module="nhsuk-notification-banner"
        >
            <div class="nhsuk-notification-banner__header">
                <h2 class="nhsuk-notification-banner__title" id="{{ $baseId }}-0">
                    {{ $title }}
                </h2>
            </div>

            <div class="nhsuk-notification-banner__content">
                @foreach ($signposts as $sp)
                    @if ($sp instanceof \App\Models\Signpost)
                        <p class="nhsuk-notification-banner__heading" style="width: 100% !important; min-width: 900px !important;">
                            {!! $sp->guidance !!}
                        </p>
                    @endif
                @endforeach
            </div>
        </div>
    @else
        {{-- Original behavior: one banner per signpost --}}
        @foreach ($signposts as $index => $sp)
            @if ($sp instanceof \App\Models\Signpost)
                <div
                        class="nhsuk-notification-banner"
                        role="region"
                        aria-labelledby="{{ $baseId }}-{{ $index }}"
                        data-module="nhsuk-notification-banner"
                >
                    <div class="nhsuk-notification-banner__header">
                        <h2 class="nhsuk-notification-banner__title" id="{{ $baseId }}-{{ $index }}">
                            {{ $title }}
                        </h2>
                    </div>

                    <div class="nhsuk-notification-banner__content">
                        <p class="nhsuk-notification-banner__heading">
                            {!! $sp->guidance !!}
                        </p>
                    </div>
                </div>
            @endif
        @endforeach
    @endif
@endif
