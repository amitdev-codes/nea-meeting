<?php

namespace Modules\Master\DataTables;

use Yajra\DataTables\Html\Column;
use Modules\Master\Models\District;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use App\Traits\CommonDataTableFunctions;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class DistrictsDataTable extends DataTable
{
    use CommonDataTableFunctions;

    /**
     * Build the DataTable class.
     * 
     *
     * @param  QueryBuilder  $query  Results from query() method.
     */

    protected array $searchableColumns = ['code','district', 'province'];

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('checkbox', fn ($district) => $this->renderCheckbox('district_ids[]', $district->id))
            ->addColumn('province', function ($district) {
                return $district->province->name . ' (' . ($district->province->name_np ?? 'N/A') . ')';
            })
            ->addColumn('district', function ($district) {
                return $district->name . ' (' . ($district->name_np ?? 'N/A') . ')';
            })
            ->addColumn('status', fn ($row) => $this->getStatusBadge($row->status))
            ->addColumn('action', $this->addActionColumn(
                'modal', // Form type
                $this->getRoutes(),
                $this->getPermissions('districts'),
            ))
            ->rawColumns(['checkbox', 'action','status']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(District $model): QueryBuilder
    {
        $query = $model->newQuery()
        ->select('mst_districts.*') // Select district fields to avoid ambiguity
        ->leftJoin('mst_provinces', 'mst_districts.province_code', '=', 'mst_provinces.code'); // Join provinces table
        // Handle global search
        if (request()->has('search') && request('search')['value']) {
            $search = request('search')['value'];
            $query->where(function ($q) use ($search) {
                $q->where('mst_districts.code', 'like', "%{$search}%")
                  ->orWhere('mst_districts.name', 'like', "%{$search}%")
                  ->orWhere('mst_districts.name_np', 'like', "%{$search}%")
                  ->orWhere('mst_provinces.name', 'like', "%{$search}%")
                  ->orWhere('mst_provinces.name_np', 'like', "%{$search}%");
            });
        }
    
        // Handle individual column searches
        if (request()->has('columns')) {
            foreach (request('columns') as $i => $column) {
                if (isset($column['search']['value']) && $column['search']['value'] !== '') {
                    $value = $column['search']['value'];
                    if ($column['data'] === 'code') {
                        $query->where('mst_districts.code', 'like', "%{$value}%");
                    }
                    if ($column['data'] === 'district') {
                        $query->where(function ($q) use ($value) {
                            $q->where('mst_districts.name', 'like', "%{$value}%")
                              ->orWhere('districts.name_np', 'like', "%{$value}%");
                        });
                    }
                    if ($column['data'] === 'province') {
                        $query->where(function ($q) use ($value) {
                            $q->where('mst_provinces.name', 'like', "%{$value}%")
                              ->orWhere('mst_provinces.name_np', 'like', "%{$value}%");
                        });
                    }
                }
            }
        }

       return $query;
    }

    public function html(): HtmlBuilder
    {
        $routeName = route("admin.districts.import"); // Generate the route name dynamically

        return $this->builder()
            ->setTableId('districts-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom($this->getCommonDom())
            ->orderBy(1)
            ->buttons(
                array_merge(
                    $this->dtActionModalButtons('districts','District'),
                    $this->importButton($routeName) // Pass the route name here
                )
            )
            ->parameters([
                'initComplete' => 'function() {
                    ' . $this->initBulkDeleteScript('district_ids[]') . '
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
            Column::make('province')->title(__('field.province')),
            Column::make('code')->title(__('field.code')),
            Column::make('district')->title(__('field.name')),
            Column::make('status')->title(__('field.status')),
            $this->actionColumn(),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Districts_'.date('YmdHis');
    }
    protected function getRoutes(): array
    {
        return [
            'create'=>'admin.districts.create',
            'view' => 'admin.districts.show',
            'edit' => 'admin.districts.edit',
            'delete' => 'admin.districts.destroy',
        ];
    }

}
