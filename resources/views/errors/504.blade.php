@extends('errors.minimal')

@section('title', __('Gateway Timeout'))
@section('code', '504')
@section('message', __('Gateway Timeout'))
@section('explanation')
    <p>
        Sorry for the inconvenience but the current session has timed out. This may be caused by a connection being dropped or reaching login expiry time. You may be able to solve this by <a href="{{ route('login') }}">logging in</a> again.
    </p>
    <p>
        Please visit our <a href="https://leadershipacademy.nhs.uk/contact-us/">contact us</a> page to find help and support information.
    </p>
@endsection
