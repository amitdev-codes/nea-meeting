@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="card">
        <div class="card-header">
            <h7 class="card-title">User Registration Form</h4>
        </div>
        <div class="card-body">
            <form id="stepperForm" method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <!-- Stepper Navigation -->
                <div class="bs-stepper" id="formStepper">
                    <div class="bs-stepper-header" role="tablist">
                        <div class="step" data-target="#personal-info">
                            <button type="button" class="step-trigger" role="tab">
                                <span class="bs-stepper-circle">1</span>
                                <span class="bs-stepper-label">{{ __('field.personal_information') }}</span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#address-info">
                            <button type="button" class="step-trigger" role="tab">
                                <span class="bs-stepper-circle">2</span>
                                <span class="bs-stepper-label">{{ __('field.address_information') }}</span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#fansep-info">
                            <button type="button" class="step-trigger" role="tab">
                                <span class="bs-stepper-circle">3</span>
                                <span class="bs-stepper-label">{{ __('field.fansep_information') }}</span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#contact-info">
                            <button type="button" class="step-trigger" role="tab">
                                <span class="bs-stepper-circle">4</span>
                                <span class="bs-stepper-label">{{ __('field.contact_information') }}</span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#remarks-status">
                            <button type="button" class="step-trigger" role="tab">
                                <span class="bs-stepper-circle">5</span>
                                <span class="bs-stepper-label">{{ __('field.remarks') }}</span>
                            </button>
                        </div>
                    </div>

                    <!-- Stepper Content -->
                    <div class="bs-stepper-content">
                        <!-- Step 1: Personal Information -->
                        <div id="personal-info" class="content" role="tabpanel">
                            <div class="row">
                                <div class="mb-3 col-md-3">
                                    <x-forms.input name="username" label="{{ __('Username') }}"
                                        :value="old('username', $model->username ?? '')" required />
                                </div>
                                <div class="mb-3 col-md-3">
                                    <x-forms.input-select2 name="rolename" label="{{ __('Role') }}"
                                        :options="$roles->map(function ($role) {
                                            return [$role->name, $role->name . ' (' . $role->name_np . ')'];
                                        })->toArray()"
                                        :value="old('rolename', isset($model) ? $model->roles->first()->name ?? '' : '')"
                                        placeholder="{{ __('Select a Role') }}" required />
                                </div>
                                <div class="mb-3 col-md-3">
                                    <x-forms.input-password name="password" label="{{ __('Password') }}" required />
                                </div>
                                <div class="mb-3 col-md-3">
                                    <x-forms.input-password name="password_confirmation" label="{{ __('Confirm Password') }}"
                                        required />
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary next-step">Next</button>
                        </div>

                        <!-- Step 2: Address Information -->
                        <div id="address-info" class="content" role="tabpanel">
                            <div class="row">
                                <div class="mb-3 col-md-3">
                                    <x-forms.input-select2 name="province_id" id="province_id" label="{{ __('Province') }}"
                                        :options="$provinces->map(function ($province) {
                                            return [$province->id, $province->name . ' (' . $province->name_np . ')'];
                                        })->toArray()"
                                        :value="old('province_id', isset($model) && $model->addresses->isNotEmpty() ? $model->addresses->first()->province_id : '')"
                                        placeholder="{{ __('Select Province') }}" required />
                                </div>
                                <div class="mb-3 col-md-3">
                                    <x-forms.input-select2 name="district_id" id="district_id" label="{{ __('District') }}"
                                        :options="$districts->map(function ($district) {
                                            return [$district->id, $district->name . ' (' . $district->name_np . ')'];
                                        })->toArray()"
                                        :value="old('district_id', isset($model) && $model->addresses->isNotEmpty() ? $model->addresses->first()->district_id : '')"
                                        placeholder="{{ __('Select District') }}" required />
                                </div>
                                <div class="mb-3 col-md-3">
                                    <x-forms.input-select2 name="localLevel_id" label="{{ __('Local Level') }}"
                                        :options="$local_levels->map(function ($localLevel) {
                                            return [$localLevel->id, $localLevel->name . ' (' . $localLevel->name_np . ')'];
                                        })->toArray()"
                                        :value="old('local_level_id', isset($model) && $model->addresses->isNotEmpty() ? $model->addresses->first()->localLevel_id : '')"
                                        placeholder="{{ __('Select Local Level') }}" required />
                                </div>
                                <div class="mb-3 col-md-1">
                                    <x-forms.input name="ward_no" label="{{ __('Ward No') }}"
                                        :value="old('ward_no', isset($model) && $model->addresses->isNotEmpty() ? $model->addresses->first()->ward_no : '')"
                                        required />
                                </div>
                                <div class="mb-3 col-md-2">
                                    <x-forms.input name="street_name" label="{{ __('Street Name') }}"
                                        :value="old('street_name', isset($model) && $model->addresses->isNotEmpty() ? $model->addresses->first()->street_name : '')"
                                        required />
                                </div>
                            </div>
                            <button type="button" class="btn btn-secondary prev-step">Previous</button>
                            <button type="button" class="btn btn-primary next-step">Next</button>
                        </div>

                        <!-- Step 3: Fansep Information -->
                        <div id="fansep-info" class="content" role="tabpanel">
                            <div class="row">
                                <div class="mb-3 col-md-4">
                                    <x-forms.input-select2 name="designation_id" label="{{ __('Designation') }}"
                                        :options="$designations->map(function ($designation) {
                                            return [$designation->id, $designation->name . ' (' . $designation->name_np . ')'];
                                        })->toArray()"
                                        :value="old('designation_id', isset($model) ? $model->designation_id : '')"
                                        placeholder="{{ __('Select Position') }}" required />
                                </div>
                                <div class="mb-3 col-md-4">
                                    <x-forms.input-select2 name="component_id" id="component_id" label="{{ __('Component') }}"
                                        :options="$components->map(function ($component) {
                                            return [$component->id, $component->name . ' (' . $component->name_np . ')'];
                                        })->toArray()"
                                        :value="old('component_id', isset($model) ? $model->component_id ?? '' : '')"
                                        placeholder="{{ __('Select Component') }}" required />
                                </div>
                                <div class="mb-3 col-md-4">
                                    <x-forms.input-select2 name="sub_component_id" label="{{ __('Sub Component') }}"
                                        :options="$subComponents->map(function ($subComponent) {
                                            return [$subComponent->id, $subComponent->name . ' (' . $subComponent->name_np . ')'];
                                        })->toArray()"
                                        :value="old('sub_component_id', isset($model) ? $model->sub_component_id ?? '' : '')"
                                        placeholder="{{ __('Select SubComponent') }}" required />
                                </div>
                            </div>
                            <button type="button" class="btn btn-secondary prev-step">Previous</button>
                            <button type="button" class="btn btn-primary next-step">Next</button>
                        </div>

                        <!-- Step 4: Contact Information -->
                        <div id="contact-info" class="content" role="tabpanel">
                            <div class="row">
                                <div class="mb-3 col-md-4">
                                    <x-forms.input name="email" type="email" label="{{ __('Email') }}"
                                        :value="old('email', $model->email ?? '')" required />
                                </div>
                                <div class="mb-3 col-md-4">
                                    <x-forms.input-phone name="mobile_no" label="{{ __('Mobile No') }}"
                                        :value="old('mobile_no', $model->mobile_no ?? '')" required />
                                </div>
                                <div class="mb-3 col-md-4">
                                    <x-forms.input name="phone" label="{{ __('Phone') }}"
                                        :value="old('phone', $model->phone ?? '')" />
                                </div>
                            </div>
                            <button type="button" class="btn btn-secondary prev-step">Previous</button>
                            <button type="button" class="btn btn-primary next-step">Next</button>
                        </div>

                        <!-- Step 5: Remarks & Status -->
                        <div id="remarks-status" class="content" role="tabpanel">
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <x-forms.input-textarea name="remarks" label="{{ __('Remarks') }}"
                                        :value="old('remarks', $model->remarks ?? '')" rows="3" />
                                </div>
                                <div class="col-md-4 mt-3">
                                    <x-forms.input-switch name="status" label="Status"
                                        :value="old('status', $model->status ?? 1)" />
                                </div>
                            </div>
                            <button type="button" class="btn btn-secondary prev-step">Previous</button>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="module">
        document.addEventListener('DOMContentLoaded', function () {
            const stepper = new Stepper(document.getElementById('formStepper'), {
                linear: true, // Enforces step-by-step progression
                animation: true, // Adds smooth transitions
            });

            // Next button functionality
            document.querySelectorAll('.next-step').forEach(button => {
                button.addEventListener('click', () => stepper.next());
            });

            // Previous button functionality
            document.querySelectorAll('.prev-step').forEach(button => {
                button.addEventListener('click', () => stepper.previous());
            });
        });

        const routes = {
            districts: '{{ route("get.districts") }}',
            localLevels: '{{ route("get.local-levels") }}',
            subcomponents: '{{ route("get.subcomponents") }}',
        };
        const initialValues = {
            province_id: '{{ old('province_id', isset($model) && $model->addresses->isNotEmpty() ? $model->addresses->first()->province_id : '') }}',
            district_id: '{{ old('district_id', isset($model) && $model->addresses->isNotEmpty() ? $model->addresses->first()->district_id : '') }}',
            local_level_id: '{{ old('localLevel_id', isset($model) && $model->addresses->isNotEmpty() ? $model->addresses->first()->localLevel_id : '') }}',
        };
        window.initLocationDropdowns(routes, initialValues);
        window.initFansepDropdowns(routes);
    </script>
@endpush
