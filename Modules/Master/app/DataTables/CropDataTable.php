<?php

namespace Modules\Master\DataTables;

use Modules\Master\Models\Crop;
use App\Traits\CommonDataTableFunctions;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CropDataTable extends DataTable
{
    use CommonDataTableFunctions;

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    protected array $searchableColumns = ['name']; // Modify this based on your model fields

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('checkbox', fn ($row) => $this->renderCheckbox('crops[]', $row->id))
            ->addColumn('name', function ($row) {
                return $row->name . ' (' . ($row->name_np ?? 'N/A') . ')';
            })
            ->addColumn('status', fn ($row) => $this->getStatusBadge($row->status))
            ->addColumn('action', $this->addActionColumn(
                'modal', 
                $this->getRoutes(),
                $this->getPermissions('crops')
            ))
            ->rawColumns(['checkbox', 'action','status']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @param \Modules\Master\Models\Crop $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Crop $model): QueryBuilder
    {
        $query = $model->newQuery();

        // Handle global search
        if (request()->has('search') && request('search')['value']) {
            $search = request('search')['value'];
            $query->where(function ($q) use ($search) {
                foreach ($this->searchableColumns as $column) {
                    $q->orWhere($column, 'like', "%{$search}%");
                }
            });
        }

        // Handle individual column searches
        if (request()->has('columns')) {
            foreach (request('columns') as $i => $column) {
                if (isset($column['search']['value']) && $column['search']['value'] !== '') {
                    $value = $column['search']['value'];
                    if (in_array($column['data'], $this->searchableColumns)) {
                        $query->where($column['data'], 'like', "%{$value}%");
                    }
                }
            }
        }

        return $query;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        $routeName = route("admin.crops.import");
        return $this->builder()
            ->setTableId('crops-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom($this->getCommonDom())
            ->orderBy(0)

            ->buttons(
                array_merge(
                    $this->dtActionModalButtons('crops','Crop'),
                    $this->importButton($routeName) // Pass the route name here
                )
            )
            ->parameters([
                'initComplete' => 'function() {
                    ' . $this->initBulkDeleteScript('crops[]') . '
                    ' . $this->initDeleteScript() . '
                    ' . $this->initColumnSearch() . '
                    ' . $this->initStickyColumnsStyles() . '
                }',
            ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            $this->checkboxColumn(),
            Column::make('name')->title(__('field.name')),
            Column::make('status')->title(__('field.status')),
            // Add your columns here
            $this->actionColumn(),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Crop_' . date('YmdHis');
    }

    /**
     * Get routes for action column.
     *
     * @return array
     */
    protected function getRoutes(): array
    {
        return [
            'create' => 'admin.crops.create',
            'view' => 'admin.crops.show',
            'edit' => 'admin.crops.edit',
            'delete' => 'admin.crops.destroy',
        ];
    }

}