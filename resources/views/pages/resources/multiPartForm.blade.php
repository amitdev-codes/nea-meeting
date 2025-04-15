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
                method="POST" enctype="multipart/form-data">
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

@endpush