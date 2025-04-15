@extends('pages.resources.form')
@section('form-fields')
    <div class="mb-3 col-md-4">
        <x-forms.input name="code" :label="__('field.code')" :value="old('code', $model->code ?? '')" />
    </div>

    <div class="mb-3 col-md-4">
        <x-forms.input name="name" :label="__('field.name')" :value="old('name', $model->name ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input-select2 name="cluster_type_id" :options="$clusterTypes
            ->map(fn($clusterType) => [$clusterType->id, $clusterType->name . ' (' . $clusterType->name_np . ')'])
            ->toArray()" :value="old('cluster_type_id', $model->cluster_type_id ?? '')"
            placeholder="{{ __('Select a Cluster Type') }}" required />
    </div>

    <div class="mb-3 col-md-4">
        <x-forms.input-select2 name="provinces" id="select2Provinces" class="select2 form-select" required :options="$provinces
            ->map(fn($province) => [$province->id, $province->name . ' - ' . $province->name_np])
            ->toArray()"
            :value="old('provinces', isset($model) ? (is_array($model->provinces) ? $model->provinces : json_decode($model->provinces, true)) : [])" placeholder="{{ __('Select Provinces') }}" multiple />
    </div>


    <div class="mb-3 col-md-4">
        <x-forms.input-select2 name="districts" id="select2Districts" class="select2 form-select" required :options="$districts
            ->map(fn($district) => [$district->id, $district->name . ' - ' . $district->name_np])
            ->toArray()"
            :value="old('districts', isset($model) ? (is_array($model->districts) ? $model->districts : json_decode($model->districts, true)) : [])" placeholder="{{ __('Select Districts') }}" multiple />
    </div>

    <div class="mb-3 col-md-4">
        <x-forms.input-select2 name="local_levels" id="select2LocalLevels" class="select2 form-select" required
            :options="$local_levels
                ->map(fn($local_level) => [$local_level->id, $local_level->name . ' - ' . $local_level->name_np])
                ->toArray()" :value="old('local_levels', isset($model) ? (is_array($model->local_levels) ? $model->local_levels : json_decode($model->local_levels, true)) : [])" placeholder="{{ __('Select Local Levels') }}" multiple />
    </div>

    <div class="col-md-6 mt-6">
        <x-forms.input-textarea name="description" :label="__('field.field_description')" :value="old('description', $model->description ?? '')" />
    </div>
    <div class="col-md-6 mt-8">
        <x-forms.input-switch name="status" label="status" :value="old('status', $model->status ?? 1)" />
    </div>
@endsection
