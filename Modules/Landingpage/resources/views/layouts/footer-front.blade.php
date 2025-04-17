<footer class="footer bg-dark text-white border-top">
    <div class="container py-4">
        <div class="row align-items-center">
            <!-- Copyright -->
            <div class="col-md-6 mb-3 mb-md-0">
                <small>
                    {{ $siteSettings['footer_settings']['copyright_text'] ?? '© ' . date('Y') . ' All Rights Reserved' }}
                    | {{ 'NEA Meeting Management System' }}
                </small>
            </div>
            <!-- Visitor Counter, Last Modified, and Social Links -->
            <div class="col-md-6">
                <div class="d-flex flex-column flex-md-row justify-content-md-end align-items-md-center gap-3">
                    <div class="visitor-counter small">
                        <i class="bx bx-user me-1"></i>
                        {{ __('field.site_visitors') }}:
                        <span class="fw-semibold" id="visit-counter">{{ Cache::get('site_visits', 0) }}</span>
                    </div>
                    <div class="small">
                        <i class="bx bx-time me-1"></i>
                        {{ __('Last Modified') }}:
                        <span class="fw-semibold" id="last-modified">{{ date('F j, Y') }}</span>
                    </div>
                    <div class="social-links d-flex gap-2">
                        @php
                            $socialLinks = [
                                'github' => ['icon' => 'bxl-github', 'color' => 'text-white'],
                                'facebook' => ['icon' => 'bxl-facebook', 'color' => 'text-primary'],
                                'twitter' => ['icon' => 'bxl-twitter', 'color' => 'text-info'],
                                'instagram' => ['icon' => 'bxl-instagram', 'color' => 'text-danger'],
                                'linkedin' => ['icon' => 'bxl-linkedin', 'color' => 'text-primary'],
                            ];
                        @endphp
                        @foreach ($socialLinks as $platform => $data)
                            @if (!empty($siteSettings['social_account_settings'][$platform]))
                                <a href="{{ $siteSettings['social_account_settings'][$platform] }}" target="_blank"
                                    class="{{ $data['color'] }} hover-opacity" data-bs-toggle="tooltip"
                                    title="{{ ucfirst($platform) }}">
                                    <i class="bx {{ $data['icon'] }} fs-5"></i>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
@push('styles')
    <style>
        /* Footer Styles */
        .footer {
            background: #212529;
            transition: all 0.3s ease;
        }

        .footer small {
            font-size: 0.85rem;
            opacity: 0.9;
        }

        .visitor-counter,
        .social-links {
            font-size: 0.85rem;
        }

        .social-links a {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .social-links a:hover {
            opacity: 0.7;
            transform: translateY(-2px);
        }

        .hover-opacity {
            transition: opacity 0.3s ease;
        }

        .hover-opacity:hover {
            opacity: 0.7;
        }

        /* Responsive Adjustments */
        @media (max-width: 767.98px) {
            .footer .row {
                text-align: center;
            }

            .footer .d-flex {
                justify-content: center !important;
            }

            .visitor-counter,
            .social-links {
                font-size: 0.8rem;
            }
        }
    </style>
@endpush
@push('scripts')
<script type="module">
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));
    // Visitor counter logic
    let visits = parseInt(localStorage.getItem('site_visits')) || parseInt(document.getElementById('visit-counter').textContent);
    visits++;
    localStorage.setItem('site_visits', visits);
    document.getElementById('visit-counter').textContent = visits;
</script>
@endpush