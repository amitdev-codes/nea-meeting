@extends('layouts/commonMaster')
@section('layoutContent')
@php
    $locale = Session::get('locale');
    App::setLocale('np');
@endphp
    <div class="authentication-wrapper authentication-cover auth-main position-relative">
        <!-- Background Image -->
        <img src="{{ Vite::asset('resources/assets/img/illustrations/nea-login-front.png') }}"
             alt="Background Image"
             class="w-100 h-100 position-absolute inset-0 login-bg object-fit-cover">

        <!-- Arrow / Decorative Image -->
        <div class="arrow-bg d-none d-lg-block">
            <img src="{{ Vite::asset('resources/assets/img/illustrations/login-bg.jpg') }}"
                 alt="Background Image"
                 class="arrow-img w-100">
        </div>
        <div class="auth-content border p-4 rounded-2 bg-white mt-12">
            <div class="auth-form">
                <div class="auth-header text-center mb-4">
                    @include('components.project-title')

                    <p class="mb-6 text-center card-header text-dark fw-normal">
                        {{ __('field.Please sign-in to your account and start the adventure') }}
                    </p>
                </div>
                <form id="formAuthentication" class="mb-6" action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <x-forms.input name="login" :value="null" type="text" required autofocus />
                    </div>
                    <div class="mb-4 form-password-toggle">
                        <x-forms.input-password name="password" :value="null" required autofocus />
                    </div>
                    <div class="mb-8">
                        <div class="d-flex justify-content-between">
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="checkbox" id="remember-me">
                                <label class="form-check-label" for="remember-me">
                                    {{ __('field.remember_me') }}
                                </label>
                            </div>
                            <a href="{{ route('password.request') }}">
                                <span>{{ __('field.forgot_password') }}</span>
                            </a>
                        </div>
                    </div>
                    <button class="button-primary-solid w-100" type="submit">{{ __('button.login') }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
