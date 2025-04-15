@extends('layouts/contentNavbarLayout')

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

@section('content')
    <x-breadcrumb :model="$modelClass" />

    <div class="container-xxl">
        <!-- DataTable Grid -->
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

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
@push('styles')
<style>
    /* DataTable header styling */
    .dataTable thead th {
        font-weight: 800 !important;
        font-size: 0.85rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        white-space: nowrap !important;
        padding-top: 0.75rem !important;
        padding-bottom: 0.75rem !important;
    }
    
    /* Prevent header text wrapping */
    .dt-head-nowrap {
        white-space: nowrap !important;
    }
    
    /* Dark mode support */
    html[data-style="dark"] .dataTable thead th {
        background-color: #2d3748 !important;
        color: #f7fafc !important;
    }
</style>
@endpush