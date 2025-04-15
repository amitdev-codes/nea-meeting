<?php

namespace Modules\Master\DataTables;

use Modules\Master\Models\Infrastructure;
use App\Traits\CommonDataTableFunctions;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class InfrastructureDataTable extends DataTable
{
    use CommonDataTableFunctions;
    
    protected array $searchableColumns = [
        'name',
        'code'
    ];
    
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('checkbox', fn ($row) => $this->renderCheckbox('infrastructures[]', $row->id))
            ->addColumn('status', fn ($row) => $this->getStatusBadge($row->status))
            ->addColumn('action', $this->addActionColumn(
                'modal',
                $this->getRoutes(),
                $this->getPermissions('infrastructures')
            ))
            ->rawColumns(['checkbox', 'action','status']);
    }
    
    public function query(Infrastructure $model): QueryBuilder
    {
        $query = $model->newQuery();
        
        if (request()->has('search') && request('search')['value']) {
            $search = request('search')['value'];
            $query->where(function ($q) use ($search) {
                foreach ($this->searchableColumns as $column) {
                    $q->orWhere($column, 'like', "%{$search}%");
                }
            });
        }
        
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
    
    public function html(): HtmlBuilder
    {
        // $routeName = route("admin.infrastructures.import");
        return $this->builder()
            ->setTableId('infrastructures-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom($this->getCommonDom())
            ->orderBy(0)
            ->buttons(
                array_merge(
                    $this->dtActionModalButtons('infrastructures', 'Infrastructure'),
                    // $this->importButton($routeName)
                )
            )
            ->parameters([
                'initComplete' => 'function() {
                    ' . $this->initBulkDeleteScript('infrastructures[]') . '
                    ' . $this->initDeleteScript() . '
                    ' . $this->initColumnSearch() . '
                    ' . $this->initStickyColumnsStyles() . ' 
                }',
            ]);
    }
    
    public function getColumns(): array
    {
        return [
            $this->checkboxColumn(),
            Column::make('code')->title(__('field.code')),
            Column::make('name')->title(__('field.name')),
            Column::make('name_np')->title(__('field.name_np')),
            Column::make('status')->title(__('field.status')),
            $this->actionColumn()
        ];
    }
    
    protected function filename(): string
    {
        return 'Infrastructure_' . date('YmdHis');
    }
    
    protected function getRoutes(): array
    {
        return [
            'create' => 'admin.infrastructures.create',
            'view' => 'admin.infrastructures.show',
            'edit' => 'admin.infrastructures.edit',
            'delete' => 'admin.infrastructures.destroy',
        ];
    }
}