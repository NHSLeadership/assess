@extends('errors.minimal')

@section('title', __('Unrecognised server error'))
@section('code', $exception->getStatusCode() ?? '5xx')
@section('message', 'Unrecognised server error')
@section('explanation')
    <p>
        Sorry for the inconvenience. An unexpected error has occurred.
    </p>
    <p>
        Please visit our <a href="{{ config('app.contact_us_url') }}">contact us</a> page to find help and support information.
    </p>
@endsection
