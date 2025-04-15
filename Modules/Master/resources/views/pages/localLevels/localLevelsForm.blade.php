@extends('pages.modal.modalForm')
@section('form-fields')
    <div class="mb-3 col-md-4">
        <x-forms.input-select2 name="district_code" :options="$districts
            ->map(function ($district) {
                return [$district->code, $district->name . ' (' . $district->name_np . ')'];
            })
            ->toArray()" :value="old('district_code', isset($model) ? $model->district_code ?? '' : '')"
            placeholder="{{ __('Select a district') }}" required />
    </div>

    <div class="mb-3 col-md-4">
        <x-forms.input name="code" :label="__('field.code')" :value="old('code', $model->code ?? '')" />
    </div>

    <div class="mb-3 col-md-4">
        <x-forms.input name="name" :label="__('field.name')" :value="old('name', $model->name ?? '')" />
    </div>

    <div class="mb-3 col-md-4">
        <x-forms.input name="name_np" :label="__('field.field_name_np')" :value="old('name_np', $model->name_np ?? '')" />
    </div>
@endsection
