@extends('pages.modal.modalForm')
@section('form-fields')
    <div class="mb-3 col-md-4">
        <x-forms.input name="meeting_id" :label="__('field.meeting_id')" :value="old('meeting_id', $model->meeting_id ?? '')" />
    </div>
    <div class="mb-3 col-md-12">
        <x-forms.textarea name="content" :label="__('field.content')" :value="old('content', $model->content ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="recorded_by" :label="__('field.recorded_by')" :value="old('recorded_by', $model->recorded_by ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="approved" :label="__('field.approved')" :value="old('approved', $model->approved ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="approved_by" :label="__('field.approved_by')" :value="old('approved_by', $model->approved_by ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="approved_at" :label="__('field.approved_at')" :value="old('approved_at', $model->approved_at ?? '')" />
    </div>
@endsection