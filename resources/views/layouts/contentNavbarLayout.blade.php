@extends('layouts/commonMaster')

@php
    /* Display elements */
    $contentNavbar = true;
    $containerNav = $containerNav ?? 'container-xxl';
    $isNavbar = $isNavbar ?? true;
    $isMenu = $isMenu ?? true;
    $isFlex = $isFlex ?? false;
    $isFooter = $isFooter ?? true;
    /* HTML Classes */
    $navbarDetached = 'navbar-detached';
    /* Content classes */
    $container = $container ?? 'container-xxl';
@endphp

@section('vendor-style')
    @vite('resources/assets/vendor/libs/typeahead-js/typeahead.css')
    @vite('resources/assets/vendor/libs/toastr/toastr.css')
    @vite('resources/assets/vendor/libs/animate-css/animate.css')
    @vite('resources/assets/vendor/libs/sweetalert2/sweetalert2.css')
    @vite('resources/assets/vendor/libs/select2/select2.css')
    @vite('resources/assets/vendor/libs/tagify/tagify.css')
    @vite('resources/assets/vendor/libs/quill/typography.css')
     @vite('resources/assets/vendor/libs/flatpickr/flatpickr.css')
    {{-- @vite('resources/assets/vendor/libs/dropzone5/dropzone.css')
    @vite('resources/assets/vendor/libs/quill/typography.css')
    @vite('resources/assets/vendor/libs/quill/katex.css')
    @vite('resources/assets/vendor/libs/quill/editor.css')
    @vite('resources/assets/vendor/libs/flatpickr/flatpickr.css') --}}

    @stack('vendor-style')
@endsection

@section('layoutContent')
    <div class="layout-wrapper layout-content-navbar {{ $isMenu ? '' : 'layout-without-menu' }}">
        <div class="layout-container">
            @include('components.sidebar')
            <!-- Layout page -->
            <div class="layout-page">
                <!-- BEGIN: Navbar-->
                @include('components.navbar')
                <!-- END: Navbar-->
                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    @if ($isFlex)
                        <div class="{{ $container }} d-flex align-items-stretch flex-grow-1 p-0">
                    @else
                        <div class="{{ $container }}flex-grow-1 px-4 py-4">
                    @endif
                    @yield('content')

                </div>
                @include('components.footer')
                <!-- / Footer -->
                <div class="content-backdrop fade"></div>
            </div>
        </div>
    </div>

    @if ($isMenu)
        <div class="layout-overlay layout-menu-toggle"></div>
    @endif
    <div class="drag-target"></div>
    </div>
@endsection
@section('vendor-script')
    @vite('resources/assets/vendor/libs/moment/moment.js')
    @vite('resources/assets/vendor/libs/flatpickr/flatpickr.js')
    @vite('resources/assets/vendor/libs/pickr/pickr.js')
    @vite('resources/assets/vendor/libs/typeahead-js/typeahead.js')
    @vite('resources/assets/vendor/libs/toastr/toastr.js')
    @vite('resources/assets/vendor/libs/sweetalert2/sweetalert2.js')
    @vite('resources/assets/vendor/libs/select2/select2.js')
    @stack('vendor-script')
@endsection


@section('page-script')
    @vite('resources/js/app.js')
    @vite('resources/assets/js/forms-selects.js')
@endsection
