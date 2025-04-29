<?php

namespace Modules\GoogleCalendar\DataTables;

use Modules\GoogleCalendar\Models\GoogleCalendarSetting;
use App\Traits\CommonDataTableFunctions;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class GoogleCalendarSettingDataTable extends DataTable
{
    use CommonDataTableFunctions;
    
    protected array $searchableColumns = [

    ];
    
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('checkbox', fn ($row) => $this->renderCheckbox('googlecalendarsetting_ids[]', $row->id))
            ->addColumn('is_enabled', fn ($row) => $this->getStatusBadge($row->is_enabled))
            ->addColumn('action', $this->addActionColumn(
                'form',
                $this->getRoutes(),
                $this->getPermissions('google-calendar-settings')
            ))
            ->rawColumns(['checkbox', 'action','is_enabled']);
    }
    
    public function query(GoogleCalendarSetting $model): QueryBuilder
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
            ->setTableId('google-calendar-settings-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom($this->getCommonDom())
            ->orderBy(0)
            ->buttons(
                array_merge(
                    $this->dtActionButtons('google-calendar-settings', 'GoogleCalendarSetting'),
                )
            )
            ->parameters([
                'initComplete' => 'function() {
                    ' . $this->initBulkDeleteScript('googlecalendarsetting_ids[]') . '
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
            ]);
    }
    
    public function getColumns(): array
    {
        return [
            $this->checkboxColumn(),
            Column::make('google_calendar_id')->title(__('field.google_calendar_id')),
            Column::make('client_id')->title(__('field.client_id')),
            Column::make('client_secret')->title(__('field.client_secret')),
            Column::make('redirect_uri')->title(__('field.redirect_uri')),
            Column::make('service_account_json')->title(__('field.service_account_json')),
            Column::make('is_enabled')->title(__('is_enabled')),
            $this->actionColumn()
        ];
    }
    
    protected function filename(): string
    {
        return 'GoogleCalendarSetting_' . date('YmdHis');
    }
    
    protected function getRoutes(): array
    {
        return [
            'create' => 'admin.google-calendar-settings.create',
            'view' => 'admin.google-calendar-settings.show',
            'edit' => 'admin.google-calendar-settings.edit',
            'delete' => 'admin.google-calendar-settings.destroy',
        ];
    }
}