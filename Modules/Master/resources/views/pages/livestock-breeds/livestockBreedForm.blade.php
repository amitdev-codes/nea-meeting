@extends('pages.modal.modalForm')
@section('form-fields')
<div class="mb-3 col-md-4">
    <x-forms.input-select2 name="breed_id" :options="$breeds
        ->map(function ($breed) {
            return [$breed->code, $breed->name . ' (' . $breed->name_np . ')'];
        })
        ->toArray()" :value="old('breed_id', isset($model) ? $model->breed_id ?? '' : '')"
        placeholder="{{ __('Select a Breed') }}" required />
</div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="name" :label="__('field.name')" :value="old('name', $model->name ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="name_np" :label="__('field.name_np')" :value="old('name_np', $model->name_np ?? '')" />
    </div>
    <div class="col-md-4 mt-6">
        <x-forms.input-switch name="status" label="status" :value="old('status', $model->status ?? 1)" />
    </div>

    <div class="mb-3 col-md-12">
        <x-forms.input-textarea name="description" :label="__('field.description')" :value="old('description', $model->description ?? '')" />
    </div>
@endsection
