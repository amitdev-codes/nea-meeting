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
{{-- #sweetalert package --}}
@vite('resources/assets/vendor/libs/typeahead-js/typeahead.css')
@vite('resources/assets/vendor/libs/bs-stepper/bs-stepper.css')
{{-- @vite('resources/assets/vendor/libs/bootstrap-select/bootstrap-select.css') --}}
@vite('resources/assets/vendor/libs/toastr/toastr.css')
@vite('resources/assets/vendor/libs/animate-css/animate.css')
@vite('resources/assets/vendor/libs/sweetalert2/sweetalert2.css')
@vite('resources/assets/vendor/libs/select2/select2.css')
@vite('resources/assets/vendor/libs/tagify/tagify.css')
@vite('resources/assets/vendor/libs/dropzone5/dropzone.css')
{{-- #quill rich text editor --}}
{{-- @vite('resources/assets/vendor/libs/quill/typography.css') --}}
@vite('resources/assets/vendor/libs/quill/typography.css')
@vite('resources/assets/vendor/libs/quill/katex.css')
@vite('resources/assets/vendor/libs/quill/editor.css')

@vite('resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')
{{-- @vite('resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') --}}
{{-- @vite('resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') --}}
{{-- @vite('resources/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') --}}
{{-- @vite('resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') --}}
@vite('resources/assets/vendor/libs/flatpickr/flatpickr.css')
{{-- @vite('resources/assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css') --}}
{{-- @vite('resources/assets/vendor/libs/@form-validation/form-validation.css') --}}
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
                    <div>
                        @endif
                        @yield('content')

                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    @include('components.footer')
                    <!-- / Footer -->
                    <div class="content-backdrop fade"></div>
                </div>
                <!--/ Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        @if ($isMenu)
        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
        @endif
        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
    </div>
    @endsection
    @section('vendor-script')
    @vite('resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')

    <!-- Flat Picker -->
    @vite('resources/assets/vendor/libs/moment/moment.js')
    @vite('resources/assets/vendor/libs/flatpickr/flatpickr.js')
    @vite('resources/assets/vendor/libs/pickr/pickr.js')
    
    <!-- Form Validation -->
    {{-- @vite('resources/assets/vendor/libs/@form-validation/popular.js')
    @vite('resources/assets/vendor/libs/@form-validation/bootstrap5.js')
    @vite('resources/assets/vendor/libs/@form-validation/auto-focus.js') --}}

    {{-- @vite('resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.js') --}}

    @vite('resources/assets/vendor/libs/typeahead-js/typeahead.js')
    @vite('resources/assets/vendor/libs/bs-stepper/bs-stepper.js')
    {{-- @vite('resources/assets/vendor/libs/bootstrap-select/bootstrap-select.js') --}}

    @vite('resources/assets/vendor/libs/toastr/toastr.js')
    @vite('resources/assets/vendor/libs/sweetalert2/sweetalert2.js')
    @vite('resources/assets/vendor/libs/select2/select2.js')
    @vite('resources/assets/vendor/libs/tagify/tagify.js')
    @vite('resources/assets/vendor/libs/dropzone5/dropzone.js')

    @vite('resources/assets/vendor/libs/quill/katex.js')
    @vite('resources/assets/vendor/libs/quill/quill.js')

    {{-- // export pdf  --}}
    {{-- @vite('resources/assets/vendor/libs/dropzone5/dropzone.js') --}}
    @endsection


    @section('page-script')
    @vite('resources/js/app.js')
    {{-- @vite('resources/assets/js/custom-ajax.js') --}}
    {{-- // used for search ind dropwodn --}}
    @vite('resources/assets/js/forms-selects.js')
    {{-- @vite('resources/assets/js/forms-pickers.js') --}}
    @endsection