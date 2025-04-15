<?php

namespace Modules\Master\DataTables;


use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use App\Traits\CommonDataTableFunctions;
use Yajra\DataTables\Services\DataTable;
use Modules\Master\Models\LiveStockBreed;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class LiveStockBreedDataTable extends DataTable
{
    use CommonDataTableFunctions;
    
    protected array $searchableColumns = [
        'name',
        'description'
    ];
    
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('checkbox', fn ($row) => $this->renderCheckbox('livestock-breeds[]', $row->id))
            ->addColumn('breed', function ($row) {
                return $row->breed->name . ' (' . ($row->breed->name_np ?? 'N/A') . ')';
            })
            ->addColumn('name', function ($row) {
                return $row->name . ' (' . ($row->name_np ?? 'N/A') . ')';
            })
            ->addColumn('status', fn ($row) => $this->getStatusBadge($row->status))
            ->addColumn('action', $this->addActionColumn(
                'modal',
                $this->getRoutes(),
                $this->getPermissions('livestock-breeds')
            ))
            ->rawColumns(['checkbox', 'action','status']);
    }
    
    public function query(LiveStockBreed $model): QueryBuilder
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
        $routeName = route("admin.livestock-breeds.import");
        return $this->builder()
            ->setTableId('livestock-breeds-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom($this->getCommonDom())
            ->orderBy(0)
            ->buttons(
                array_merge(
                    $this->dtActionModalButtons('livestock-breeds', 'BreedCategory'),
                    $this->importButton($routeName)
                )
            )
            ->parameters([
                'initComplete' => 'function() {
                    ' . $this->initBulkDeleteScript('livestock-breeds[]') . '
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
            Column::make('breed')->title(__('field.breed')),
            Column::make('name')->title(__('field.name')),
            Column::make('description')->title(__('field.description')),
            Column::make('status')->title(__('field.status')),
            $this->actionColumn()
        ];
    }
    
    protected function filename(): string
    {
        return 'BreedCategory_' . date('YmdHis');
    }
    
    protected function getRoutes(): array
    {
        return [
            'create' => 'admin.livestock-breeds.create',
            'view' => 'admin.livestock-breeds.show',
            'edit' => 'admin.livestock-breeds.edit',
            'delete' => 'admin.livestock-breeds.destroy',
        ];
    }
}