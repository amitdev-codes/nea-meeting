@extends('layouts.contentNavbarLayout')

@section('content')
    <x-breadcrumb :title="$title" :items="[['label' => $title, 'route' => $routeName]]" />
    <div class="flex-grow-1">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">{{ $title }} /</span> {{ isset($model) ? 'Edit' : 'Create' }}
        </h4>

        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ isset($model) ? 'Edit' : 'Create' }} {{ $title }}</h5>
                        <a href="{{ route("admin.{$routeName}.index") }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back me-1"></i> Back
                        </a>
                    </div>
                    <div class="card-body">
                        <form id="mainForm" method="POST"
                            action="{{ isset($model) ? route("admin.{$routeName}.update", $model->id) : route("admin.{$routeName}.store") }}"
                            enctype="multipart/form-data">
                            @csrf
                            @if (isset($model))
                                @method('PUT')
                            @endif


                            @if (isset($model) && (property_exists($model, 'image') || property_exists($model, 'avatar')))
                                <div class="row mb-3">
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <div class="d-flex align-items-start align-items-sm-center gap-4">
                                                <img src="{{ isset($model) ? $model->image_url : asset('assets/img/avatars/default.png') }}"
                                                    alt="user-avatar" class="d-block rounded" height="100" width="100"
                                                    id="uploadedAvatar" />
                                                <div class="button-wrapper">
                                                    <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                                        <span class="d-none d-sm-block">Upload new photo</span>
                                                        <i class="bx bx-upload d-block d-sm-none"></i>
                                                        <input type="file" id="upload" name="image"
                                                            class="account-file-input" hidden
                                                            accept="image/png, image/jpeg" />
                                                    </label>
                                                    <button type="button"
                                                        class="btn btn-outline-secondary account-image-reset mb-4">
                                                        <i class="bx bx-reset d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Reset</span>
                                                    </button>
                                                    <p class="text-muted mb-0">Allowed JPG or PNG. Max size of 800K</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            @endif

                            @yield('form-fields')

                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i>
                                {{ isset($model) ? 'Update' : 'Save' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Image upload preview
            let uploadButton = document.querySelector('.account-file-input'),
                resetButton = document.querySelector('.account-image-reset'),
                img = document.querySelector('#uploadedAvatar');

            if (uploadButton) {
                uploadButton.onchange = () => {
                    if (uploadButton.files[0]) {
                        img.src = URL.createObjectURL(uploadButton.files[0]);
                    }
                };
            }

            if (resetButton) {
                resetButton.onclick = () => {
                    uploadButton.value = '';
                    img.src =
                        '{{ isset($model) ? $model->image_url : asset('assets/img/avatars/default.png') }}';
                };
            }

            // Form submission
            $('#mainForm').on('submit', function(e) {
                e.preventDefault();
                let form = $(this);
                let url = form.attr('action');

                $.ajax({
                    url: url,
                    type: form.attr('method'),
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            }
                        }
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        Object.keys(errors).forEach(function(key) {
                            toastr.error(errors[key][0]);
                        });
                    }
                });
            });
        });
    </script>
@endpush
