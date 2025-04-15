<nav class="shadow-none fixed-top py-0 bg-white">
    <div class="container bg-white py-4">
        <div class="d-flex justify-content-between align-items-center">
            {{-- <!-- Left Side: Government Logo -->
            <div class="d-flex align-items-center">
                <img src="{{ asset('img/gov.png') }}" alt="Government Logo" width="40" class="me-2">
                <div class="text-start">
                    <span class="sys-title fw-bold text-danger">{{ __('landing.government of nepal') }}</span><br>
                    <span class="sys-title fw-bold text-danger">{{ __('landing.nepal electricity authority') }}</span><br>
                    <span class="sys-title fw-bold text-success">{{ __('landing.meeting management system') }}</span>
                </div>
            </div> --}}
            

            <!-- Right Side: Nepal Flag, DateTime, Theme Toggle, Language Switch -->
            <div class="d-flex align-items-right gap-2">
                <img src="{{ asset('img/nepalflag.gif') }}" alt="Nepal Flag" height="30" width="30"
                    class="me-3">
                <span class="small">
                    {{ $nepaliDateTime['formatted_date'] }}, <br> {{ $nepaliDateTime['weekday'] }}
                    <span id="nepaliTime"></span>
                </span>

                <!-- Theme Toggle Button -->
                <button id="themeToggler" class="btn btn-icon">
                    <i class="bx bx-moon"></i>
                </button>

                <!-- Language Switch -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="languageDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bx bx-globe me-1"></i>
                        <span class="d-none d-md-inline">
                            {{ Session::get('locale', 'en') === 'np' ? 'नेपाली' : 'English' }}
                        </span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="languageDropdown">
                        <li>
                            <a class="dropdown-item {{ Session::get('locale', 'en') === 'en' ? 'active' : '' }}"
                                href="{{ route('language.switcher', ['locale' => 'en']) }}">
                                <img src="https://flagcdn.com/us.svg" width="20" height="15" alt="US Flag"
                                    class="me-2">
                                English
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ Session::get('locale', 'np') === 'np' ? 'active' : '' }}"
                                href="{{ route('language.switcher', ['locale' => 'np']) }}">
                                <img src="https://flagcdn.com/np.svg" width="20" height="15" alt="Nepal Flag"
                                    class="me-2">
                                नेपाली
                            </a>
                        </li>
                    </ul>
                </div>

                <a href="{{ route('login') }}" class="btn btn-primary">
                    <span class="d-none d-md-inline">{{ __('landing.login') }}</span>
                </a>
            </div>
        </div>
    </div>

</nav>

@push('scripts')
    <script type="module">
        function getNepaliNumber(number) {
            const nepaliNumbers = ['०', '१', '२', '३', '४', '५', '६', '७', '८', '९'];
            return number.toString().split('').map(digit => nepaliNumbers[digit] || digit).join('');
        }

        function updateNepaliTime() {
            const now = new Date();
            const hours = getNepaliNumber(now.getHours().toString().padStart(2, '0'));
            const minutes = getNepaliNumber(now.getMinutes().toString().padStart(2, '0'));
            const seconds = getNepaliNumber(now.getSeconds().toString().padStart(2, '0'));
            const ampm = now.getHours() >= 12 ? 'बेलुका' : 'बिहान';
            const formattedTime = `${hours}:${minutes}:${seconds} ${ampm}`;
            const timeElement = document.getElementById('nepaliTime');
            if (timeElement) timeElement.textContent = formattedTime;
        }

        setInterval(updateNepaliTime, 1000);
        document.addEventListener('DOMContentLoaded', updateNepaliTime);

        // Dark Mode Toggle
        const themeToggler = document.getElementById('themeToggler');
        const body = document.body;
        const currentTheme = localStorage.getItem('theme');

        if (currentTheme) {
            body.classList.add(currentTheme);


            themeToggler.innerHTML = currentTheme === 'dark' ? '<i class="bx bx-sun"></i>' : '<i class="bx bx-moon"></i>';
        }

        themeToggler.addEventListener('click', () => {
            body.classList.toggle('dark');
            const newTheme = body.classList.contains('dark') ? 'dark' : 'light';
            localStorage.setItem('theme', newTheme);
            themeToggler.innerHTML = newTheme === 'dark' ? '<i class="bx bx-sun"></i>' :
                '<i class="bx bx-moon"></i>';
        });
    </script>
@endpush
<style>
    /* Light Theme Default Styles */
    a {
        color: #333;
        text-decoration: none;
    }

    a:hover {
        color: #696cff;
    }

    .navbar-nav .nav-link {
        color: #333 !important;
    }

    .navbar-nav .nav-link:hover {
        color: #696cff !important;
    }

    .navbar-nav .nav-link.active {
        color: #696cff !important;
    }

    .dropdown-menu {
        background-color: #fff;
    }

    .dropdown-item {
        color: #333;
    }

    .dropdown-item:hover {
        background-color: #f5f5f5;
        color: #696cff;
    }

    .dropdown-item.active {
        background-color: #696cff;
        color: #fff !important;
    }

    .landing-footer {
        background-color: #fff;
        color: #333;
    }

    .footer-card {
        background-color: #fff;
        color: #333;
    }

    .footer-card-title {
        color: #333;
    }

    .footer-links a {
        color: #333;
    }

    .footer-links a:hover {
        color: #696cff;
    }

    .footer-bottom {
        background-color: #f5f5f5;
        color: #333;
    }

    .text-muted {
        color: #6c757d !important;
    }

    /* Dark Theme Styles */
    body.dark {
        background-color: #121212;
        color: #ffffff;
    }

    body.dark .card-title {
        color: #ffffff !important;
    }
    body.dark .card-header {
        background-color: #333;
        color: #ffffff !important;
        border-bottom: 1px solid #555;
    }


    /* Add dark mode for headings h1 through h6 */
    body.dark h1,
    body.dark h2,
    body.dark h3,
    body.dark h4,
    body.dark h5,
    body.dark h6 {
        color: #ffffff !important;
    }

    /* [Rest of the previous dark theme styles remain unchanged] */

    body.dark .landing-footer {
        background-color: #1e1e1e;
        color: #ffffff;
    }

    body.dark a {
        color: #ffffff;
    }

    body.dark a:hover {
        color: #a5a5ff;
    }

    body.dark .navbar,
    body.dark .bg-white {
        background-color: #1e1e1e !important;
        color: #ffffff !important;
    }

    body.dark .navbar-nav .nav-link {
        color: #ffffff !important;
    }

    body.dark .navbar-nav .nav-link:hover {
        color: #a5a5ff !important;
    }

    body.dark .navbar-nav .nav-link.active {
        color: #a5a5ff !important;
    }

    body.dark .text-dark {
        color: #ffffff !important;
    }

    body.dark .text-danger {
        color: #ff6666 !important;
    }

    body.dark .text-success {
        color: #66ff66 !important;
    }

    body.dark .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    body.dark .btn-outline-secondary {
        color: #ffffff;
        border-color: #ffffff;
    }

    body.dark .dropdown-menu {
        background-color: #2d2d2d;
        border-color: #444;
    }

    body.dark .dropdown-item {
        color: #ffffff;
    }

    body.dark .dropdown-item:hover {
        background-color: #3d3d3d;
        color: #a5a5ff;
    }

    body.dark .dropdown-item.active {
        background-color: #696cff;
        color: #ffffff !important;
    }

    body.dark .card {
        background-color: #222222;
        color: #ffffff;
        border-color: #444;
    }

    body.dark .card-header {
        background-color: #333;
        border-bottom: 1px solid #555;
    }

    body.dark .landing-footer {
        background-color: #1e1e1e;
        color: #ffffff;
    }

    body.dark .footer-card {
        background-color: #222222;
        color: #ffffff;
    }

    body.dark .footer-card-title {
        color: #ffffff;
    }

    body.dark .footer-links a {
        color: #ffffff;
    }

    body.dark .footer-links a:hover {
        color: #a5a5ff;
    }

    body.dark .footer-bottom {
        background-color: #2d2d2d;
        color: #ffffff;
    }

    body.dark .text-muted {
        color: #b0b0b0 !important;
        /* Lighter gray for muted text in dark mode */
    }

    body.dark iframe {
        filter: brightness(0.8);
        /* Slightly dim the map iframe for better contrast */
    }
</style>
