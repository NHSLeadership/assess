@extends('errors.minimal')

@section('title', __('Page Expired'))
@section('code', '419')
@section('message', __('Your session may have expired. Try signing in again.'))

@section('explanation')
    <p class="nhsuk-u-margin-bottom-0">
        <a href="{{ route('login') }}">
            Sign in
        </a>
    </p>

    <p class="nhsuk-u-margin-top-3">
        <a href="https://support.leadershipacademy.nhs.uk/">
            Support
        </a>
    </p>
@endsection
