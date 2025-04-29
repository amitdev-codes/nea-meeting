@extends('pages.resources.form')

@section('form-fields')
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <!-- Username -->
                <div class="mb-3 col-md-3">
                    <x-forms.input name="username" label="{{ __('Username') }}" :value="old('username', $model->username ?? '')" required />
                </div>

                <!-- Email -->
                <div class="mb-3 col-md-3">
                    <x-forms.input name="email" type="email" label="{{ __('Email') }}" :value="old('email', $model->email ?? '')" required />
                </div>

                <!-- Mobile No -->
                <div class="mb-3 col-md-3">
                    <x-forms.input-phone name="mobile_no" label="{{ __('Mobile No') }}" :value="old('mobile_no', $model->mobile_no ?? '')" required />
                </div>
                <!-- Mobile No -->
                <div class="mb-3 col-md-3">
                    <x-forms.input-telephone name="phone" label="{{ __('Phone No') }}" :value="old('phone', $model->phone ?? '')"  />
                </div>

                <!-- Role -->
                <div class="mb-3 col-md-3">
                    <x-forms.input-select2 name="rolename" label="{{ __('Role') }}" :options="$roles
                        ->map(function ($role) {
                            return [$role->name, $role->name];
                        })
                        ->toArray()" :value="old('rolename', isset($model) ? $model->roles->first()->name ?? '' : '')"
                        placeholder="{{ __('Select a Role') }}" required />
                </div>


                <!-- Organization -->
                <div class="mb-3 col-md-3">
                    <x-forms.input-select2 name="organization_id" label="{{ __('Organization') }}" :options="$organizations
                        ->map(function ($organization) {
                            return [$organization->id, $organization->name . ' (' . $organization->name_np . ')'];
                        })
                        ->toArray()"
                        :value="old('organization_id', isset($model) ? $model->organization_id : '')" placeholder="{{ __('Select Office') }}" required />
                </div>

                <!-- Password (Moved to Last) -->
                <div class="mb-3 col-md-3">
                    <x-forms.input-password name="password" label="{{ __('Password') }}" required />
                </div>
            </div>
        </div>
    </div>
@endsection
