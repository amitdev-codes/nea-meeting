@extends('pages.resources.form')

@section('form-fields')
    <!-- Personal Information Section (Empty or Removed) -->
    <!-- Since password is moved and no other fields remain, we can omit this section -->
    
    <!-- Fansep Information Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h7 class="card-title">{{ __('field.fansep_information') }}</h7>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Username -->
                <div class="mb-3 col-md-3">
                    <x-forms.input name="username" label="{{ __('Username') }}" 
                        :value="old('username', $model->username ?? '')" required />
                </div>

                <!-- Email -->
                <div class="mb-3 col-md-3">
                    <x-forms.input name="email" type="email" label="{{ __('Email') }}" 
                        :value="old('email', $model->email ?? '')" required />
                </div>

                <!-- Mobile No -->
                <div class="mb-3 col-md-3">
                    <x-forms.input-phone name="mobile_no" label="{{ __('Mobile No') }}" 
                        :value="old('mobile_no', $model->mobile_no ?? '')" required />
                </div>

                <!-- Role -->
                <div class="mb-3 col-md-3">
                    <x-forms.input-select2 name="rolename" label="{{ __('Role') }}" 
                        :options="$roles->map(function ($role) {
                            return [$role->name, $role->name . ' (' . $role->name_np . ')'];
                        })->toArray()" 
                        :value="old('rolename', isset($model) ? $model->roles->first()->name ?? '' : '')" 
                        placeholder="{{ __('Select a Role') }}" required />
                </div>

                <!-- Clusters (Multiple Select) -->
                <div class="mb-3 col-md-3">
                    <x-forms.input-select2 name="clusters" id="select2clusters" class="select2 form-select" 
                        :options="$clusters->map(fn($cluster) => [$cluster->id, $cluster->name . ' - ' . $cluster->name_np])->toArray()"
                        :value="old('clusters', isset($model) ? (is_array($model->clusters) ? $model->clusters : json_decode($model->clusters, true)) : [])" 
                        placeholder="{{ __('Select clusters') }}" multiple />
                </div>

                <!-- Category -->
                <div class="mb-3 col-md-3">
                    <x-forms.input-select2 name="category_id" id="category_id" label="{{ __('Category') }}"
                        :options="$categories->map(function ($category) {
                            return [$category->id, $category->name . ' (' . $category->name_np . ')'];
                        })->toArray()" 
                        :value="old('category_id', isset($model) ? $model->category_id ?? '' : '')" 
                        placeholder="{{ __('Select Category') }}" required />
                </div>

                <!-- Designation -->
                <div class="mb-3 col-md-3">
                    <x-forms.input-select2 name="designation_id" label="{{ __('Designation') }}" 
                        :options="$designations->map(function ($designation) {
                            return [$designation->id, $designation->name . ' (' . $designation->name_np . ')'];
                        })->toArray()"
                        :value="old('designation_id', isset($model) ? $model->designation_id : '')" 
                        placeholder="{{ __('Select Position') }}" required />
                </div>

                <!-- Optional: Office Email -->
                <div class="mb-3 col-md-3">
                    <x-forms.input name="office_email" type="email" label="{{ __('Office Email') }}" 
                        :value="old('office_email', $model->office_email ?? '')" />
                </div>

                <!-- Optional: Office Mobile No -->
                <div class="mb-3 col-md-3">
                    <x-forms.input-phone name="office_mobile_no" label="{{ __('Office Mobile No') }}" 
                        :value="old('office_mobile_no', $model->office_mobile_no ?? '')" />
                </div>

                <!-- Password (Moved to Last) -->
                <div class="mb-3 col-md-3">
                    <x-forms.input-password name="password" label="{{ __('Password') }}" required />
                </div>
            </div>
        </div>
    </div>
@endsection