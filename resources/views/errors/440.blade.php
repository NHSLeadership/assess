@extends('errors.minimal')

@section('title', __('Sign in Timeout'))
@section('code', '440')
@section('message', __('Sign in Timeout'))
@section('explanation')
    <p>
        Sorry for the inconvenience but the client's session has expired. Please try to <a href="{{ route('login') }}">{{__('sign in')}}</a> again.
    </p>
    <p>
        Please visit our <a href="{{ config('app.contact_us_url') }}">contact us</a> page to find help and support information.
    </p>
@endsection
