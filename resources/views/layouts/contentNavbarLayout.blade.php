@extends('layouts/commonMaster')

@php
    $contentNavbar = true;
    $containerNav = $containerNav ?? 'container-xxl';
    $isNavbar = $isNavbar ?? true;
    $isMenu = $isMenu ?? true;
    $isFlex = $isFlex ?? false;
    $isFooter = $isFooter ?? true;
    $container = $container ?? 'container-xxl';
@endphp

@section('vendor-style')
    @foreach ($commonVendorStyles as $style)
        @vite($style)
    @endforeach
    @stack('vendor-style')
@endsection
@stack('page-style')

@section('layoutContent')
    <div class="layout-wrapper layout-content-navbar {{ $isMenu ? '' : 'layout-without-menu' }}">
        <div class="layout-container">

            @if ($isMenu && auth()->check())
                {!! $cachedSidebar ?? view('components.sidebar')->render() !!}
            @endif

            <div class="layout-page">

                @if ($isNavbar && auth()->check())
                    {!! $cachedNavbar ?? view('components.navbar')->render() !!}
                @endif

                <div class="content-wrapper">
                    <div class="{{ $container }} flex-grow-1 px-3 py-3">
                        @yield('content')
                    </div>

                    @if ($isFooter)
                        {!! $cachedFooter ?? view('components.footer')->render() !!}
                    @endif

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
    @foreach ($commonVendorScripts as $script)
        @vite($script)
    @endforeach
    @stack('vendor-script')
@endsection

@section('page-script')
    @stack('page-script')
@endsection



@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2 on all .select2 elements that are NOT already initialized
            function initSelect2(context = document) {
                $(context).find('select.select2').not('.select2-hidden-accessible').each(function() {
                    $(this).select2({
                        dropdownParent: $(this).closest('.modal').length ? $(this).closest(
                            '.modal') : $('body'),
                        width: '100%',
                        placeholder: $(this).data('placeholder') || 'Select an option',
                        allowClear: true
                    });
                });
            }

            // Initial page load
            initSelect2();

            // Every time a Bootstrap modal is fully shown → re-init Select2 inside it
            $(document).on('shown.bs.modal', '.modal', function() {
                initSelect2(this);
            });

            // Support for Livewire / AJAX loaded content (very common in Sneat)
            document.addEventListener('livewire:load', () => {
                Livewire.hook('message.processed', () => {
                    initSelect2();
                });
            });
        });
    </script>
@endpush
