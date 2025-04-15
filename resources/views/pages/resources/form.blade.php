@extends('layouts/contentNavbarLayout')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
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
</div>
@endsection

@push('scripts')
    <script type="module">
        document.getElementById('saveAndAddMore')?.addEventListener('click', function() {
            const form = document.getElementById('{{ $resourceName }}Form');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'save_and_add_more';
            input.value = '1';
            form.appendChild(input);
            form.submit();
        });
    </script>
@endpush