@extends('errors::minimal')

@section('title', __('Unauthorized'))
@section('code', '401')
@section('message', __('Unauthorized'))
@section('explanation')
    <p>
        Sorry for the inconvenience. If you entered a web address please check it was correct.
    </p>
    <p>
        Please visit our <a href="{https://leadershipacademy.nhs.uk/contact-us/">contact us</a> page to find help and support information.
    </p>
@endsection
