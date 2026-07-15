@extends('errors.minimal')

@section('title', __('Unauthorised'))
@section('code', '401')
@section('message', __($exception->getMessage() ?: 'Unauthorised'))

@section('explanation')
    <p class="nhsuk-u-margin-bottom-0">
        <a href="https://profile.leadershipacademy.nhs.uk/">
            Profile system
        </a>
    </p>

    <p class="nhsuk-u-margin-top-3">
        <a href="https://support.leadershipacademy.nhs.uk/">
            Support
        </a>
    </p>
@endsection
