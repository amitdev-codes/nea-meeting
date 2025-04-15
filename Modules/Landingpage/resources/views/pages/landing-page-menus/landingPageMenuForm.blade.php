@extends('pages.modal.modalForm')
@section('form-fields')
    <div class="mb-3 col-md-4">
        <x-forms.input name="name" :label="__('field.name')" :value="old('name', $model->name ?? '')" />
    </div>
    <div class="col-md-4 mt-6">
        <x-forms.input-switch name="is_active" label="is_active" :value="old('is_active', $model->is_active ?? 1)" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="parent_id" :label="__('field.parent_id')" :value="old('parent_id', $model->parent_id ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="icon" :label="__('field.icon')" :value="old('icon', $model->icon ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="url" :label="__('field.url')" :value="old('url', $model->url ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="order_id" :label="__('field.order_id')" :value="old('order_id', $model->order_id ?? '')" />
    </div>
@endsection