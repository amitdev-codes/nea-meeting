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
    <meta name="viewport"content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <!-- PWA Meta Tags -->
    <!-- PWA Meta Tags -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name', 'Laravel') }}">
    
    <!-- iOS Icons -->
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('icons/icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('icons/icon-180x180.png') }}">
    <link rel="apple-touch-icon" sizes="167x167" href="{{ asset('icons/icon-167x167.png') }}">

    <!-- Splash Screen Images for iOS -->
    <link rel="apple-touch-startup-image" media="screen and (device-width: 440px) and (device-height: 956px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)" href="{{ asset('splash_screens /iPhone_16_Pro_Max_landscape.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 402px) and (device-height: 874px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)" href="{{ asset('splash_screens /iPhone_16_Pro_landscape.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 430px) and (device-height: 932px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)" href="{{ asset('splash_screens /iPhone_16_Plus__iPhone_15_Pro_Max__iPhone_15_Plus__iPhone_14_Pro_Max_landscape.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 393px) and (device-height: 852px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)" href="{{ asset('splash_screens /iPhone_16__iPhone_15_Pro__iPhone_15__iPhone_14_Pro_landscape.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 428px) and (device-height: 926px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)" href="{{ asset('splash_screens /iPhone_14_Plus__iPhone_13_Pro_Max__iPhone_12_Pro_Max_landscape.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 390px) and (device-height: 844px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)" href="{{ asset('splash_screens /iPhone_16e__iPhone_14__iPhone_13_Pro__iPhone_13__iPhone_12_Pro__iPhone_12_landscape.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)" href="{{ asset('splash_screens /iPhone_13_mini__iPhone_12_mini__iPhone_11_Pro__iPhone_XS__iPhone_X_landscape.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)" href="{{ asset('splash_screens /iPhone_11_Pro_Max__iPhone_XS_Max_landscape.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)" href="{{ asset('splash_screens /iPhone_11__iPhone_XR_landscape.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)" href="{{ asset('splash_screens /iPhone_8_Plus__iPhone_7_Plus__iPhone_6s_Plus__iPhone_6_Plus_landscape.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)" href="{{ asset('splash_screens /iPhone_8__iPhone_7__iPhone_6s__iPhone_6__4.7__iPhone_SE_landscape.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)" href="{{ asset('splash_screens /4__iPhone_SE__iPod_touch_5th_generation_and_later_landscape.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 1032px) and (device-height: 1376px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)" href="{{ asset('splash_screens /13__iPad_Pro_M4_landscape.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)" href="{{ asset('splash_screens /12.9__iPad_Pro_landscape.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 834px) and (device-height: 1210px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)" href="{{ asset('splash_screens /11__iPad_Pro_M4_landscape.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)" href="{{ asset('splash_screens /11__iPad_Pro__10.5__iPad_Pro_landscape.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 820px) and (device-height: 1180px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)" href="{{ asset('splash_screens /10.9__iPad_Air_landscape.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)" href="{{ asset('splash_screens /10.5__iPad_Air_landscape.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 810px) and (device-height: 1080px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)" href="{{ asset('splash_screens /10.2__iPad_landscape.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)" href="{{ asset('splash_screens /9.7__iPad_Pro__7.9__iPad_mini__9.7__iPad_Air__9.7__iPad_landscape.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 744px) and (device-height: 1133px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)" href="{{ asset('splash_screens /8.3__iPad_Mini_landscape.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 440px) and (device-height: 956px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)" href="{{ asset('splash_screens /iPhone_16_Pro_Max_portrait.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 402px) and (device-height: 874px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)" href="{{ asset('splash_screens /iPhone_16_Pro_portrait.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 430px) and (device-height: 932px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)" href="{{ asset('splash_screens /iPhone_16_Plus__iPhone_15_Pro_Max__iPhone_15_Plus__iPhone_14_Pro_Max_portrait.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 393px) and (device-height: 852px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)" href="{{ asset('splash_screens /iPhone_16__iPhone_15_Pro__iPhone_15__iPhone_14_Pro_portrait.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 428px) and (device-height: 926px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)" href="{{ asset('splash_screens /iPhone_14_Plus__iPhone_13_Pro_Max__iPhone_12_Pro_Max_portrait.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 390px) and (device-height: 844px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)" href="{{ asset('splash_screens /iPhone_16e__iPhone_14__iPhone_13_Pro__iPhone_13__iPhone_12_Pro__iPhone_12_portrait.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)" href="{{ asset('splash_screens /iPhone_13_mini__iPhone_12_mini__iPhone_11_Pro__iPhone_XS__iPhone_X_portrait.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)" href="{{ asset('splash_screens /iPhone_11_Pro_Max__iPhone_XS_Max_portrait.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" href="{{ asset('splash_screens /iPhone_11__iPhone_XR_portrait.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)" href="{{ asset('splash_screens /iPhone_8_Plus__iPhone_7_Plus__iPhone_6s_Plus__iPhone_6_Plus_portrait.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" href="{{ asset('splash_screens /iPhone_8__iPhone_7__iPhone_6s__iPhone_6__4.7__iPhone_SE_portrait.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" href="{{ asset('splash_screens /4__iPhone_SE__iPod_touch_5th_generation_and_later_portrait.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 1032px) and (device-height: 1376px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" href="{{ asset('splash_screens /13__iPad_Pro_M4_portrait.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" href="{{ asset('splash_screens /12.9__iPad_Pro_portrait.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 834px) and (device-height: 1210px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" href="{{ asset('splash_screens /11__iPad_Pro_M4_portrait.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" href="{{ asset('splash_screens /11__iPad_Pro__10.5__iPad_Pro_portrait.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 820px) and (device-height: 1180px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" href="{{ asset('splash_screens /10.9__iPad_Air_portrait.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" href="{{ asset('splash_screens /10.5__iPad_Air_portrait.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 810px) and (device-height: 1080px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" href="{{ asset('splash_screens /10.2__iPad_portrait.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" href="{{ asset('splash_screens /9.7__iPad_Pro__7.9__iPad_mini__9.7__iPad_Air__9.7__iPad_portrait.png')}}">
    <link rel="apple-touch-startup-image" media="screen and (device-width: 744px) and (device-height: 1133px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" href="{{ asset('splash_screens /8.3__iPad_Mini_portrait.png')}}">


    
    <title>@yield('title') | {{ config('app.name') }} </title>
    <link rel="icon" href="{{ asset('img/favicon.png') }}" type="image/x-icon">
    <meta name="description"
        content="{{ config('variables.templateDescription') ? config('variables.templateDescription') : '' }}" />

    <meta name="keywords"
        content="{{ config('variables.templateKeyword') ? config('variables.templateKeyword') : '' }}">
    <!-- laravel CRUD token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        @font-face {
            font-family: Kalimati;
            src: url('/fonts/kalimati.ttf');
        }
        .nepali_td {
            font-family: kalimati, serif; /* Replace 'Your-English-Font' with the desired English font */
        }
        .dropdown-language {
                z-index: 1000;
            }

    </style>
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
    <script type="module">
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/service-worker.js').then(function(registration) {
                    console.log('ServiceWorker registration successful with scope: ', registration.scope);
                }, function(err) {
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        }
    </script>
</body>

</html>
