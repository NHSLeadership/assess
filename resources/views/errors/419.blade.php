@extends('layouts.app')

@section('code', '419')

@section('content')
    <div class="nhsuk-width-container nhsuk-u-padding-bottom-5">

        <h1 class="nhsuk-heading-xl nhsuk-u-margin-bottom-4">
            Page expired
        </h1>

        <div class="nhsuk-u-reading-width">

            <p class="nhsuk-body">
                Your session has expired. This usually happens if the page was left open for too long.
            </p>

            <p class="nhsuk-body">
                Please refresh the page and try again.
            </p>

            <p class="nhsuk-body">
                <a href="{{ url()->current() }}" class="nhsuk-link">Reload this page</a>
            </p>

        </div>

    </div>
@endsection
