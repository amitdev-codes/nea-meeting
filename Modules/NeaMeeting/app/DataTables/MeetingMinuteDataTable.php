<?php

namespace Modules\NeaMeeting\DataTables;

use Modules\NeaMeeting\Models\MeetingMinute;
use App\Traits\CommonDataTableFunctions;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MeetingMinuteDataTable extends DataTable
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
            ->addColumn('checkbox', fn ($row) => $this->renderCheckbox('meetingminutes[]', $row->id))
            ->addColumn('status', fn ($row) => $this->getStatusBadge($row->status))
            ->addColumn('action', $this->addActionColumn(
                'modal',
                $this->getRoutes(),
                $this->getPermissions('meetingminutes')
            ))
            ->rawColumns(['checkbox', 'action','status']);
    }
    
    public function query(MeetingMinute $model): QueryBuilder
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
        $routeName = route("admin.meetingminutes.import");
        return $this->builder()
            ->setTableId('meetingminutes-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom($this->getCommonDom())
            ->orderBy(0)
            ->buttons(
                array_merge(
                    $this->dtActionModalButtons('meetingminutes', 'MeetingMinute'),
                    $this->importButton($routeName)
                )
            )
            ->parameters([
                'initComplete' => 'function() {
                    ' . $this->initBulkDeleteScript('meetingminutes[]') . '
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
            Column::make('meeting_id')->title(__('meeting_id')),
            Column::make('content')->title(__('content')),
            Column::make('recorded_by')->title(__('recorded_by')),
            Column::make('approved')->title(__('approved')),
            $this->actionColumn()
        ];
    }
    
    protected function filename(): string
    {
        return 'MeetingMinute_' . date('YmdHis');
    }
    
    protected function getRoutes(): array
    {
        return [
            'create' => 'admin.meetingminutes.create',
            'view' => 'admin.meetingminutes.show',
            'edit' => 'admin.meetingminutes.edit',
            'delete' => 'admin.meetingminutes.destroy',
        ];
    }
}