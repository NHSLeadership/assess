@extends('layouts.app')

@section('code', '429')

@section('content')
    <div class="nhsuk-width-container nhsuk-u-padding-bottom-5">

        <h1 class="nhsuk-heading-xl nhsuk-u-margin-bottom-4">
            Too many requests
        </h1>

        <div class="nhsuk-u-reading-width">

            <p class="nhsuk-body">
                You’ve made too many requests in a short period of time.
            </p>

            <p class="nhsuk-body">
                Please wait a moment before trying again.
            </p>

            <p class="nhsuk-body">
                <a href="{{ url('/') }}" class="nhsuk-link">Return to the homepage</a>
            </p>

        </div>

    </div>
@endsection
