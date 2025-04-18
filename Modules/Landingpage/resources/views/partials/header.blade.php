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
            
            <!-- Login Button (Right Aligned) -->
            <div class="flex-shrink-0">
                <a href="{{ route('login') }}" class="btn btn-primary btn-sm rounded-pill px-4">
                    <i class="bx bx-log-in me-1"></i> {{ __('Login') }}
                </a>
            </div>
        </div>
    </div>
</header>
@push('styles')
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
            width: 100%; /* Ensure full width for alignment */
        }

        .header-text div {
            line-height: 1.2;
            transition: color 0.3s ease;
        }

        /* Adjust the margins as needed */
        .header-text div:nth-child(1) {
            font-size: 0.9rem;
        }

        .header-text div:nth-child(2) {
            /* No margin needed since we're using text-start */
        }

        .header-text div:nth-child(3) {
            font-size: 1rem;
        }

        .header-text span {
            display: block;
            line-height: 1.2;
            transition: color 0.3s ease;
        }

        .header-text span:nth-child(1) {
            margin-left: 0;
            font-size: 0.9rem;
        }

        .header-text span:nth-child(2) {
            margin-left: 0.5rem;
        }

        .header-text span:nth-child(3) {
            margin-left: 1rem;
            font-size: 1rem;
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

            .header-text span:nth-child(2) {
                font-size: 1.2rem;
            }

            .nea-logo {
                max-width: 60px;
            }

            .btn-primary {
                padding: 0.25rem 1rem;
                font-size: 0.85rem;
            }
        }
    </style>
@endpush
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
