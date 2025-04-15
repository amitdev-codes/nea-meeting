@php
    use Illuminate\Support\Facades\Vite;
@endphp
<!-- laravel style -->
@vite(['resources/assets/vendor/js/helpers.js'])
@vite(['resources/assets/vendor/js/template-customizer.js'])
<!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
@vite(['resources/assets/js/config.js'])
<script>
    const themePreference = localStorage.getItem('templateCustomizer-vertical-menu-template---Style');
    document.querySelector('.core-dark-css').disabled = themePreference !== 'dark';
    document.querySelector('.theme-dark-css').disabled = themePreference !== 'dark';
</script>
