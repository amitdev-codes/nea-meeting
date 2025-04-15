<?php

namespace App\DataTables;

use App\Models\Slider;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Gate;
use Livewire\Livewire;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class SlidersDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', fn ($data) => view('pages.sliders.partials.action', ['data' => $data]))
            ->addColumn('image', function ($data) {
                $url = $data->getFirstMediaUrl('images', 'thumb');
                return '<img src="' . $url . '" border="0" width="50" class="img-thumbnail" align="center"/>';
            })
            ->editColumn('slider_status', function ($data) {
                if (Gate::allows('edit sliders')) {
                    return Livewire::mount('sliders.slider-status', ['slider_id'=>$data->id, 'slider_status'=>$data->slider_status]);
                } else{
                    return $data->slider_status == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>';
                }
            })
            ->setRowId('id')
            ->rawColumns(['image','slider_status']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Slider $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('sliders-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom("<'row align-items-center'<'col-md-4'l><'col-md-4 text-center'B><'col-md-4'f>>" .
                      "<'row'<'col-md-12'tr>>" .
                      "<'row'<'col-md-6'i><'col-md-6'p>>")
                    ->orderBy(0, 'asc')
                    ->rowReorder(auth()->user()->can('edit sliders') ? [
                        'dataSrc' => 'order',
                        'update' => true,
                    ] : false)
                    ->drawCallbackWithLivewire()
                    ->buttons([
                        Button::make('add')
                            ->text('<i class="bx bx-plus"></i> Add')
                            ->addClass('btn-primary')
                            ->titleAttr('Add Slider')
                            ->enabled(auth()->user()->can('create sliders')),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reload'),
                    ])
                    ->initComplete($this->headerSearchScript());
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('order')
                ->title('<i class="bx bx-move"></i>')
                ->titleAttr('Drag items to reorder')
                ->searchable(false)
                ->width(50)
                ->escapeHtml(false),

            Column::computed('image')
                ->title('Image'),

            Column::make('title')
                ->title('Title'),

            Column::make('subtitle')
                ->title('Subtitle'),

            Column::make('url_text')
                ->title('Link Text'),

            Column::make('slider_status')
                ->title('Status')
                ->searchable(false)
                ->addClass('text-center justify-content-center align-middle'),

            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center')

        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Sliders_' . date('YmdHis');
    }

    protected function headerSearchScript(): string
    {
        return <<<JS
        function () {
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
        }
    JS;
    }
}
