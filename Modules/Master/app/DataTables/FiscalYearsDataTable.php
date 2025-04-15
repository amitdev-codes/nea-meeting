<?php

namespace Modules\Master\DataTables;

use App\Traits\CommonDataTableFunctions;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\Master\Models\FiscalYear;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class FiscalYearsDataTable extends DataTable
{
    use CommonDataTableFunctions;

    protected array $searchableColumns = ['code','name','date_from_bs','date_to_bs'];

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('checkbox', fn ($fiscalYear) => $this->renderCheckbox('fiscalYears_ids[]', $fiscalYear->id))
            ->addColumn('action', $this->addActionColumn(
                'form', 
                $this->getRoutes(),
                $this->getPermissions('fiscal-years'),
            ))
            ->rawColumns(['checkbox', 'action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(FiscalYear $model): QueryBuilder
    {
        $query = $model->newQuery()
        ->select('mst_fiscal_years.*'); 
        // Handle global search
        if (request()->has('search') && request('search')['value']) {
            $search = request('search')['value'];
            $query->where(function ($q) use ($search) {
                $q->where('mst_fiscal_years.code', 'like', "%{$search}%")
                  ->orWhere('mst_fiscal_years.name', 'like', "%{$search}%")
                  ->orWhere('mst_fiscal_years.name_np', 'like', "%{$search}%");
            });
        }
    
        // Handle individual column searches
        if (request()->has('columns')) {
            foreach (request('columns') as $i => $column) {
                if (isset($column['search']['value']) && $column['search']['value'] !== '') {
                    $value = $column['search']['value'];
                    if ($column['data'] === 'code') {
                        $query->where('mst_fiscal_years.code', 'like', "%{$value}%");
                    }
                    if ($column['data'] === 'name') {
                        $query->where(function ($q) use ($value) {
                            $q->where('mst_fiscal_years.name', 'like', "%{$value}%")
                              ->orWhere('mst_fiscal_years.name_np', 'like', "%{$value}%");
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
            ->setTableId('fiscalYears-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom($this->getCommonDom())
            ->orderBy(1)
            ->buttons($this->dtActionButtons('fiscal-years','FiscalYear'))
            ->parameters([
                'initComplete' => 'function() {
                    ' . $this->initBulkDeleteScript('fiscalYears_ids[]') . '
                    ' . $this->initDeleteScript() . '
                    ' . $this->initColumnSearch() . ' 
                    ' . $this->initStickyColumnsStyles() . '  // Sticky columns styles for better view in mobile devices
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
            Column::make('date_from_bs')->title(__('field.date_from_bs')),
            Column::make('date_to_bs')->title(__('field.date_to_bs')),
            Column::make('is_current')->title(__('field.is_current')),
            $this->actionColumn(),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'FiscalYears_'.date('YmdHis');
    }
    protected function getRoutes(): array
    {
        return [
            'create'=>'admin.fiscal-years.create',
            'view' => 'admin.fiscal-years.show',
            'edit' => 'admin.fiscal-years.edit',
            'delete' => 'admin.fiscal-years.destroy',
        ];
    }
}
