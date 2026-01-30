@extends('errors::minimal')

@section('title', __('Not Found'))
@section('code', '404')
@section('message', __('Not Found'))
@section('explanation')
    <p>
        Sorry, the page you are looking for does not exist. If you entered a web address please check it was correct.
    </p>
    <p>
        Please visit our <a href="https://leadershipacademy.nhs.uk/contact-us/">contact us</a> page to find help and support information.
    </p>
@endsection
