@extends('layouts.app')

@section('content')
    <h1>@yield('code') @yield('title')</h1>

    @hasSection('message')
        <p class="nhsuk-body-l">@yield('message')</p>
    @endif

    <div class="nhsuk-body">
        @yield('explanation')
    </div>
@endsection
