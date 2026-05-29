@php
    use Illuminate\Support\Facades\Vite;
@endphp
    <!-- laravel style -->
@vite(['resources/assets/vendor/js/helpers.js'])
@vite(['resources/assets/vendor/js/template-customizer.js'])
@vite(['resources/assets/js/config.js'])
<script>
    (function() {
        const theme = localStorage.getItem('templateCustomizer-vertical-menu-template---Style');
        document.addEventListener('DOMContentLoaded', function() {
            const coreDark = document.querySelector('.core-dark-css');
            const themeDark = document.querySelector('.theme-dark-css');
            if (coreDark && themeDark) {
                coreDark.disabled = theme !== 'dark';
                themeDark.disabled = theme !== 'dark';
            }
        });
        // console.log(theme);
    })();
</script>
