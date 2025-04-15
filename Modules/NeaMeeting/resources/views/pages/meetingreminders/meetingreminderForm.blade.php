@extends('pages.modal.modalForm')
@section('form-fields')
    <div class="mb-3 col-md-4">
        <x-forms.input name="meeting_id" :label="__('field.meeting_id')" :value="old('meeting_id', $model->meeting_id ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="user_id" :label="__('field.user_id')" :value="old('user_id', $model->user_id ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="reminder_time" :label="__('field.reminder_time')" :value="old('reminder_time', $model->reminder_time ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="sent" :label="__('field.sent')" :value="old('sent', $model->sent ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="sent_at" :label="__('field.sent_at')" :value="old('sent_at', $model->sent_at ?? '')" />
    </div>
@endsection