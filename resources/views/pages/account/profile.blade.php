@extends('layouts/contentNavbarLayout')

@section('content')
<x-breadcrumb :title="__('menu.profile')" :items="$breadcrumb['items']" />
<div class="container-xxl">
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-pills flex-column flex-md-row mb-3 gap-2 border-bottom">
                <li class="nav-item">
                    <a class="nav-link active" href="javascript:void(0);">
                        <i class="bx bx-user me-1 small"></i> {{ __('menu.profile') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('account.password.edit') }}">
                        <i class="bx bx-lock-open-alt small me-1"></i> {{ __('menu.change_password') }}
                    </a>
                </li>
            </ul>
            <div class="card mb-4">
                <h5 class="card-header">{{ __('label.profile_information') }}</h5>
                <!-- Account -->
                <div class="card-body">
                    <form id="formAccountSettings" action="{{ route('account.profile.update') }}" method="POST">
                        @csrf
                        @method('patch')
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <x-forms.input name="name" :value="$user->name" />
                            </div>
                            <div class="mb-3 col-md-6">
                                <x-forms.input name="email" type="email" :value="$user->email" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2">{{ __('button.submit') }}</button>
                            <button type="reset" class="btn btn-outline-secondary">{{ __('button.reset') }}</button>
                        </div>
                    </form>
                </div>
                <!-- /Account -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script type="module">
    const checkBox = $('#accountActivation');
    const accountActivationButton = $('#accountActivationButton');

    $('#password').on('keyup', function(e) {
        checkBox.attr("disabled", e.target.value.length === 0);
    });

    checkBox.on('change', function(e) {
        accountActivationButton.attr('disabled', !checkBox.prop('checked'));
    })

    console.log($('#accountActivation'));
</script>
@endpush