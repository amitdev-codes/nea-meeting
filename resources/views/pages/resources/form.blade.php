@extends('layouts/contentNavbarLayout')
@push('vendor-style')
    @vite('resources/assets/vendor/libs/select2/select2.css')
@endpush

@push('vendor-script')
    @vite('resources/assets/vendor/libs/select2/select2.js')
@endpush
@push('page-script')
    @vite('resources/assets/js/forms-selects.js')
@endpush
@section('content')
    <x-breadcrumb :model="$modelClass" />
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">{{ $title }}</h5>
        </div>
        <div class="card-body">
            <form id="{{ $resourceName }}Form" class="mb-3"
                action="{{ isset($model) ? route('admin.' . $resourceName . '.update', $model->id) : route('admin.' . $resourceName . '.store') }}"
                method="POST">
                @csrf
                @if (isset($model))
                    @method('PUT')
                @endif
                <div class="row">
                    @yield('form-fields')
                    <div class="col-md-12 mt-5">
                        <button type="submit" class="btn btn-primary">
                            {{ isset($model) ? __('button.update') : __('button.submit') }}
                        </button>

                        @if (!isset($model))
                            <button type="button" id="saveAndAddMore" class="btn btn-danger">
                                {{ __('button.save_and_add_more') }}
                            </button>
                        @endif
                        <a href="{{ route('admin.' . $resourceName . '.index') }}" class="btn btn-outline-secondary">
                            {{ __('button.cancel') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="module">
        // Handle Save and Add More button click
        document.getElementById('saveAndAddMore')?.addEventListener('click', function() {
            const form = document.getElementById('{{ $resourceName }}Form');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'save_and_add_more';
            input.value = '1';
            form.appendChild(input);
            form.submit();
        });

        // Disable submit buttons on form submission
        document.getElementById('{{ $resourceName }}Form').addEventListener('submit', function() {
            // Disable the Submit button
            const submitButton = document.getElementById('submitButton');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="bx bx-loader bx-spin"></i> Processing...';
            }

            // Disable the Save and Add More button
            const saveAndAddMoreButton = document.getElementById('saveAndAddMore');
            if (saveAndAddMoreButton) {
                saveAndAddMoreButton.disabled = true;
                saveAndAddMoreButton.innerHTML = '<i class="bx bx-loader bx-spin"></i> Processing...';
            }
        });

        // Re-enable buttons if validation errors exist
        window.addEventListener('load', function() {
            @if ($errors->any())
                const submitButton = document.getElementById('submitButton');
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = '{{ isset($model) ? __('button.update') : __('button.submit') }}';
                }

                const saveAndAddMoreButton = document.getElementById('saveAndAddMore');
                if (saveAndAddMoreButton) {
                    saveAndAddMoreButton.disabled = false;
                    saveAndAddMoreButton.innerHTML = '{{ __('button.save_and_add_more') }}';
                }
            @endif
        });
    </script>
@endpush
