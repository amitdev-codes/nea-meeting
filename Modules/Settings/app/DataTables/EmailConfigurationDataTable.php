<?php

namespace Modules\Settings\DataTables;

use Modules\Settings\Models\EmailConfiguration;
use App\Traits\CommonDataTableFunctions;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class EmailConfigurationDataTable extends DataTable
{
    use CommonDataTableFunctions;
    
    protected array $searchableColumns = [

    ];
    
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('checkbox', fn ($row) => $this->renderCheckbox('emailconfiguration_ids[]', $row->id))
            ->addColumn('is_active', fn ($row) => $this->getStatusBadge($row->is_active))
            ->addColumn('action', $this->addActionColumn(
                'form',
                $this->getRoutes(),
                $this->getPermissions('email-configurations')
            ))
            ->rawColumns(['checkbox', 'action','is_active']);
    }
    
    public function query(EmailConfiguration $model): QueryBuilder
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
            ->setTableId('email-configurations-table')
            ->columns($this->getColumns())
            ->dom($this->getCommonDom())
            ->orderBy(0)
            ->buttons(
                array_merge(
                    $this->dtActionButtons('email-configurations', 'EmailConfiguration'),

                )
            )
            ->parameters([
                'initComplete' => 'function() {
                    ' . $this->initBulkDeleteScript('emailconfiguration_ids[]') . '
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
            Column::make('mail_mailer')->title(__('field.mail_mailer')),
            Column::make('mail_host')->title(__('field.mail_host')),
            Column::make('mail_port')->title(__('field.mail_port')),
            Column::make('mail_username')->title(__('field.mail_username')),
            Column::make('mail_password')->title(__('field.mail_password')),
            Column::make('mail_encryption')->title(__('field.mail_encryption')),
            Column::make('mail_from_address')->title(__('field.mail_from_address')),
            Column::make('mail_from_name')->title(__('field.mail_from_name')),
            Column::make('is_active')->title(__('field.is_active'))->width('10%'),
            $this->actionColumn()
        ];
    }
    
    protected function filename(): string
    {
        return 'EmailConfiguration_' . date('YmdHis');
    }
    
    protected function getRoutes(): array
    {
        return [
            'create' => 'admin.email-configurations.create',
            'view' => 'admin.email-configurations.show',
            'edit' => 'admin.email-configurations.edit',
            'delete' => 'admin.email-configurations.destroy',
        ];
    }
}