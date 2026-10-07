@extends('errors.minimal')

@section('title', __('Too Many Requests'))
@section('code', '429')
@section('message', __('Too Many Requests'))
@section('explanation')
    <p>
        Sorry for the inconvenience. Please try again later or if you entered a web address please check it was correct.
    </p>
    <p>
        Please visit our <a href="{{ config('app.contact_us_url') }}">contact us</a> page to find help and support information.
    </p>
@endsection
