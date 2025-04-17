@extends('pages.modal.modalForm')
@section('form-fields')
    <div class="col-md-4 mt-6">
        <x-forms.input-switch name="status" label="status" :value="old('status', $model->status ?? 1)" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="meeting_id" :label="__('field.meeting_id')" :value="old('meeting_id', $model->meeting_id ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="organization_id" :label="__('field.organization_id')" :value="old('organization_id', $model->organization_id ?? '')" />
    </div>
@endsection