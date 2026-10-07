@extends('errors.minimal')

@section('title', __('Server Error'))
@section('code', '500')
@section('message', __('Server Error'))
@section('explanation')
    <p>
        Sorry for the inconvenience but something went wrong with this service. This error has been logged for future troubleshooting.
    </p>
    <p>
        Please visit our <a href="{{ config('app.contact_us_url') }}">contact us</a> page to find help and support information.
    </p>
@endsection
