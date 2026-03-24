@extends('errors::minimal')

@section('title', __('Method Not Allowed'))
@section('code', '405')
@section('message', __('Method Not Allowed'))
@section('explanation')
    <p>
        Sorry for the inconvenience but something went wrong with this service. This error has been logged for future troubleshooting.
    </p>
    <p>
        Please visit our <a href="https://leadershipacademy.nhs.uk/contact-us/">contact us</a> page to find help and support information.
    </p>
@endsection
