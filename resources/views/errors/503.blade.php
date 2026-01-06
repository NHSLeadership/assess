@extends('components.layouts.app')

@section('code', '503')

@section('content')
    <div class="nhsuk-width-container nhsuk-u-padding-bottom-5">

        <h1 class="nhsuk-heading-xl nhsuk-u-margin-bottom-4">
            Service unavailable
        </h1>

        <div class="nhsuk-u-reading-width">

            <p class="nhsuk-body">
                We’re currently carrying out maintenance or updates.
            </p>

            <p class="nhsuk-body">
                The service will be available again shortly. Thank you for your patience.
            </p>

            <p class="nhsuk-body">
                <a href="{{ url('/') }}" class="nhsuk-link">Return to the homepage</a>
            </p>

        </div>

    </div>
@endsection
