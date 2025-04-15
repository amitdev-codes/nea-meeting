<!-- BEGIN: Vendor JS-->
@vite([
        // 'resources/assets/vendor/js/dropdown-hover.js',
        //  'resources/assets/vendor/js/mega-dropdown.js',
          'resources/assets/vendor/libs/popper/popper.js',
           'resources/assets/vendor/js/bootstrap.js'])

@vite('resources/js/app.js')
@yield('vendor-script')
{{-- @vite(['resources/assets/js/front-main.js']) --}}
@yield('page-script')
