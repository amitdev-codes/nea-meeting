<?php

namespace Modules\Master\DataTables;

use App\Traits\CommonDataTableFunctions;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\Master\Models\LengthUnit;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class LengthUnitsDataTable extends DataTable
{
    use CommonDataTableFunctions;

    protected array $searchableColumns = ['code','name'];

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
        ->eloquent($query)
        ->addColumn('checkbox', fn ($lengthUnit) => $this->renderCheckbox('lengthUnit_ids[]', $lengthUnit->id))
        ->addColumn('name', function ($lengthUnit) {
            return $lengthUnit->name . ' (' . ($lengthUnit->name_np ?? 'N/A') . ')';
        })
        ->addColumn('status', fn ($row) => $this->getStatusBadge($row->status))
        ->addColumn('action', $this->addActionColumn(
            'modal', // Form type
            $this->getRoutes(),
            $this->getPermissions('length-units'),
        ))
        ->rawColumns(['checkbox', 'action','status']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(LengthUnit $model): QueryBuilder
    {
        $query = $model->newQuery()
        ->select('mst_length_units.*'); 

        // Handle global search
        if (request()->has('search') && request('search')['value']) {
            $search = request('search')['value'];
            $query->where(function ($q) use ($search) {
                $q->where('mst_length_units.code', 'like', "%{$search}%")
                  ->orWhere('mst_length_units.name', 'like', "%{$search}%")
                  ->orWhere('mst_length_units.name_np', 'like', "%{$search}%");
            });
        }
    
        // Handle individual column searches
        if (request()->has('columns')) {
            foreach (request('columns') as $i => $column) {
                if (isset($column['search']['value']) && $column['search']['value'] !== '') {
                    $value = $column['search']['value'];
                    if ($column['data'] === 'code') {
                        $query->where('mst_length_units.code', 'like', "%{$value}%");
                    }
                    if ($column['data'] === 'name') {
                        $query->where(function ($q) use ($value) {
                            $q->where('mst_length_units.name', 'like', "%{$value}%")
                              ->orWhere('mst_length_units.name_np', 'like', "%{$value}%");
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
        return $this->builder()
            ->setTableId('lengthUnits-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom($this->getCommonDom())
            ->orderBy(1)
            ->buttons($this->dtActionModalButtons('length-units','LengthUnit'))
            ->parameters([
                'initComplete' => 'function() {
                    ' . $this->initBulkDeleteScript('lengthUnit_ids[]') . '
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
            Column::make('code')->title(__('field.code')),
            Column::make('name')->title(__('field.name')),
            Column::make('status')->title(__('field.status')),
            $this->actionColumn(),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'LengthUnits_'.date('YmdHis');
    }
    protected function getRoutes(): array
    {
        return [
            'create'=>'admin.length-units.create',
            'view' => 'admin.length-units.show',
            'edit' => 'admin.length-units.edit',
            'delete' => 'admin.length-units.destroy',
        ];
    }
}
