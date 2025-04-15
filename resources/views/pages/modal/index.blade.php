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
    <div class="card">
        <div class="card-header">
            {{$title}}
        </div>
        <div class="card-body table-responsive text-nowrap">
            {{ $dataTable->table() }}
        </div>
    </div>
</div>
<x-modal id="{{ $resourceName }}Modal" />
@endsection

@push('scripts')
{{ $dataTable->scripts(attributes: ['type' => 'module']) }}
<script type="module">
    $(document).ready(function() {
        const resourceName = '{{ $resourceName }}';
        // #get routes from backend
        const routes = @json($routes);
        const tableSelector = `#${resourceName}-table`;
        // Open Create Modal
        $(document).on('click', '.add-btn', function() {
            AjaxRequestHandler.create(
                routes.create,
                `#${resourceName}Modal`,
                `{{ __('field.create', ['resource' => __('field.resource.' . $resourceName)]) }}`
            );
        });
        // Open Edit Modal
        $(document).on('click', '.edit-btn', function() {
            const modelId = $(this).data('id');
            const editUrl = routes.edit.replace(':id', modelId);

            AjaxRequestHandler.edit(
                editUrl,
                `#${resourceName}Modal`,
                `{{ __('field.edit', ['resource' => __('field.resource.' . $resourceName)]) }}`
            );
        });
        // Handle Form Submission
        $(document).on('submit', `#${resourceName}Form`, function(e) {
            e.preventDefault();
            AjaxRequestHandler.storeOrUpdate(
                `#${resourceName}Form`,
                `#${resourceName}Modal`,
                tableSelector
            );
        });
        // Delete Record
        $(document).on('click', '.delete-btn', function() {
            const modelId = $(this).data('id');
            const deleteUrl = routes.destroy.replace(':id', modelId);
            AjaxRequestHandler.destroy(
                deleteUrl,
                `#${resourceName}-table`
            );
        });
    });
</script>
@endpush