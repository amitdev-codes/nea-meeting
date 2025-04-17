<?php

namespace Modules\NeaMeeting\DataTables;

use Modules\NeaMeeting\Models\MeetingOrganization;
use App\Traits\CommonDataTableFunctions;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MeetingOrganizationDataTable extends DataTable
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
            ->addColumn('checkbox', fn ($row) => $this->renderCheckbox('meetingorganizations[]', $row->id))
            ->addColumn('status', fn ($row) => $this->getStatusBadge($row->status))
            ->addColumn('action', $this->addActionColumn(
                'modal',
                $this->getRoutes(),
                $this->getPermissions('meetingorganizations')
            ))
            ->rawColumns(['checkbox', 'action','status']);
    }
    
    public function query(MeetingOrganization $model): QueryBuilder
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
        $routeName = route("admin.meetingorganizations.import");
        return $this->builder()
            ->setTableId('meetingorganizations-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom($this->getCommonDom())
            ->orderBy(0)
            ->buttons(
                array_merge(
                    $this->dtActionModalButtons('meetingorganizations', 'MeetingOrganization'),
                    $this->importButton($routeName)
                )
            )
            ->parameters([
                'initComplete' => 'function() {
                    ' . $this->initBulkDeleteScript('meetingorganizations[]') . '
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
            Column::make('status')->title(__('field.status')),
            Column::make('meeting_id')->title(__('meeting_id')),
            Column::make('organization_id')->title(__('organization_id')),
            $this->actionColumn()
        ];
    }
    
    protected function filename(): string
    {
        return 'MeetingOrganization_' . date('YmdHis');
    }
    
    protected function getRoutes(): array
    {
        return [
            'create' => 'admin.meetingorganizations.create',
            'view' => 'admin.meetingorganizations.show',
            'edit' => 'admin.meetingorganizations.edit',
            'delete' => 'admin.meetingorganizations.destroy',
        ];
    }
}