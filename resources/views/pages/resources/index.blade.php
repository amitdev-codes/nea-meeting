@extends('layouts/contentNavbarLayout')
@section('content')
    <x-breadcrumb :model="$modelClass" />
    <div class="container-xxl">
        <div class="card">
            <div class="card-header">
                {{ $title }}
            </div>
            <div class="card-body table-responsive text-nowrap">
                {!! $dataTable->table() !!}
            </div>
        </div>
    </div>
@endsection
@push('vendor-style')
    @vite('resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')
@endpush

@push('vendor-script')
    @vite('resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')
@endpush

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
@push('vendor-style')
<style>
    table.dataTable thead th {
        text-transform: none !important;
    }

    .filter-row th {
        padding: 5px;
    }

    .filter-row input {
        width: 100%;
    }
</style>
@endpush
