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
                    {{ __('label.reset_password') }}
                </p>
                <!-- <span class="text-muted">
                    {{ __('label.please_enter_your_new_password') }}</span> -->
                @if (session('force_password_change'))
                <div class="alert alert-info mb-4 d-inline-block py-2 px-3 small">
                    {{ session('force_password_change') }}
                </div>
                @endif
            </div>
            <form id="formAuthentication" class="mb-3" action="{{ route('password.force-update') }}"
                method="POST">
                @csrf
                <div class="mb-3">
                    <x-forms.input-password name="current_password" :label="__('label.current_password')" />
                </div>
                <div class="mb-3">
                    <x-forms.input-password name="password" :label="__('label.new_password')" />
                </div>
                <div class="mb-3">
                    <x-forms.input-password name="password_confirmation" :label="__('label.confirm_password')" />
                </div>

                <button class="button-primary-solid w-100"
                    type="submit">{{ __('button.reset') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection