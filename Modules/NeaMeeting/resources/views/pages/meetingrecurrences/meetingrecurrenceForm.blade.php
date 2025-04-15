@extends('pages.modal.modalForm')
@section('form-fields')
        <div class="mb-3 col-md-4">
        <x-forms.input name="name" :label="__('field.name')" :value="old('name', $model->name ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="code" :label="__('field.code')" :value="old('code', $model->code ?? '')" />
    </div>
    <div class="col-md-4 mt-6">
        <x-forms.input-switch name="status" label="status" :value="old('status', $model->status ?? 1)" />
    </div>
@endsection