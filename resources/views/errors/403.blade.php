@extends('layouts.app')

@section('code', '403')

@section('content')
    <div class="nhsuk-width-container nhsuk-u-padding-bottom-5">

        <h1 class="nhsuk-heading-xl nhsuk-u-margin-bottom-4">
            Page not found
        </h1>

        <div class="nhsuk-u-reading-width">

            @if(!empty($message))
                <p class="nhsuk-body">{{ $message }}</p>
            @else
                <p class="nhsuk-body">
                    We can’t find the page you’re looking for. It may have been moved, deleted, or the link may be incorrect.
                </p>
            @endif

            <p class="nhsuk-body">
                If you need help, please visit our
                <a href="https://leadershipacademy.nhs.uk/contact-us/" class="nhsuk-link">
                    contact us
                </a>
                page for support.
            </p>

            <p class="nhsuk-body">
                <a href="{{ url('/') }}" class="nhsuk-link">Return to the homepage</a>
            </p>

        </div>

    </div>
@endsection
