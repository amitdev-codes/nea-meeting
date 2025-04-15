<?php

namespace App\DataTables;

use App\Models\Contact;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ContactDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
        ->editColumn('created_at', function ($data) {
            return Carbon::parse($data['created_at'])->format('Y-m-d');
        })
        ->addColumn('action', fn ($data) => view('pages.contacts.partials.actions', [
            'data' => $data
        ]))
        ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Contact $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('contact-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom("<'row align-items-center'<'col-md-4'l><'col-md-4 text-center'B><'col-md-4'f>>" .
                      "<'row'<'col-md-12'tr>>" .
                      "<'row'<'col-md-6'i><'col-md-6'p>>")
                    ->orderBy(0 )
                    ->buttons([
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reset'),
                    ])
                    ->initComplete($this->headerSearchScript());
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('created_at')->title('Date')->searchable(false),
            Column::make('name'),
            Column::make('email'),
            Column::make('subject'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Contact_' . date('YmdHis');
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
