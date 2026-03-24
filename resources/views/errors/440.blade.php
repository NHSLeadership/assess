@extends('errors.minimal')

@section('title', __('Login Timeout'))
@section('code', '440')
@section('message', __('Login Timeout'))
@section('explanation')
    <p>
        Sorry for the inconvenience but the client's session has expired. Please try to <a href="{{ route('login') }}">login</a> again.
    </p>
    <p>
        Please visit our <a href="https://leadershipacademy.nhs.uk/contact-us/">contact us</a> page to find help and support information.
    </p>
@endsection
