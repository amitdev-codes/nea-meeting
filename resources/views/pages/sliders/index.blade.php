@extends('layouts/contentNavbarLayout')

@section('content')
    <x-breadcrumb title="All Sliders" :items="[['label' => 'All Sliders', 'route' => 'admin.sliders.index']]" />

    <div class="card">
    <div class="card-header">
            Table header
        </div>
        <div class="card-body  table-responsive text-nowrap">
            {{ $dataTable->table() }}
        </div>
    </div>
@endsection

@push('script')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}

    <script type="module">
        $(document).ready(function() {
            $(document).on('click', '.delete-btn', function() {
                let modelId = $(this).data('id');
                let deleteUrl = "{{ route('admin.sliders.destroy', ':id') }}".replace(':id', modelId);
                AjaxRequestHandler.destroy(deleteUrl, '#sliders-table');
            });

            AjaxRequestHandler.datatableReorder('#sliders-table', '{{ route('admin.sliders.reorder') }}');
        });
    </script>
@endpush
