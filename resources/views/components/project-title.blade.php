@php
    // Check if a locale is passed via query parameter
    $newLocale = request()->query('locale');
    if ($newLocale && in_array($newLocale, ['en', 'np'])) {
        Session::put('locale', $newLocale);
    }

    // Get the current locale from session, default to 'en'
    $locale = Session::get('locale', 'en');
    App::setLocale($locale);
@endphp

<div class="position-relative text-center mb-4">
    <!-- Language Dropdown in Top Right Corner -->
    <div class="position-absolute end-0 top-0">
        <div class="dropdown dropdown-language">
            <a class="nav-link d-flex align-items-center" href="#" id="navbarDropdownLanguage" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://flagcdn.com/{{ app()->getLocale() === 'en' ? 'us' : 'np' }}.svg"
                    class="small" width="20" height="15"
                    alt="{{ app()->getLocale() === 'en' ? 'US Flag' : 'Nepal Flag' }}">
                <span class="ms-1 small">{{ app()->getLocale() === 'en' ? 'English' : 'नेपाली' }}</span>
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownLanguage">
                <li>
                    <a class="dropdown-item {{ app()->getLocale() === 'en' ? 'active' : '' }}"
                        href="?locale=en">
                        <img src="https://flagcdn.com/us.svg" width="20" height="15" alt="US Flag" class="small">
                        <span class="ms-2 small">English</span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item {{ app()->getLocale() === 'np' ? 'active' : '' }}"
                        href="?locale=np">
                        <img src="https://flagcdn.com/np.svg" width="20" height="15" alt="Nepal Flag" class="small">
                        <span class="ms-2 small">नेपाली</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Project Title Content -->
    <img src="{{ asset('assets/img/nea-logo.png') }}" alt="Government Logo" class="mb-3" width="60">
    <div class="text-center flex-grow-1 mb-4">
        <h4 class="card-header mb-1 fw-bold text-danger">
            {{ __('landing.nepal electricity authority') }}
        </h4>
        <h5 class="card-header mb-1 fw-bold text-success">
            {{ __('landing.meeting management system') }}
        </h5>
    </div>
</div>