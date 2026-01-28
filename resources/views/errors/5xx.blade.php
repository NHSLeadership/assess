@extends('layouts.app')

@section('code', '5xx')

@section('content')
    <div class="nhsuk-width-container nhsuk-u-padding-bottom-5">

        <h1 class="nhsuk-heading-xl nhsuk-u-margin-bottom-4">
            Something went wrong
        </h1>

        <div class="nhsuk-u-reading-width">

            @if (!empty($message))
                <p class="nhsuk-body">{{ $message }}</p>
            @else
                <p class="nhsuk-body">
                    We’re sorry — something has gone wrong on our side.
                    Please try again later.
                </p>
            @endif

            <p class="nhsuk-body">
                If the problem continues, please visit our
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
