@extends('components.layouts.app')

@section('code', '401')

@section('content')
    <div class="nhsuk-width-container nhsuk-u-padding-bottom-5">

        <h1 class="nhsuk-heading-xl nhsuk-u-margin-bottom-4">
            You need to sign in
        </h1>

        <div class="nhsuk-u-reading-width">

            @if (!empty($message))
                <p class="nhsuk-body">{{ $message }}</p>
            @else
                <p class="nhsuk-body">
                    You must be signed in to access this page.
                    Your session may have expired, or you may not be logged in.
                </p>
            @endif

            <p class="nhsuk-body">
                <a href="{{ route('login') }}" class="nhsuk-link">Go to the sign‑in page</a>
            </p>

            <p class="nhsuk-body">
                <a href="{{ url('/') }}" class="nhsuk-link">Return to the homepage</a>
            </p>

        </div>

    </div>
@endsection
