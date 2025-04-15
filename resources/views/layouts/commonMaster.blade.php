<!DOCTYPE html>
@php
    $configData = config('custom');
    $isFront = false;
    $locale = App::getLocale();
@endphp

<html class="light-style layout-navbar-fixed layout-compact layout-menu-fixed" lang="{{ $locale }}"
    data-theme="theme-default" dir="ltr" data-assets-path="../../assets/" data-base-url="{{ url('/') }}"
    data-framework="laravel" data-template="vertical-menu-template-" data-style="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>@yield('title') | {{ config('app.name') }} </title>
    <link rel="icon" href="{{ asset('img/favicon.png') }}" type="image/x-icon">
    <meta name="description"
        content="{{ config('variables.templateDescription') ? config('variables.templateDescription') : '' }}" />

    <meta name="keywords"
        content="{{ config('variables.templateKeyword') ? config('variables.templateKeyword') : '' }}">
    <!-- laravel CRUD token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Include Styles -->
    @include('layouts/sections/styles' . $isFront)
    @vite('resources/css/stylesheets/style.css')
    <!-- Helpers -->
    @include('layouts/sections/scriptsIncludes' . $isFront)
</head>

<body>
    <!-- Layout Content -->
    @yield('layoutContent')
    <!-- Include Scripts -->
    <script>
        if (typeof require === 'undefined') {
            window.require = function(module) {
                console.warn(`require('${module}') called but not supported. Ensure ESM imports are used instead.`);
                return undefined; // Or return a mock object if needed
            };
        }
    </script>
    @include('layouts/sections/scripts')
    @stack('scripts')
</body>

</html>
