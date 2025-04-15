<?php

namespace Modules\Master\DataTables;

use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Modules\Master\Models\Component;
use Yajra\DataTables\EloquentDataTable;
use App\Traits\CommonDataTableFunctions;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ComponentDataTable extends DataTable
{
    use CommonDataTableFunctions;

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    protected array $searchableColumns = ['name','description']; // Modify this based on your model fields

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('checkbox', fn ($row) => $this->renderCheckbox('component_ids[]', $row->id))
            ->addColumn('name', function ($row) {
                return $row->name . ' (' . ($row->name_np ?? 'N/A') . ')';
            })
            ->addColumn('status', fn ($row) => $this->getStatusBadge($row->status))
            ->addColumn('action', $this->addActionColumn(
                'modal', 
                $this->getRoutes(),
                $this->getPermissions('components')
            ))
            ->rawColumns(['checkbox', 'action','status']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @param \Modules\Master\Models\Component $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Component $model): QueryBuilder
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
                    $columnData = $column['data'];
                    if (in_array($columnData, $this->searchableColumns)) {
                        if (array_key_exists($columnData, $dropdownFields)) {
                            $query->where($dropdownFields[$columnData], $value);
                        } else {
                            $query->where($columnData, 'like', "%{$value}%");
                        }
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
        $routeName = route("admin.components.import");
        return $this->builder()
            ->setTableId('components-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom($this->getCommonDom())
            ->orderBy(0)
            ->buttons(
                array_merge(
                    $this->dtActionModalButtons('components','Component'),
                    $this->importButton($routeName) // Pass the route name here
                )
            )
            ->parameters([
                'initComplete' => 'function() {
                    ' . $this->initBulkDeleteScript('component_ids[]') . '
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
            Column::make('description')->title(__('field.description')),
            Column::make('status')->title(__('field.status')),
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
        return 'Component_' . date('YmdHis');
    }

    /**
     * Get routes for action column.
     *
     * @return array
     */
    protected function getRoutes(): array
    {
        return [
            'create' => 'admin.components.create',
            'view' => 'admin.components.show',
            'edit' => 'admin.components.edit',
            'delete' => 'admin.components.destroy',
        ];
    }

}