@extends('pages.resources.form')

@section('form-fields')

    <div class="mb-3 col-md-4">
        <x-forms.input name="google_calendar_id" :label="__('field.google_calendar_id')" :value="old('google_calendar_id', $model->google_calendar_id ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="client_id" :label="__('field.client_id')" :value="old('client_id', $model->client_id ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="client_secret" :label="__('field.client_secret')" :value="old('client_secret', $model->client_secret ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="redirect_uri" :label="__('field.redirect_uri')" :value="old('redirect_uri', $model->redirect_uri ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input-select2 name="auth_method" :options="\App\Enums\GoogleAuthType::toArray()" :value="old('auth_method', $model->auth_method ?? \App\Enums\GoogleAuthType::OAUTH->value)"
            :label="__('field.auth_method')" placeholder="{{ __('Select AUth Method') }}" />
    </div>


    <div class="mb-3 col-md-4">
        <x-forms.input-switch name="is_enabled" :label="__('is_enabled')" :value="old('is_enabled', $model->is_enabled ?? 0)" />
    </div>
    <div class="mb-3 col-md-12">
        <x-forms.input-textarea name="service_account_json" :label="__('field.service_account_json')" :value="old('service_account_json', $model->service_account_json ?? '')" />
    </div>

@endsection