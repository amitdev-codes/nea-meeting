<header class="header sticky-top bg-white shadow-sm border-bottom">
    <div class="container">
        <div class="d-flex align-items-center py-2">
            <!-- Empty spacer to balance the layout -->
            <div class="flex-grow-1 d-flex justify-content-center">
                <!-- Logo and Text Group -->
                <div class="d-flex align-items-center justify-content-center gap-3">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        <img src="{{ asset('assets/img/nea-logo.png') }}" alt="NEA Logo" class="nea-logo" width="80">
                    </div>
                    <!-- Text Content -->
                    <div class="header-text d-flex flex-column gap-1 text-center">
                        <div class="w-100">
                            <span class="fw-bold text-danger fs-4 text-start nepali_td">{{ __('landing.nepal electricity authority') }}</span>
                        </div>
                        <div class="w-100 text-center">
                            <span class="fw-bold text-success nepali_td">{{ __('landing.meeting management system') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Language Switcher -->
            <div class="nav-item dropdown dropdown-language me-3">
                <a class="nav-link d-flex align-items-center language-toggle" href="#" id="navbarDropdownLanguage" role="button"
                   data-bs-toggle="dropdown" aria-expanded="false" aria-label="{{ __('Select Language') }}">
                    <img src="https://flagcdn.com/{{ auth()->user()->locale === 'en' ? 'us' : 'np' }}.svg"
                         class="flag-icon me-1" width="24" height="18"
                         alt="{{ auth()->user()->locale === 'en' ? 'English' : 'Nepali' }} Flag">
                    <span class="language-text fw-medium small">
                        {{ auth()->user()->locale === 'en' ? 'English' : 'नेपाली' }}
                    </span>
                    <i class="bx bx-chevron-down ms-1"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="navbarDropdownLanguage">
                    <!-- English Option -->
                    <li>
                        <a class="dropdown-item d-flex align-items-center {{ auth()->user()->locale === 'en' ? 'active-language' : '' }}"
                           href="{{ route('account.locale', ['locale' => 'en']) }}">
                            <img src="https://flagcdn.com/us.svg" width="24" height="18" alt="English Flag" class="flag-icon me-2">
                            <span class="small">English</span>
                        </a>
                    </li>
                    <!-- Nepali Option -->
                    <li>
                        <a class="dropdown-item d-flex align-items-center {{ auth()->user()->locale === 'np' ? 'active-language' : '' }}"
                           href="{{ route('account.locale', ['locale' => 'np']) }}">
                            <img src="https://flagcdn.com/np.svg" width="24" height="18" alt="Nepali Flag" class="flag-icon me-2">
                            <span class="small">नेपाली</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Login/Logout Button -->
            <div class="flex-shrink-0">
                @auth
                    <a href="{{ route('logout') }}" class="btn btn-primary btn-sm rounded-pill px-4"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bx bx-log-out me-1"></i> {{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @endauth
                @guest
                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm rounded-pill px-4">
                        <i class="bx bx-log-in me-1"></i> {{ __('Login') }}
                    </a>
                @endguest
            </div>
        </div>
    </div>
</header>
@section('page-style')
    <style>
        /* Header Styles */
        .header {
            z-index: 1030;
            transition: all 0.3s ease;
        }

        .nea-logo {
            max-width: 80px;
            height: auto;
            transition: transform 0.3s ease;
        }

        .nea-logo:hover {
            transform: scale(1.05);
        }

        .header-text {
            padding-left: 0.5rem;
            width: 100%;
        }

        .header-text div {
            line-height: 1.2;
            transition: color 0.3s ease;
        }

        .header-text span {
            display: block;
            line-height: 1.2;
            transition: color 0.3s ease;
        }

        /* Nepali Text Styling */
        .nepali_td {

            font-weight: 600;
        }

        /* Language Switcher Styles */
        .language-toggle {
            padding: 0.5rem 0.75rem;
            border-radius: 0.25rem;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .language-toggle:hover {
            background-color: #f8f9fa;
        }

        .flag-icon {
            border-radius: 2px;
            box-shadow: 0 0 2px rgba(0, 0, 0, 0.2);
        }

        .language-text {
            color: #333;
            transition: color 0.2s ease;
        }

        .dropdown-menu {
            min-width: 150px;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .dropdown-item {
            padding: 0.5rem 1rem;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: #e9ecef;
        }

        .active-language {
            background-color: #e6f3ff;
            color: #0b5ed7;
            font-weight: 600;
        }

        .btn-primary {
            font-weight: 500;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            transform: translateY(-2px);
        }

        /* Responsive Adjustments */
        @media (max-width: 767.98px) {
            .header-text {
                max-width: 60%;
            }

            .header-text span {
                font-size: 0.85rem;
            }

            .nea-logo {
                max-width: 60px;
            }

            .btn-primary {
                padding: 0.25rem 1rem;
                font-size: 0.85rem;
            }

            .language-toggle {
                padding: 0.4rem 0.6rem;
            }

            .flag-icon {
                width: 20px;
                height: 15px;
            }

            .language-text {
                font-size: 0.85rem;
            }
        }
    </style>
@endsection
@push('scripts')
    <script type="module">
        const header = document.querySelector('.header');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.add('shadow');
            } else {
                header.classList.remove('shadow');
            }
        });
    </script>
@endpush
