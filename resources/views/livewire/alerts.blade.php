<div wire:key="alerts">
    {{-- Session-driven message --}}
    @if (session()->has('message'))
        <div class="nhsuk-inset-text" role="message" tabindex="-1">
            <span class="nhsuk-u-visually-hidden">Message: </span>
            <p>{!! session('message') !!}</p>
        </div>
    @endif

    {{-- Validation errors or session error --}}
    @if ($errors->any() || session()->has('error'))
        <div class="nhsuk-error-summary" role="alert" tabindex="-1">
            <h2 class="nhsuk-error-summary__title">
                {{ __('headers.generic.error') }} <span class="nhsuk-u-visually-hidden">:</span>
            </h2>
            <div class="nhsuk-error-summary__body">
                @if ($errors->any())
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{!! $error !!}</li>
                        @endforeach
                    </ul>
                @else
                    <p>{!! session('error') !!}</p>
                @endif
            </div>
        </div>
    @endif

    {{-- Session-driven warning --}}
    @if (session()->has('warning'))
        <div class="nhsuk-warning-callout" role="warning" tabindex="-1">
            <h3 class="nhsuk-warning-callout__label">
                Warning <span class="nhsuk-u-visually-hidden">:</span>
            </h3>
            <p>{!! session('warning') !!}</p>
        </div>
    @endif

    {{-- Session-driven success --}}
    @if (session()->has('success'))
        <div class="nhsuk-card">
            <div class="nhsuk-card__content">
                <h3 class="nhsuk-card__heading">
                    {!! session('success-heading') !!}
                </h3>
                <p class="nhsuk-card__description">{!! session('success') !!}</p>
            </div>
        </div>
    @endif

    {{-- Event-driven alerts via dispatch/emit --}}
    @if ($type && $message)
        <div x-data="{ show: true }"
             x-init="setTimeout(() => { show = false; $wire.clearAlert() }, 5000)"
             x-show="show"
             wire:ignore.self>
            @switch($type)
                @case('message')
                    <div class="nhsuk-inset-text" role="message" tabindex="-1">
                        <span class="nhsuk-u-visually-hidden">Message: </span>
                        <p>{!! $message !!}</p>
                    </div>
                    @break

                @case('error')
                    <div class="nhsuk-error-summary" role="alert" tabindex="-1">
                        <h2 class="nhsuk-error-summary__title">
                            {{ $heading ?? __('alerts.errors.title') }} <span class="nhsuk-u-visually-hidden">:</span>
                        </h2>
                        <div class="nhsuk-error-summary__body">
                            <p>{!! $message !!}</p>
                        </div>
                    </div>
                    @break

                @case('warning')
                    <div class="nhsuk-warning-callout" role="warning" tabindex="-1">
                        <h3 class="nhsuk-warning-callout__label">
                            Warning <span class="nhsuk-u-visually-hidden">:</span>
                        </h3>
                        <p>{!! $message !!}</p>
                    </div>
                    @break

                @case('success')
                    <div class="nhsuk-card">
                        <div class="nhsuk-card__content">
                            <h3 class="nhsuk-card__heading">
                                {!! $heading ?? 'Success' !!}
                            </h3>
                            <p class="nhsuk-card__description">{!! $message !!}</p>
                        </div>
                    </div>
                    @break
            @endswitch
        </div>
    @endif
</div>