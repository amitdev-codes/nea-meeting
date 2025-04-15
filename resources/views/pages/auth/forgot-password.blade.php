@extends('layouts/commonMaster')

@section('layoutContent')

<div class="authentication-wrapper authentication-cover auth-main position-relative">
    <img src="{{ asset('assets/img/illustrations/login-bg.jpg') }}" alt="Background Image" class="w-100 h-100 position-absolute inset-0 login-bg">
    <div class="arrow-bg d-none d-lg-block">
        <img src="{{ asset('assets/img/illustrations/login.jpg') }}" alt="Background Image" class="arrow-img w-100">
    </div>
    <div class="auth-content border p-4 rounded-2 bg-white mt-12">
        <div class="auth-form">
            <div class="auth-header text-center mb-4">
                @include('components.project-title')

                <p class="mb-2 text-center card-header text-dark fw-bold">
                    {{ __('field.forgot_password') }}?
                </p>
                <span class="text-muted">
                    {{ __("field.forgot_message") }}</span>
            </div>
            <form id="formAuthentication" method="POST" class="mb-6" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-3">
                    <x-forms.input name="email" :value="null" type="email" />
                </div>
                <button class="button-primary-solid w-100">{{ __('button.send') }}</button>
            </form>
            <div class="text-center">
                <span class="text-muted">पासवर्ड याद छ ?</span>
                <a href="{{ route('login') }}" class="text-dark">
                    {{ __('label.login') }}
                </a>
            </div>
        </div>
    </div>
</div>

@endsection