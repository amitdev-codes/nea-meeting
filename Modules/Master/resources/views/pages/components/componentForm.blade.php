@extends('pages.modal.modalForm')
@section('form-fields')
    <div class="mb-3 col-md-6">
        <x-forms.input name="name" :label="__('component.name')" :value="old('name', $model->name ?? '')" />
    </div>

    <div class="mb-3 col-md-6">
        <x-forms.input name="name_np" :label="__('component.name_np')" :value="old('name_np', $model->name_np ?? '')" />
    </div>

    <div class="mb-3 col-md-6">
        <x-forms.input-textarea name="description" :label="__('component.name_np')" :value="old('name_np', $model->name_np ?? '')" />
    </div>

    <div class="col-md-4 mt-6">
        <x-forms.input-switch name="status" label="status" :value="old('status', $model->status?? '')" />
    </div>
@endsection