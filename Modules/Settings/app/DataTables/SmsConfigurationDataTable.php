<?php

namespace Modules\Settings\DataTables;

use Modules\Settings\Models\SmsConfiguration;
use App\Traits\CommonDataTableFunctions;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SmsConfigurationDataTable extends DataTable
{
    use CommonDataTableFunctions;
    
    protected array $searchableColumns = [

    ];
    
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('checkbox', fn ($row) => $this->renderCheckbox('smsconfiguration_ids[]', $row->id))
            ->addColumn('is_active', fn ($row) => $this->getStatusBadge($row->is_active))
            ->addColumn('sms_provider', function ($row) {
                return $row->provider->name;
            })
            ->addColumn('action', $this->addActionColumn(
                'form',
                $this->getRoutes(),
                $this->getPermissions('sms-configurations')
            ))
            ->rawColumns(['checkbox', 'action','is_active']);
    }
    
    public function query(SmsConfiguration $model): QueryBuilder
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
        return $this->builder()
            ->setTableId('sms-configurations-table')
            ->columns($this->getColumns())
            ->dom($this->getCommonDom())
            ->orderBy(0)
            ->buttons(
                array_merge(
                    $this->dtActionButtons('sms-configurations', 'SmsConfiguration'),
                )
            )
            ->parameters([
                'initComplete' => 'function() {
                    ' . $this->initBulkDeleteScript('smsconfiguration_ids[]') . '
                    ' . $this->initDeleteScript() . '
                    ' . $this->initColumnSearch() . '
                    ' . $this->initStickyColumnsStyles() . ' 
                }',
                'headerCallback' => 'function(thead) {
                    $(thead).find("th").css({
                        "font-weight": "800",
                        "font-size": "0.85rem",
                        "text-transform": "uppercase",
                        "letter-spacing": "0.5px"
                    });
                }',
                'columnDefs' => [
                    [
                        'targets' => '_all', // Applies to all columns
                        'className' => 'dt-head-nowrap' // Prevents text wrapping in headers
                    ]
                ]
            ]);
    }
    
    public function getColumns(): array
    {
        return [
            $this->checkboxColumn(),
            Column::make('sms_provider')->title(__('field.sms_provider')),
            Column::make('api_token')->title(__('field.api_token')),
            Column::make('api_secret')->title(__('field.api_secret')),
            Column::make('base_url')->title(__('field.base_url')),
            Column::make('username')->title(__('field.username')),
            Column::make('password')->title(__('field.password')),
            Column::make('is_active')->title(__('field.is_active')),
            $this->actionColumn()
        ];
    }
    
    protected function filename(): string
    {
        return 'SmsConfiguration_' . date('YmdHis');
    }
    
    protected function getRoutes(): array
    {
        return [
            'create' => 'admin.sms-configurations.create',
            'view' => 'admin.sms-configurations.show',
            'edit' => 'admin.sms-configurations.edit',
            'delete' => 'admin.sms-configurations.destroy',
        ];
    }
}