@php
    $banner = app(\App\Settings\Banner::class);
@endphp

@if($banner->title || $banner->body)
    <div class="nhsuk-notification-banner" data-module="nhsuk-notification-banner" role="region" aria-labelledby="nhsuk-notification-banner-title">
        <div class="nhsuk-notification-banner__header">
            <h2 class="nhsuk-notification-banner__title" id="nhsuk-notification-banner-title">
                {{ $banner->title }}
            </h2>
        </div>
        <div class="nhsuk-notification-banner__content banner-content-full-width">
            {!! $banner->body !!}
        </div>
    </div>
@endif