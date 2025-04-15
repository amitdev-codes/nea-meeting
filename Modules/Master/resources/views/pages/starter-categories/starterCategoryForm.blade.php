@extends('pages.modal.modalForm')
@section('form-fields')
<div class="mb-3 col-md-4">
    <x-forms.input name="code" :label="__('caste.code')" :value="old('code', $model->code ?? '')" />
</div>
<div class="mb-3 col-md-4">
    <x-forms.input name="name" :label="__('caste.name')" :value="old('name', $model->name ?? '')" />
</div>
<div class="mb-3 col-md-4">
    <x-forms.input name="name_np" :label="__('caste.name_np')" :value="old('name_np', $model->name_np ?? '')" />
</div>
    <div class="col-md-4 mt-6">
        <x-forms.input-switch name="status" label="status" :value="old('status', $model->status ?? 1)" />
    </div>
@endsection