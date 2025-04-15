@extends('layouts/contentNavbarLayout')

@section('content')


<x-breadcrumb title="Update Site Settings" :items="[['label' => 'Update Site Settings']]" />
<div class="container-xxl">
    <form action="{{ route('admin.site-settings.update') }}" method="POST" enctype="multipart/form-data" id="companyInfo">
        @csrf
        @method('patch')

        <input type="hidden" name="active_settings_tab" id="activeSettingsTab"
            value="{{ session('active_settings_tab', 'navs-company-info') }}">

        <div class="nav-align-left nav-tabs-shadow mb-6">
            <ul class="nav nav-tabs py-4" role="tablist">

                <li class="nav-item " role="presentation">
                    <button type="button" data-bs-target="#navs-company-info" aria-controls="navs-company-info"
                        class="nav-link {{ session('active_settings_tab') === 'navs-company-info' ? 'active' : '' }}"
                        role="tab" data-bs-toggle="tab" aria-selected="true" tabindex="0">
                        <span class="d-none d-sm-block"><i
                                class="tf-icons mb-1 bx bx-buildings bx-sm me-1_5 align-text-bottom"></i>{{ __('field.company_information') }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" data-bs-target="#navs-contact-info" aria-controls="navs-contact-info"
                        class="nav-link {{ session('active_settings_tab') === 'navs-contact-info' ? 'active' : '' }}"
                        role="tab" data-bs-toggle="tab" aria-selected="false" tabindex="-1">
                        <span class="d-none d-sm-block"><i
                                class="tf-icons mb-1 bx bx-phone bx-sm me-1_5 align-text-bottom"></i>{{ __('field.contact_information') }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" data-bs-target="#navs-social-links" aria-controls="navs-social-links"
                        class="nav-link {{ session('active_settings_tab') === 'navs-social-links' ? 'active' : '' }}"
                        role="tab" data-bs-toggle="tab" aria-selected="false" tabindex="-1">
                        <span class="d-none d-sm-block"><i
                                class="tf-icons mb-1 bx bxl-instagram bx-sm me-1_5 align-text-bottom"></i>{{ __('field.social_links') }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" data-bs-target="#navs-privacy-terms" aria-controls="navs-privacy-terms"
                        class="nav-link {{ session('active_settings_tab') === 'navs-privacy-terms' ? 'active' : '' }}"
                        role="tab" data-bs-toggle="tab" aria-selected="false" tabindex="-1">
                        <span class="d-none d-sm-block"><i
                                class="tf-icons mb-1 bx bx-mouse-alt bx-sm me-1_5 align-text-bottom"></i>{{ __('field.privacy_terms') }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" data-bs-target="#navs-about-us" aria-controls="navs-about-us"
                        class="nav-link {{ session('active_settings_tab') === 'navs-about-us' ? 'active' : '' }}"
                        role="tab" data-bs-toggle="tab" aria-selected="false" tabindex="-1">
                        <span class="d-none d-sm-block"><i
                                class="tf-icons mb-1 bx bxs-business bx-sm me-1_5 align-text-bottom"></i>{{ __('field.about_us') }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" data-bs-target="#navs-footer-details" aria-controls="navs-footer-details"
                        class="nav-link {{ session('active_settings_tab') === 'navs-footer-details' ? 'active' : '' }}"
                        role="tab" data-bs-toggle="tab" aria-selected="false" tabindex="-1">
                        <span class="d-none d-sm-block"><i
                                class="tf-icons mb-1 bx bxs-dock-bottom bx-sm me-1_5 align-text-bottom"></i>{{ __('field.footer') }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" data-bs-target="#navs-video-links" aria-controls="navs-video-links"
                        class="nav-link {{ session('active_settings_tab') === 'navs-video-links' ? 'active' : '' }}"
                        role="tab" data-bs-toggle="tab" aria-selected="false" tabindex="-1">
                        <span class="d-none d-sm-block"><i
                                class="tf-icons mb-1 bx bx-movie-play bx-sm me-1_5 align-text-bottom"></i>{{ __('field.video_links') }}</span>
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button type="button" data-bs-target="#navs-application-settings"
                        aria-controls="navs-application-settings"
                        class="nav-link {{ session('active_settings_tab') === 'navs-application-settings' ? 'active' : '' }}"
                        role="tab" data-bs-toggle="tab" aria-selected="false" tabindex="-1">
                        <span class="d-none d-sm-block"><i
                                class="tf-icons mb-1 bx bx-slider-alt rotate-90 bx-sm me-1_5 align-text-bottom"></i>{{ __('field.application_settings') }}</span>
                    </button>
                </li>
                {{-- //artisan settings --}}
                <li class="nav-item" role="presentation">
                    <button type="button" data-bs-target="#navs-artisan-settings" aria-controls="navs-artisan-settings"
                        class="nav-link {{ session('active_settings_tab') === 'navs-artisan-settings' ? 'active' : '' }}"
                        role="tab" data-bs-toggle="tab" aria-selected="false" tabindex="-1">
                        <span class="d-none d-sm-block"><i
                                class="tf-icons mb-1 bx bx-slider-alt rotate-90 bx-sm me-1_5 align-text-bottom"></i>{{ __('field.optimize_settings') }}</span>
                    </button>
                </li>
                {{-- // unlock user settinmgs --}}
                <li class="nav-item" role="presentation">
                    <button type="button" data-bs-target="#navs-lockedUser-settings"
                        aria-controls="navs-lockedUser-settings"
                        class="nav-link {{ session('active_settings_tab') === 'navs-lockedUser-settings' ? 'active' : '' }}"
                        role="tab" data-bs-toggle="tab" aria-selected="false" tabindex="-1">
                        <span class="d-none d-sm-block"><i
                                class="tf-icons mb-1 bx bx-slider-alt rotate-90 bx-sm me-1_5 align-text-bottom"></i>{{ __('field.lockedUser-settings') }}</span>
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button type="button" data-bs-target="#navs-app-env" aria-controls="navs-app-env"
                        class="nav-link {{ session('active_settings_tab') === 'navs-app-env' ? 'active' : '' }}"
                        role="tab" data-bs-toggle="tab" aria-selected="false" tabindex="-1">
                        <span class="d-none d-sm-block"><i
                                class="tf-icons mb-1 bx bx-slider-alt rotate-90 bx-sm me-1_5 align-text-bottom"></i>{{ __('field.app_environment') }}</span>
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade {{ session('active_settings_tab') === 'navs-company-info' ? 'active show' : '' }}"
                    id="navs-company-info" role="tabpanel">
                    @include('pages.site-settings.includes.company-info')
                </div>

                <div class="tab-pane fade {{ session('active_settings_tab') === 'navs-contact-info' ? 'active show' : '' }}"
                    id="navs-contact-info" role="tabpanel">
                    @include('pages.site-settings.includes.contact-info')
                </div>

                <div class="tab-pane fade {{ session('active_settings_tab') === 'navs-social-links' ? 'active show' : '' }}"
                    id="navs-social-links" role="tabpanel">
                    @include('pages.site-settings.includes.social-links')
                </div>

                <div class="tab-pane fade {{ session('active_settings_tab') === 'navs-privacy-terms' ? 'active show' : '' }}"
                    id="navs-privacy-terms" role="tabpanel">
                    @include('pages.site-settings.includes.privacy-terms')
                </div>

                <div class="tab-pane fade {{ session('active_settings_tab') === 'navs-about-us' ? 'active show' : '' }}"
                    id="navs-about-us" role="tabpanel">
                    @include('pages.site-settings.includes.about-us')
                </div>

                <div class="tab-pane fade {{ session('active_settings_tab') === 'navs-footer-details' ? 'active show' : '' }}"
                    id="navs-footer-details" role="tabpanel">
                    @include('pages.site-settings.includes.footer-info')
                </div>

                <div class="tab-pane fade {{ session('active_settings_tab') === 'navs-video-links' ? 'active show' : '' }}"
                    id="navs-video-links" role="tabpanel">
                    @include('pages.site-settings.includes.video-links')
                </div>

                {{-- application settings like  artisan commands --}}
                <div class="tab-pane fade {{ session('active_settings_tab') === 'navs-artisan-settings' ? 'active show' : '' }}"
                    id="navs-artisan-settings" role="tabpanel">
                    @include('pages.site-settings.includes.artisan-settings')
                </div>

                {{-- application settings like  failed password attempts and other --}}
                <div class="tab-pane fade {{ session('active_settings_tab') === 'navs-application-settings' ? 'active show' : '' }}"
                    id="navs-application-settings" role="tabpanel">
                    @include('pages.site-settings.includes.application-settings')
                </div>
                {{-- application settings like  managed locked user --}}
                <div class="tab-pane fade {{ session('active_settings_tab') === 'navs-lockedUser-settings' ? 'active show' : '' }}"
                    id="navs-lockedUser-settings" role="tabpanel">
                    @include('pages.site-settings.includes.lockedUser-settings')
                </div>



                <div class="tab-pane fade {{ session('active_settings_tab') === 'navs-app-env' ? 'active show' : '' }}"
                    id="navs-app-env" role="tabpanel">
                    @include('pages.site-settings.includes.app-environment')
                </div>

                <div class="mt-2">
                    <button type="submit" class="btn btn-primary me-2">{{ __('button.submit') }}</button>
                    <button type="reset" class="btn btn-outline-secondary">{{ __('button.reset') }}</button>
                </div>
            </div>
        </div>

        <div class="content-backdrop fade"></div>
    </form>
</div>
@endsection

@push('script')
<script type="module">
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.nav-tabs .nav-link').forEach(function(tab) {
            tab.addEventListener('click', function() {
                document.getElementById('activeSettingsTab').value = this.getAttribute(
                    'data-bs-target').substring(1);
            });
        });
    });
</script>
@endpush