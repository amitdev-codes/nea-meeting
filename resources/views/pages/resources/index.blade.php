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
@endsection

@push('vendor-style')
    @vite('resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')
@endpush

@push('vendor-script')
    @vite('resources/js/laravel-datatables.js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
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
    <script type="module">
        $(document).on('click', '.delete-btn', function() {
            const deleteUrl = $(this).data('url'); // e.g. /livestocks/1
            const tableSelector = '#{{ $resourceName }}-table';

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
                        method: "POST", // POST is correct for _method override
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            _method: "DELETE"
                        }) // must include _method
                    })
                        .then(res => {
                            if (!res.ok) throw new Error(`HTTP error ${res.status}`);
                            return res.json();
                        })
                        .then(data => {
                            if (data.success) {
                                Swal.fire("Deleted!", data.message, "success");
                                $(tableSelector).DataTable().ajax.reload(null, false);
                            } else {
                                Swal.fire("Error!", data.message || "Something went wrong", "error");
                            }
                        })
                        .catch(err => Swal.fire("Error!", err.message, "error"));
                }
            });
        });
    </script>
@endpush
