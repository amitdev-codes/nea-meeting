<!DOCTYPE html>
@php
    $configData = config('custom');
    $isFront = true;
    $locale = session::get('locale');
    App::setLocale($locale);
@endphp

<html class="light-style layout-navbar-fixed layout-compact layout-menu-fixed" lang="{{ $locale }}"
    data-theme="theme-default" dir="ltr" data-assets-path="../../assets/" data-base-url="{{ url('/') }}"
    data-framework="laravel" data-template="vertical-menu-template-" data-style="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>@yield('title') | {{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('img/favicon.png') }}" type="image/x-icon">
    <meta name="description"
        content="{{ config('variables.templateDescription') ? config('variables.templateDescription') : '' }}" />
    <meta name="keywords"
        content="{{ config('variables.templateKeyword') ? config('variables.templateKeyword') : '' }}" />
    <!-- Laravel CSRF token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('landingpage::layouts/sections/stylesFront')
</head>

<body class="d-flex flex-column min-vh-100">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('navbar')

    <main class="flex-grow-1">
        @yield('content')
    </main>

    @yield('footer')

    @include('landingpage::layouts/sections/scriptsFront')
    @stack('scripts')
</body>
</html>