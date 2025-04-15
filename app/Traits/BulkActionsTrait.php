<?php

namespace App\Traits;

use Yajra\DataTables\Html\Column;

trait BulkActionsTrait
{
    protected function setupBulkActions()
    {
        return [
            Column::computed('checkbox')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center')
                ->title('<input type="checkbox" class="form-check-input dt-checkboxes-select-all">')
                ->orderable(false)
                ->searchable(false),
        ];
    }

    protected function getBulkDeleteButton(string $model): array
    {
        return [
            'extend' => 'collection',
            'text' => '<i class="bx bx-trash"></i> Bulk Delete',
            'className' => 'btn btn-sm btn-danger disabled',
            'action' => $this->bulkDeleteScript(),
            'attr' => [
                'id' => 'bulkDeleteBtn',
                'data-bulk-delete-url' => route('admin.bulkDelete', ['model' => $model]),
            ],
        ];
    }

    protected function bulkDeleteScript(): string
    {
        return <<<'JS'
        function () {
            var selectedIds = [];
            $('.dt-checkboxes:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete ${selectedIds.length} selected items. This cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete them!',
                cancelButtonText: 'No, cancel',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: $('#bulkDeleteBtn').data('bulk-delete-url'),
                        type: 'POST',
                        data: {
                            ids: selectedIds,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire(
                                'Deleted!',
                                'Selected items have been deleted.',
                                'success'
                            );
                            $('.dataTable').DataTable().ajax.reload();
                            $('#selectAll').prop('checked', false);
                            updateBulkDeleteButton();
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                'There was an error deleting the items.',
                                'error'
                            );
                        }
                    });
                }
            });
        }
        JS;
    }

    protected function initCompleteScript(): string
    {
        return <<<'JS'

        function() {
            let api = this.api();

                $(api.table().header()).append('<tr></tr>');
                api.columns().every(function (index) {
                    var column = this;
                    var th = $('<th class="p-2"></th>');

                    // Check if the column is searchable
                    if (column.settings()[0].aoColumns[index].bSearchable) {
                        var input = $('<input>')
                            .addClass('form-control')
                            .attr('placeholder', 'Search ' + $(column.header()).text())
                            .on('keyup change clear', function () {
                                if (column.search() !== this.value) {
                                    column.search(this.value).draw();
                                }
                            });
                        th.append(input);
                    }

                    $(api.table().header()).find('tr').last().append(th); // Append <th> to the search row
                });

            // Add select all checkbox in header
            $(api.table().container()).find('thead tr:eq(0)').before(
                '<tr>' +
                '<th colspan="1" class="text-center">' +
                '<input type="checkbox" class="form-check-input select-all-checkbox" id="selectAll">' +
                '</th>' +
                '<th colspan="' + (api.columns().nodes().length - 1) + '"></th>' +
                '</tr>'
            );


            // Handle select all checkbox
            $('#selectAll').on('change', function() {
                const isChecked = $(this).prop('checked');
                $('.dt-checkboxes').prop('checked', isChecked);
                updateBulkDeleteButton();
            });

            // Handle individual checkbox changes
            $(document).on('change', '.dt-checkboxes', function() {
                updateBulkDeleteButton();

                // Update select all checkbox
                const totalCheckboxes = $('.dt-checkboxes').length;
                const selectedCheckboxes = $('.dt-checkboxes:checked').length;
                $('#selectAll').prop('checked', totalCheckboxes === selectedCheckboxes);
            });

            function updateBulkDeleteButton() {
                const selectedCount = $('.dt-checkboxes:checked').length;
                const bulkDeleteBtn = $('#bulkDeleteBtn');

                // bulkDeleteBtn.addClass('btn-sm');

                if (selectedCount > 0) {
                    bulkDeleteBtn
                        .removeClass('disabled')
                        .html(`<i class="bx bx-trash"></i> Delete Selected (${selectedCount})`);
                } else {
                    bulkDeleteBtn
                        .addClass('disabled')
                        .html('<i class="bx bx-trash"></i> Delete Selected');
                }
            }

            // Handle bulk delete button click
            $('#bulkDeleteBtn').on('click', function() {
                if ($(this).hasClass('disabled')) return;

                const selectedIds = $('.dt-checkboxes:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedIds.length === 0) {
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to delete ${selectedIds.length} selected items. This action cannot be undone.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete them!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const bulkDeleteUrl = $(this).data('bulk-delete-url');

                        $.ajax({
                            url: bulkDeleteUrl,
                            type: 'POST',
                            data: {
                                ids: selectedIds,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Deleted!',
                                    'Selected items have been deleted.',
                                    'success'
                                );

                                api.ajax.reload();
                                $('#selectAll').prop('checked', false);
                                updateBulkDeleteButton();
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    'There was an error deleting the selected items.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            // Initial button state
            updateBulkDeleteButton();
        }
        JS;
    }

    protected function drawCallbackScript(): string
    {
        return <<<'JS'
        function() {
            $('.dt-checkboxes').on('change', function() {
                const selectedCount = $('.dt-checkboxes:checked').length;
                const bulkDeleteBtn = $('#bulkDeleteBtn');

                if (selectedCount > 0) {
                    bulkDeleteBtn
                        .removeClass('disabled')
                        .addClass('btn-sm')
                        .html(`<i class="bx bx-trash"></i> Delete Selected (${selectedCount})`);
                } else {
                    bulkDeleteBtn
                        .addClass('disabled')
                        .addClass('btn-sm')
                        .html('<i class="bx bx-trash"></i> Delete Selected');
                }
            });
        }
        JS;
    }

    protected function getCheckboxColumn(): array
    {
        return [
            'checkbox' => [
                'title' => '<input type="checkbox" class="form-check-input select-all-checkbox">',
                'className' => 'text-center dt-checkboxes-cell',
                'width' => 50,
                'orderable' => false,
                'searchable' => false,
                'exportable' => false,
                'printable' => false,
            ],
        ];
    }

    protected function setupDataTableParameters(): array
    {
        return [
            'pageLength' => 25,
            'drawCallback' => $this->drawCallbackScript(),
            'order' => [[1, 'asc']],
        ];
    }
}
