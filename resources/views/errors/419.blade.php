@extends('errors.minimal')

@section('title', __('Page Expired'))
@section('code', '419')
@section('message', __('Your session may have expired. Try signing in again.'))
@section('explanation')
    <a class="nhsuk-action-link" href="{{ route('login') }}">
        <svg class="nhsuk-icon nhsuk-icon--arrow-right-circle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" focusable="false" aria-hidden="true">
            <path d="M12 2a10 10 0 0 0-10 9h11.7l-4-4a1 1 0 0 1 1.5-1.4l5.6 5.7a1 1 0 0 1 0 1.4l-5.6 5.7a1 1 0 0 1-1.5 0 1 1 0 0 1 0-1.4l4-4H2A10 10 0 1 0 12 2z" />
        </svg>
        <span class="nhsuk-action-link__text">Sign in</span>
    </a>
    <p>
        <a href="https://support.leadershipacademy.nhs.uk/">
            Support
        </a>
    </p>
@endsection
