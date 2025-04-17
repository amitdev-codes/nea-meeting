@extends('pages.modal.modalForm')
@section('form-fields')
    <div class="mb-3 col-md-4">
        <x-forms.input name="meeting_id" :label="__('field.meeting_id')" :value="old('meeting_id', $model->meeting_id ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="user_id" :label="__('field.user_id')" :value="old('user_id', $model->user_id ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="notified_at" :label="__('field.notified_at')" :value="old('notified_at', $model->notified_at ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="notification_type" :label="__('field.notification_type')" :value="old('notification_type', $model->notification_type ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="notification_status" :label="__('field.notification_status')" :value="old('notification_status', $model->notification_status ?? '')" />
    </div>
@endsection