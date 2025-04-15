@extends('pages.modal.modalForm')
@section('form-fields')
    <div class="mb-3 col-md-4">
        <x-forms.input-select2 name="province_code" :options="$provinces
            ->map(function ($province) {
                return [$province->code, $province->name . ' (' . $province->name_np . ')'];
            })
            ->toArray()" :value="old('province_code', isset($model) ? $model->province_code ?? '' : '')"
            placeholder="{{ __('Select a province') }}" required />
    </div>

    <div class="mb-3 col-md-4">
        <x-forms.input name="code" :label="__('district.code')" :value="old('code', $model->code ?? '')" />
    </div>

    <div class="mb-3 col-md-4">
        <x-forms.input name="name" :label="__('district.name')" :value="old('name', $model->name ?? '')" />
    </div>

    <div class="mb-3 col-md-4">
        <x-forms.input name="name_np" :label="__('district.district_name_np')" :value="old('name_np', $model->name_np ?? '')" />
    </div>
@endsection
