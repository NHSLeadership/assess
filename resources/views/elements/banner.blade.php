@php
    $banner = app(\App\Settings\Banner::class);
@endphp

@if($banner->title || $banner->body)
    <div class="nhsuk-notification-banner nhsuk-notification-banner--success" data-module="nhsuk-notification-banner" role="alert" aria-labelledby="nhsuk-notification-banner-title" data-nhsuk-notification-banner-init="">
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