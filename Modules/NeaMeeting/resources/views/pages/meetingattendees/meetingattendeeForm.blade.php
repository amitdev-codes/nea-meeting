@extends('pages.modal.modalForm')
@section('form-fields')
    <div class="mb-3 col-md-4">
        <x-forms.input name="meeting_id" :label="__('field.meeting_id')" :value="old('meeting_id', $model->meeting_id ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="user_id" :label="__('field.user_id')" :value="old('user_id', $model->user_id ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="is_required" :label="__('field.is_required')" :value="old('is_required', $model->is_required ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="attendance_status" :label="__('field.attendance_status')" :value="old('attendance_status', $model->attendance_status ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="invitation_sent_at" :label="__('field.invitation_sent_at')" :value="old('invitation_sent_at', $model->invitation_sent_at ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="response_at" :label="__('field.response_at')" :value="old('response_at', $model->response_at ?? '')" />
    </div>
    <div class="mb-3 col-md-12">
        <x-forms.input-textarea name="notes" :label="__('field.notes')" :value="old('notes', $model->notes ?? '')" />
    </div>
@endsection