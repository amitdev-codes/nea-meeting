@extends('pages.modal.modalForm')
@section('form-fields')
    <div class="mb-3 col-md-4">
        <x-forms.input name="name" :label="__('field.name')" :value="old('name', $model->name ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="location" :label="__('field.location')" :value="old('location', $model->location ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="capacity" :label="__('field.capacity')" :value="old('capacity', $model->capacity ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="has_projector" :label="__('field.has_projector')" :value="old('has_projector', $model->has_projector ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="has_video_conference" :label="__('field.has_video_conference')" :value="old('has_video_conference', $model->has_video_conference ?? '')" />
    </div>
    <div class="mb-3 col-md-12">
        <x-forms.textarea name="notes" :label="__('field.notes')" :value="old('notes', $model->notes ?? '')" />
    </div>
@endsection