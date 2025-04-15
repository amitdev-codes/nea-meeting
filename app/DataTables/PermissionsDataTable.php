<?php

namespace App\DataTables;

use App\Models\Permission;
use App\Traits\CommonDataTableFunctions;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PermissionsDataTable extends DataTable
{
    use CommonDataTableFunctions;
    protected array $searchableColumns = ['name'];
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('checkbox', fn ($permission) => $this->renderCheckbox('permissions_ids[]', $permission->id))
            ->addColumn('status', fn ($row) => $this->getStatusBadge($row->status))
            ->addColumn('action', $this->addActionColumn(
                'modal',
                $this->getRoutes(),
                $this->getPermissions('permissions'),
            ))
            ->rawColumns(['checkbox', 'action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Permission $model): QueryBuilder
    {
        $query=$model->newQuery();
        // Handle global search
        if (request()->has('search') && request('search')['value']) {
         $search = request('search')['value'];
         $query->where(function ($q) use ($search) {
             $q->where('name', 'like', "%{$search}%");
          });
         }

        // Handle individual column searches
        if (request()->has('columns')) {
            foreach (request('columns') as $i => $column) {
                if (isset($column['search']['value']) && $column['search']['value'] !== '') {
                    $value = $column['search']['value'];
                    if ($column['data'] === 'name') {
                        $query->where(function ($q) use ($value) {
                            $q->where('name', 'like', "%{$value}%");
                        });
                    }
                }
            }
        }
        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $routeName = route("admin.permissions.import"); 
        return $this->builder()
            ->setTableId('permissions-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom($this->getCommonDom())
            ->orderBy(1)
            ->buttons(
                array_merge(
                    $this->dtActionModalButtons('permissions', 'Province'),
                    $this->importButton($routeName) // Pass the route name here
                )
            )
            ->parameters([
                'initComplete' => 'function() {
                    ' . $this->initBulkDeleteScript('permissions_ids[]') . '
                    ' . $this->initDeleteScript() . '
                    ' . $this->initColumnSearch() . '
                    ' . $this->initStickyColumnsStyles() . '

                }',
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            $this->checkboxColumn(),
            Column::make('name')->title(' Name'),
            $this->actionColumn(),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Permissions_'.date('YmdHis');
    }
    protected function getRoutes(): array
    {
        return [
            'create' => 'admin.permissions.create',
            'view' => 'admin.permissions.show',
            'edit' => 'admin.permissions.edit',
            'delete' => 'admin.permissions.destroy',
        ];
    }
}
