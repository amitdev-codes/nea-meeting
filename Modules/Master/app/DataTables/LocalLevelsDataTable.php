<?php

namespace Modules\Master\DataTables;

use App\Traits\CommonDataTableFunctions;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\Master\Models\LocalLevel;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class LocalLevelsDataTable extends DataTable
{
    use CommonDataTableFunctions;
    protected array $searchableColumns = ['code','district', 'local_level'];

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('checkbox', fn ($localLevel) => $this->renderCheckbox('localLevel_ids[]', $localLevel->id))
            ->addColumn('district', function ($localLevel) {
                return $localLevel->district->name . ' (' . ($localLevel->district->name_np ?? 'N/A') . ')';
            })
            ->addColumn('local_level', function ($localLevel) {
                return $localLevel->name . ' (' . ($localLevel->name_np ?? 'N/A') . ')';
            })
            ->addColumn('status', fn ($row) => $this->getStatusBadge($row->status))
            ->addColumn('action', $this->addActionColumn(
                'modal', // Form type
                $this->getRoutes(),
                $this->getPermissions('local-levels'),
            ))
            ->rawColumns(['checkbox', 'action','district','local_level']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(LocalLevel $model): QueryBuilder
    {
        $query = $model->newQuery()
        ->select('mst_local_levels.*') // Select district fields to avoid ambiguity
        ->leftJoin('mst_districts', 'mst_local_levels.district_code', '=', 'mst_districts.code'); // Join provinces table

        // Handle global search
        if (request()->has('search') && request('search')['value']) {
            $search = request('search')['value'];
            $query->where(function ($q) use ($search) {
                $q->where('mst_local_levels.code', 'like', "%{$search}%")
                  ->orWhere('mst_local_levels.name', 'like', "%{$search}%")
                  ->orWhere('mst_local_levels.name_np', 'like', "%{$search}%")
                  ->orWhere('mst_districts.name', 'like', "%{$search}%")
                  ->orWhere('mst_districts.name_np', 'like', "%{$search}%");
            });
        }
    
        // Handle individual column searches
        if (request()->has('columns')) {
            foreach (request('columns') as $i => $column) {
                if (isset($column['search']['value']) && $column['search']['value'] !== '') {
                    $value = $column['search']['value'];

                    if ($column['data'] === 'code') {
                        $query->where('mst_local_levels.code', 'like', "%{$value}%");
                    }
                    if ($column['data'] === 'local_level') {
                        $query->where(function ($q) use ($value) {
                            $q->where('mst_local_levels.name', 'like', "%{$value}%")
                              ->orWhere('mst_local_levels.name_np', 'like', "%{$value}%");
                        });
                    }
                    if ($column['data'] === 'district') {
                        $query->where(function ($q) use ($value) {
                            $q->where('mst_districts.name', 'like', "%{$value}%")
                              ->orWhere('mst_districts.name_np', 'like', "%{$value}%");
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
        $routeName = route("admin.local-levels.import");
        return $this->builder()
            ->setTableId('localLevels-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom($this->getCommonDom())
            ->orderBy(1)
            ->buttons(
                array_merge(
                    $this->dtActionModalButtons('local-levels','LocalLevel'),
                    $this->importButton($routeName) // Pass the route name here
                )
            )
            ->parameters([
                'initComplete' => 'function() {
                    ' . $this->initBulkDeleteScript('localLevel_ids[]') . '
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
            Column::make('district')->title(__('field.district')),
            Column::make('code')->title(__('field.code')),
            Column::make('local_level')->title(__('field.local_level')),
            Column::make('status')->title(__('field.status')),
            $this->actionColumn(),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'LocalLevels_'.date('YmdHis');
    }
    protected function getRoutes(): array
    {
        return [
            'create'=>'admin.local-levels.create',
            'view' => 'admin.local-levels.show',
            'edit' => 'admin.local-levels.edit',
            'delete' => 'admin.local-levels.destroy',
        ];
    }
}
