<footer class="landing-footer mt-4">
    <div class="container px-4 py-5">
        <div class="row g-4">
            <!-- Contact Info Card -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="footer-card">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-4">
                            <img src="{{ asset('img/gov.png') }}" alt="Government Logo" width="40" class="me-2">
                            <!-- Center: Project Name -->
                            <div class="text-start">
                                <span class="sys-title fw-bold text-danger">{{ __('landing.government of nepal') }}</span><br>
                                <span class="sys-title fw-bold text-danger">{{ __('landing.nepal electricity authority') }}</span><br>
                                <span class="sys-title fw-bold text-success">{{ __('landing.meeting management system') }}</span>
                            </div>
                        </div>
                    </div>

                    <ul class="contact-list list-unstyled">
                        <li class="mb-2">
                            <i class="bx bx-map me-2"></i>
                            <span>{{ __('landing.'.$siteSettings['contact_settings']['address'] ?? '') }}</span>
                        </li>
                        <li class="mb-2">
                            <i class="bx bx-phone me-2"></i>
                            <span>{{ $siteSettings['contact_settings']['phone'] ?? '01-5552971/5010108' }}</span>
                        </li>
                        <li class="mb-2">
                            <i class="bx bx-envelope me-2"></i>
                            <span>{{ $siteSettings['contact_settings']['email'] ?? 'fansep2018@gmail.com' }}</span>
                        </li>
                        <li class="mb-2">
                            <i class="bx bx-globe me-2"></i>
                            <span>{{ $siteSettings['contact_settings']['website'] ?? 'fansep2018@gmail.com' }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Related Links Card -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="footer-card">
                    <div class="footer-card-title fw-bold mb-1 py-5"><i
                            class="bx bx-link me-2"></i>{{ __('field.related_links') }}</div>
                    <ul class="footer-links list-unstyled">
                        @foreach ($siteSettings['quick_links_settings']['related_links'] ?? [] as $link)
                            <li>
                                <a href="{{ $link['url'] }}" target="_blank">
                                    <i class="bx bx-chevron-right me-2"></i>{{ $link['title'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Location Card -->
            <div class="col-12 col-md-6 col-lg-2">
                <div class="footer-card">
                    <div class="footer-card-title fw-bold mb-1 py-5"><i
                            class="bx bx-map-pin me-2"></i>{{ __('field.location') }}</div>
                    @if (!empty($siteSettings['contact_settings']['google_map']))
                        <div class="map-container">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3532.672143308789!2d85.31290731506214!3d27.68131898280129!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb19e1f9c8f8b3%3A0x6b3e7e5b8f7e7f5d!2sHariharbhawan%2C%20Lalitpur%2C%20Nepal!5e0!3m2!1sen!2snp!4v1698765432100!5m2!1sen!2snp"
                                allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                                class="w-100 h-50">
                            </iframe>
                        </div>
                    @else
                        <p class="text-muted">{{ __('field.location_not_set') }}</p>
                    @endif
                </div>
            </div>

            <!-- Video Gallery Card -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="footer-card">
                    <div class="footer-card-title fw-bold mb-1 py-5"><i
                            class="bx bx-video me-2"></i>{{ __('field.video_gallery') }}</div>
                    <ul class="footer-links list-unstyled">
                        @forelse($siteSettings['video_links_settings']['video_gallery'] ?? [] as $video)
                            <li>
                                <a href="{{ $video['url'] }}" target="_blank">
                                    <i class="bx bx-play-circle me-2"></i>{{ $video['title'] }}
                                </a>
                            </li>
                        @empty
                            <p class="text-muted">{{ __('field.no_videos') }}</p>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container px-4 py-3">
            <div class="row align-items-center">
                <div class="col-md-6 mb-2 mb-md-0">
                    <div class="d-flex align-items-center flex-wrap">

                        <small>
                            {{ $siteSettings['footer_settings']['copyright_text'] ?? '© ' . date('Y') . ' All Rights Reserved' }}
                            {{ $siteSettings['company_name'] ?? 'Fansep' }}
                        </small>
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-flex justify-content-md-end align-items-center flex-wrap">
                        <div class="visitor-counter me-4 small">
                            {{ __('field.site_visitors') }}: <span
                                id="visit-counter">{{ Cache::get('site_visits', 0) }}</span>
                        </div>
                        <small class="me-4">Last Modified: <span
                                id="last-modified">{{ date('F j, Y', filemtime(__FILE__)) }}</span></small>
                        <div class="social-links">
                            @php
                                $socialLinks = [
                                    'github' => 'bxl-github',
                                    'facebook' => 'bxl-facebook',
                                    'twitter' => 'bxl-twitter',
                                    'instagram' => 'bxl-instagram',
                                    'linkedin' => 'bxl-linkedin',
                                ];
                            @endphp
                            @foreach ($socialLinks as $platform => $icon)
                                @if (!empty($siteSettings['social_account_settings'][$platform]))
                                    <a href="{{ $siteSettings['social_account_settings'][$platform] }}"
                                        target="_blank">
                                        <i class="bx {{ $icon }} small"></i>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

@push('scripts')
    <script type="module">
        document.addEventListener('DOMContentLoaded', function() {
            let visits = parseInt(localStorage.getItem('site_visits')) || {
                {
                    Cache::get('site_visits', 0)
                }
            };
            visits++;
            localStorage.setItem('site_visits', visits);
            document.getElementById('visit-counter').textContent = visits;
        });
    </script>
@endpush
