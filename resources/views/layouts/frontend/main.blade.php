@php

    $settings_model = getSiteSettings();

    $settings = $settings_model?->settings;

    $logo = $settings_model?->company_logo_preview_url;

    $favicon = $settings_model?->favicon_preview_url;

@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ env('APP_NAME') }}</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon"
        href="{{ isset($favicon) ? $favicon : asset('frontend/assets/images/Logo/Logo.png') }}">

    @vite(['resources/frontend/assets/css/fapp.css'])
    @stack('style')
</head>

<body>
    @include('layouts.frontend.header') <!-- Add a header partial -->
    <main>
        @yield('content')
    </main>
    @include('layouts.frontend.footer') <!-- Add a footer partial -->

    @vite(['resources/js/app.js'])
    @vite(['resources/frontend/assets/js/fapp.js'])
    @stack('script')

    <script type="module">
        $(document).ready(function() {
            // console.log("Frontend jQuery is working!");
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            console.log("toaster loaded");
            @if (session('success'))
                toastr.success("{{ session('success') }}");
            @endif
        });
    </script>
</body>

</html>
