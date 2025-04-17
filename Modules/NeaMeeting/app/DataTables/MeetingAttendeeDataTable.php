<?php

namespace Modules\NeaMeeting\DataTables;

use Modules\NeaMeeting\Models\MeetingAttendee;
use App\Traits\CommonDataTableFunctions;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MeetingAttendeeDataTable extends DataTable
{
    use CommonDataTableFunctions;
    
    protected array $searchableColumns = [
        // 'name',
        // 'code'
    ];
    
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('checkbox', fn ($row) => $this->renderCheckbox('meeting-attendees_ids[]', $row->id))
            ->addColumn('meeting_name', function ($row) {
                return $row->meeting->title;
            })
            ->addColumn('username', function ($row) {
                return $row->user->username;
            })
            
            ->addColumn('status', fn ($row) => $this->getStatusBadge($row->status))
            ->addColumn('action', $this->addActionColumn(
                'form',
                $this->getRoutes(),
                $this->getPermissions('meeting-attendees')
            ))
            ->rawColumns(['checkbox', 'action','status']);
    }
    
    public function query(MeetingAttendee $model): QueryBuilder
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
            ->setTableId('meeting-attendees-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom($this->getCommonDom())
            ->orderBy(0)
            ->buttons(
                array_merge(
                    $this->dtActionButtons('meeting-attendees', 'MeetingAttendee'),
                )
            )
            ->parameters([
                'initComplete' => 'function() {
                    ' . $this->initBulkDeleteScript('meeting-attendees[]') . '
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
            Column::make('meeting_name')->title(__('field.meeting_name')),
            Column::make('username')->title(__('field.username')),
            // Column::make('is_required')->title(__('field.is_required')),
            Column::make('attendance_status')->title(__('field.attendance_status')),
            $this->actionColumn()
        ];
    }
    
    protected function filename(): string
    {
        return 'MeetingAttendee_' . date('YmdHis');
    }
    
    protected function getRoutes(): array
    {
        return [
            'create' => 'admin.meeting-attendees.create',
            'view' => 'admin.meeting-attendees.show',
            'edit' => 'admin.meeting-attendees.edit',
            'delete' => 'admin.meeting-attendees.destroy',
        ];
    }
}