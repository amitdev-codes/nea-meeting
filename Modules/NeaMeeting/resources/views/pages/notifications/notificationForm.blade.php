@extends('pages.modal.modalForm')
@section('form-fields')
    <div class="mb-3 col-md-4">
        <x-forms.input name="user_id" :label="__('field.user_id')" :value="old('user_id', $model->user_id ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="meeting_id" :label="__('field.meeting_id')" :value="old('meeting_id', $model->meeting_id ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="notification_type" :label="__('field.notification_type')" :value="old('notification_type', $model->notification_type ?? '')" />
    </div>
    <div class="mb-3 col-md-12">
        <x-forms.textarea name="message" :label="__('field.message')" :value="old('message', $model->message ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="is_read" :label="__('field.is_read')" :value="old('is_read', $model->is_read ?? '')" />
    </div>
@endsection