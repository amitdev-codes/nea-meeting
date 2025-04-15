@extends('pages.modal.modalForm')
@section('form-fields')

    <div class="mb-3 col-md-6">
        <x-forms.input-select2 name="component_id" :options="$components
            ->map(function ($component) {
                return [$component->id, $component->name . ' (' . $component->name_np . ')'];
            })
            ->toArray()" :value="old('component_id', isset($model) ? $model->component_id ?? '' : '')"
            placeholder="{{ __('Select a component') }}" required />
    </div>



    <div class="mb-3 col-md-6">
        <x-forms.input name="name" :label="__('sub-components.name')" :value="old('name', $model->name ?? '')" />
    </div>

    <div class="mb-3 col-md-6">
        <x-forms.input name="name_np" :label="__('sub-components.sub-components_name_np')" :value="old('name_np', $model->name_np ?? '')" />
    </div>
    <div class="mb-3 col-md-6">
        <x-forms.input-textarea name="description" :label="__('sub-components.description')" :value="old('description', $model->description ?? '')" />
    </div>
@endsection