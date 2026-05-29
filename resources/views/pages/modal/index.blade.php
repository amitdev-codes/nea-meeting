@extends('layouts/contentNavbarLayout')
@section('content')
    <x-breadcrumb :model="$modelClass" />
    <div class="card">
        <div class="card-header">
            {{ $title }}
        </div>
        <div class="card-body p-0">
            {!! $dataTable->table([
                'class' => 'table table-striped table-hover table-sm w-100', // compact
                'style' => 'font-size: 0.85rem;', // optional tighter font
            ]) !!}
        </div>
    </div>

    <x-modal id="{{ $resourceName }}Modal" />
@endsection
@push('vendor-script')
    @vite('resources/js/laravel-datatables.js')
@endpush
@push('vendor-style')
    @vite('resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')
@endpush


@push('page-style')
    <style>
        /* Make table even more compact for Sneat */
        /* ===== DataTable Header ===== */
        table.dataTable thead th {
            padding: 0.55rem 0.6rem;
            /* slightly taller than body */
            font-size: 0.8rem;
            font-weight: 600;
            vertical-align: middle;
            border-bottom: 1px solid #dee2e6;
            white-space: nowrap;
        }

        /* ===== DataTable Body ===== */
        table.dataTable tbody td {
            padding: 0.3rem 0.5rem;
            /* compact body */
            font-size: 0.78rem;
            vertical-align: middle;
        }

        /* Compact row height */
        table.dataTable tbody tr {
            height: 26px;
        }

        .dataTables_wrapper .row {
            margin: 0.25rem 0;
        }
    </style>
@endpush
@push('page-script')
    @vite('resources/js/app.js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}

    <script type="module">
        // $(document).ready(function() {
        //     const resourceName = '{{ $resourceName }}';
        //     // #get routes from backend
        //     const routes = @json($routes);
        //     const tableSelector = `#${resourceName}-table`;

        //     // Open Create Modal
        //     $(document).on('click', '.add-btn', function() {
        //         AjaxRequestHandler.create(
        //             routes.create,
        //             `#${resourceName}Modal`,
        //             `{{ __('field.create', ['resource' => __('field.' . $resourceName)]) }}`
        //         );
        //     });

        //     // Open Edit Modal
        //     $(document).on('click', '.edit-btn', function() {
        //         const modelId = $(this).data('id');
        //         const editUrl = routes.edit.replace(':id', modelId);

        //         AjaxRequestHandler.edit(
        //             editUrl,
        //             `#${resourceName}Modal`,
        //             `{{ __('field.edit', ['resource' => __('field.' . $resourceName)]) }}`
        //         );
        //     });
        //     // Handle Form Submission
        //     $(document).on('submit', `#${resourceName}Form`, function(e) {
        //         e.preventDefault();
        //         AjaxRequestHandler.storeOrUpdate(
        //             `#${resourceName}Form`,
        //             `#${resourceName}Modal`,
        //             tableSelector
        //         );
        //     });
        //     // Delete Record
        //     $(document).on('click', '.delete-btn', function() {
        //         const modelId = $(this).data('id');
        //         const deleteUrl = routes.destroy.replace(':id', modelId);
        //         AjaxRequestHandler.destroy(
        //             deleteUrl,
        //             `#${resourceName}-table`
        //         );
        //     });
        // });

        $(document).ready(function() {
            const resourceName = '{{ $resourceName }}';
            const tableSelector = `#${resourceName}-table`;
            const routes = @json($routes);

            // CREATE
            $(document).on('click', '.add-btn', function() {
                AjaxRequestHandler.create(
                    routes.create,
                    `#${resourceName}Modal`,
                    `{{ __('field.create', ['resource' => __('field.' . $resourceName)]) }}`
                );
            });

            // VIEW
            $(document).on('click', '.view-btn', function() {
                const viewUrl = $(this).data('url');
                AjaxRequestHandler.edit(
                    viewUrl,
                    `#${resourceName}Modal`,
                    `{{ __('field.view', ['resource' => __('field.' . $resourceName)]) }}`
                );
            });

            // EDIT
            $(document).on('click', '.edit-btn', function() {
                const editUrl = $(this).data('url');
                AjaxRequestHandler.edit(
                    editUrl,
                    `#${resourceName}Modal`,
                    `{{ __('field.edit', ['resource' => __('field.' . $resourceName)]) }}`
                );
            });

            // DELETE with SweetAlert
            $(document).on('click', '.delete-btn', function() {
                const deleteUrl = $(this).data('url');

                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(deleteUrl, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "Accept": "application/json",
                                "X-CSRF-TOKEN": document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                _method: "DELETE"
                            })
                        })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire("Deleted!", data.message, "success");
                                    $(tableSelector).DataTable().ajax.reload(null, false);
                                } else {
                                    Swal.fire("Error!", "Something went wrong", "error");
                                }
                            })
                            .catch(err => Swal.fire("Error!", err.message, "error"));
                    }
                });
            });

            // Handle form submission
            $(document).on('submit', `#${resourceName}Form`, function(e) {
                e.preventDefault();
                AjaxRequestHandler.storeOrUpdate(
                    `#${resourceName}Form`,
                    `#${resourceName}Modal`,
                    tableSelector
                );
            });
        });
    </script>
@endpush
