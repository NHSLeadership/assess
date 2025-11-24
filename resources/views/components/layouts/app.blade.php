<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex">
    <title>@yield('title', 'Self Assessment Tools - ' . ($title ?? 'Home') )</title>

    <link rel="shortcut icon" href="{{ asset('media/favicons/favicon.ico', !\Illuminate\Support\Facades\App::environment('local')) }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('media/favicons/apple-touch-icon-180x180.png', !\Illuminate\Support\Facades\App::environment('local')) }}">
    <link rel="mask-icon" href="{{ asset('media/favicons/favicon.svg', !\Illuminate\Support\Facades\App::environment('local')) }}" color="#005eb8">
    <link rel="icon" sizes="192x192" href="{{ asset('media/favicons/favicon-192x192.png', !\Illuminate\Support\Facades\App::environment('local')) }}">

    @vite([
        'resources/sass/app.scss',
        'resources/js/app.js',
    ])

    @livewireStyles
    @livewireScripts
</head>

<body class="js-enabled">

<a class="nhsuk-skip-link" href="#maincontent">Skip to main content</a>

@include('elements.header')

@if (Config::get('app.alert_banner_on',false) && (Route::is('home') || Route::is('register')))
    @include('elements.banner')
@endif

<div class="nhsuk-width-container">
    {{ Breadcrumbs::render(Route::currentRouteName() ?? 'home') }}

    <main class="nhsuk-main-wrapper " id="maincontent" role="main">
        @yield('content', $slot ?? '')
    </main>
</div>

@include('elements.footer')

<script>
    document.body.className = ((document.body.className) ? document.body.className + ' js-enabled' : 'js-enabled');
</script>
</body>

</html>