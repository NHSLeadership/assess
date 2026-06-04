<div>
    @if (session()->has('message'))

        <div class="nhsuk-notification-banner" role="region" aria-labelledby="nhsuk-notification-banner-title"
             data-module="nhsuk-notification-banner">
            <div class="nhsuk-notification-banner__header">
                <h2 class="nhsuk-notification-banner__title" id="nhsuk-notification-banner-title">
                    {{ session('message-heading', __('alerts.messages.title')) }}
                </h2>
            </div>
            <div class="nhsuk-notification-banner__content">
                {!! session('message') !!}
            </div>
        </div>

    @endif

    @if (session()->has('success'))

        <div class="nhsuk-notification-banner nhsuk-notification-banner--success" role="alert"
             aria-labelledby="nhsuk-notification-banner-success-title" data-module="nhsuk-notification-banner">
            <div class="nhsuk-notification-banner__header">
                <h2 class="nhsuk-notification-banner__title" id="nhsuk-notification-banner-success-title">
                    {{ session('success-heading', __('alerts.success.deleted')) }}
                </h2>
            </div>
            <div class="nhsuk-notification-banner__content">
                {!! session('success') !!}
            </div>
        </div>

    @endif

    @if ($errors && count($errors))

        <div class="nhsuk-error-summary" id="error-summary"
             aria-labelledby="error-summary-title" role="alert" tabindex="-1">
            <h2 class="nhsuk-error-summary__title" id="error-summary-title">
                {{ session('error-heading', __('alerts.errors.title')) }}
                <span class="nhsuk-u-visually-hidden">:</span>
            </h2>
            <div class="nhsuk-error-summary__body">
                <ul class="nhsuk-list nhsuk-error-summary__list">
                    @php $errorsDecoded = $errors->messages(); @endphp
                    @foreach ($errorsDecoded as $id => $error)
                        <li>
                            <a href="#{{ $id }}-error-error">
                                {!! $error[0] !!}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

    @endif

    @if (session()->has('error'))

        <div class="nhsuk-error-summary" id="error" aria-labelledby="error-summary-title" role="alert" tabindex="-1">
            <h2 class="nhsuk-error-summary__title" id="error-summary-title">
                {{ session('error-heading', __('alerts.errors.title')) }}
                <span class="nhsuk-u-visually-hidden">:</span>
            </h2>
            <div class="nhsuk-error-summary__body">
                <p>{!! session('error') !!}</p>
            </div>
        </div>

    @endif

    @if (session()->has('warning'))

        <div class="nhsuk-warning-callout" id="warning" role="alert" tabindex="-1">
            <h3 class="nhsuk-warning-callout__label">
                {{ session('warning-heading', __('alerts.warnings.title')) }}
                <span class="nhsuk-u-visually-hidden">:</span>
            </h3>
            <p>{!! session('warning') !!}</p>
        </div>

    @endif
</div>
