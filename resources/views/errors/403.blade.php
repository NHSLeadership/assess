@extends('errors::minimal')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Forbidden'))
@section('explanation')
    <p>
        Sorry for the inconvenience.  If you entered a web address please check it was correct.
    </p>
    <p>
        Please visit our <a href="https://leadershipacademy.nhs.uk/contact-us/">contact us</a> page to find help and support information.
    </p>
@endsection

