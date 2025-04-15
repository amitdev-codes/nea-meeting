@extends('pages.modal.modalForm')
@section('form-fields')
    <div class="row">
        <div class="mb-3 col-md-4">
            <x-forms.input name="code" :label="__('lengthUnit.code')" :value="old('code', $model->code ?? '')" />
        </div>

        <div class="mb-3 col-md-4">
            <x-forms.input name="name" :label="__('lengthUnit.name')" :value="old('name', $model->name ?? '')" />
        </div>

        <div class="mb-3 col-md-4">
            <x-forms.input name="name_np" :label="__('lengthUnit.lengthUnit_name_np')" :value="old('name_np', $model->name_np ?? '')" />
        </div>
    </div>
@endsection
