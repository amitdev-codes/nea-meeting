@extends('layouts/commonMaster')

@section('layoutContent')
    <div class="authentication-wrapper authentication-cover">
        <div class="authentication-inner row m-0">
            <div class="d-none d-lg-flex col-lg-6 col-xl-6 align-items-center p-5">
                <div class="w-100 d-flex justify-content-center">
                    <img src="{{ asset('assets/img/illustrations/boy-with-rocket-light.png') }}" class="img-fluid"
                        alt="Login image" width="700" data-app-dark-img="illustrations/boy-with-rocket-dark.png"
                        data-app-light-img="illustrations/boy-with-rocket-light.png">
                </div>
            </div>
            <!-- Login -->
            <div class="d-flex col-12 col-lg-6 col-xl-6 align-items-center authentication-bg p-sm-12 p-6"
            style="background: white; height: 100vh;">
                <div class="w-px-400 mx-auto mt-12 pt-5">
                    <!-- Logo -->
                    @include('components.project-title')
                    <!-- Forgot Password -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-2">{{ __('label.reset_password') }} 🔒</h4>
                            <p class="mb-4">{{ __('label.please_enter_your_new_password') }}</p>

                            <form id="formAuthentication" class="mb-3" action="{{ route('password.store') }}"
                                method="POST">
                                @csrf
                                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                                <div class="mb-3">
                                    <x-forms.input name="email" :value="$request->email" type="email" />
                                </div>
                                <div class="mb-3">
                                    <x-forms.input-password name="password" />
                                </div>
                                <div class="mb-3">
                                    <x-forms.input-password name="password_confirmation" />
                                </div>

                                <button class="btn mt-4 btn-primary d-grid w-100"
                                    type="submit">{{ __('button.reset') }}</button>
                            </form>
                        </div>
                    </div>
                    <!-- /Forgot Password -->
                </div>
            </div>
        </div>
    </div>
@endsection
