<?php

namespace Modules\Master\DataTables;

use App\Traits\CommonDataTableFunctions;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\Master\Models\Province;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProvincesDataTable extends DataTable
{
    use CommonDataTableFunctions;

    protected array $searchableColumns = ['code', 'province'];

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('checkbox', fn ($province) => $this->renderCheckbox('province_ids[]', $province->id))
            ->addColumn('province', function ($province) {
                return $province->name . ' (' . ($province->name_np ?? 'N/A') . ')';
            })
            ->addColumn('status', fn ($row) => $this->getStatusBadge($row->status))
            ->addColumn('action', $this->addActionColumn(
                'modal', // Form type
                $this->getRoutes(),
                $this->getPermissions('provinces'),
            ))
            ->setRowId('id')
            ->rawColumns(['checkbox', 'action','status']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Province $model): QueryBuilder
    {
        $query = $model->newQuery();
               // Handle global search
               if (request()->has('search') && request('search')['value']) {
                $search = request('search')['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                      ->orWhere('name', 'like', "%{$search}%")
                      ->orWhere('name_np', 'like', "%{$search}%");
                });
            }
    
            // Handle individual column searches
            if (request()->has('columns')) {
                foreach (request('columns') as $i => $column) {
                    if (isset($column['search']['value']) && $column['search']['value'] !== '') {
                        $value = $column['search']['value'];
                        if ($column['data'] === 'code') {
                            $query->where('code', 'like', "%{$value}%");
                        }
                        if ($column['data'] === 'province') {
                            $query->where(function ($q) use ($value) {
                                $q->where('name', 'like', "%{$value}%")
                                  ->orWhere('name_np', 'like', "%{$value}%");
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
        $routeName = route("admin.provinces.import"); 
        return $this->builder()
            ->setTableId('provinces-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom($this->getCommonDom())
            ->orderBy(1)
            // ->buttons($this->dtActionModalButtons('Province'))
            ->buttons(
                array_merge(
                    $this->dtActionModalButtons('provinces','Province'),
                    $this->importButton($routeName) // Pass the route name here
                )
            )
            ->parameters([
                'initComplete' => 'function() {
                    ' . $this->initBulkDeleteScript('province_ids[]') . '
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
            Column::make('code')->title(__('master::province.code')),
            Column::make('province')->title(__('master::province.name')),
            Column::make('status')
            ->title(__('field.status'))
            ->addClass('wrap-text status-column')
            ->width('10%'),
            $this->actionColumn(),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Provinces_'.date('YmdHis');
    }
    protected function getRoutes(): array
    {
        return [
            'create'=>'admin.provinces.create',
            'view' => 'admin.provinces.show',
            'edit' => 'admin.provinces.edit',
            'delete' => 'admin.provinces.destroy',
        ];
    }
}