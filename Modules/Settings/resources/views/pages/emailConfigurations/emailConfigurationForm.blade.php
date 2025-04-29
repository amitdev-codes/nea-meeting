@extends('pages.resources.form')
@section('form-fields')
    <div class="mb-3 col-md-3">
        <x-forms.input name="mail_mailer" :label="__('field.mail_mailer')" :value="old('mail_mailer', $model->mail_mailer ?? '')"  :helper_text="'smtp, sendmail, mailgun, etc'" />
    </div>
    <div class="mb-3 col-md-3">
        <x-forms.input name="mail_host" :label="__('field.mail_host')" :value="old('mail_host', $model->mail_host ?? '')" />
    </div>
    <div class="mb-3 col-md-3">
        <x-forms.input name="mail_port" type="number" :label="__('field.mail_port')" :value="old('mail_port', $model->mail_port ?? '')" />
    </div>
    <div class="mb-3 col-md-3">
        <x-forms.input-select2 name="mail_encryption" :options="[['TLSV2', 'TLS'], ['ssl', 'SSL']]" :value="old('mail_encryption', isset($model) ? $model->mail_encryption ?? '' : '')"
            placeholder="{{ __('Select Mail Encryption') }}" required />
    </div>
    <div class="mb-3 col-md-3">
        <x-forms.input name="mail_username" :label="__('field.mail_username')" :value="old('mail_username', $model->mail_username ?? '')" />
    </div>
    <div class="mb-3 col-md-3">
        <x-forms.input name="mail_password" :label="__('field.mail_password')" :value="old('mail_password', $model->mail_password ?? '')" />
    </div>
    <div class="mb-3 col-md-3">
        <x-forms.input name="mail_from_address" :label="__('field.mail_from_address')" :value="old('mail_from_address', $model->mail_from_address ?? '')" />
    </div>
    <div class="mb-3 col-md-3">
        <x-forms.input name="mail_from_name" :label="__('field.mail_from_name')" :value="old('mail_from_name', $model->mail_from_name ?? '')" />
    </div>
    <div class="col-md-4 mt-6">
        <x-forms.input-switch name="is_active" label="is_active" :value="old('is_active', $model->is_active ?? 1)" />
    </div>
@endsection
