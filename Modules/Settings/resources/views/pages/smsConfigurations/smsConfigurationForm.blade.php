@extends('pages.resources.form')
@section('form-fields')
    <div class="mb-3 col-md-3">
        <x-forms.input-select2 name="sms_provider_id" :options="$smsProviders
            ->map(function ($smsProvider) {
                return [$smsProvider->id, $smsProvider->name];
            })
            ->toArray()" :value="old('sms_provider_id', isset($model) ? $model->sms_provider_id ?? '' : '')"
            placeholder="{{ __('Select Sms Provider') }}" required />
    </div>

    <div class="mb-3 col-md-3">
        <x-forms.input 
            name="sender_id" 
            :label="__('field.sender_id')" 
            :value="old('sender_id', $model->sender_id ?? '')" 
            :helper_text="'The sender name/number that will appear on recipient\'s device'" 
        />
    </div>
    <div class="mb-3 col-md-3">
        <x-forms.input name="base_url" :label="__('field.base_url')" :value="old('base_url', $model->base_url ?? '')" />
    </div>
    <div class="mb-3 col-md-3">
        <x-forms.input name="api_token" :label="__('field.api_token')" :value="old('api_token', $model->api_token ?? '')" />
    </div>


    <div class="mb-3 col-md-3">
        <x-forms.input name="username" :label="__('field.username')" :value="old('username', $model->username ?? '')" />
    </div>
    <div class="mb-3 col-md-3">
        <x-forms.input name="password" :label="__('field.password')" :value="old('password', $model->password ?? '')" />
    </div>
    <div class="mb-3 col-md-3">
        <x-forms.input name="api_key" :label="__('field.api_key')" :value="old('api_key', $model->api_key ?? '')" />
    </div>
    <div class="mb-3 col-md-3">
        <x-forms.input name="api_secret" :label="__('field.api_secret')" :value="old('api_secret', $model->api_secret ?? '')" />
    </div>
    <div class="col-md-4 mt-6">
        <x-forms.input-switch name="is_active" label="is_active" :value="old('is_active', $model->is_active ?? 1)" />
    </div>
@endsection
