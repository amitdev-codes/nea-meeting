<?php

namespace Modules\NeaMeeting\DataTables;

use Modules\NeaMeeting\Models\Meeting;
use App\Traits\CommonDataTableFunctions;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MeetingDataTable extends DataTable
{
    use CommonDataTableFunctions;
    
    protected array $searchableColumns = [

    ];
    
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('checkbox', fn ($row) => $this->renderCheckbox('meeting_ids[]', $row->id))
            ->addColumn('meeting_location', function ($row) {
                return $row->meetingRoom->name;
            })

            ->addColumn('status', fn ($row) => $this->getMeetingStatusBadge($row->status))
            ->addColumn('action', $this->addActionColumn(
                'form',
                $this->getRoutes(),
                $this->getPermissions('meetings')
            ))
            ->rawColumns(['checkbox', 'action','status']);
    }
    
    public function query(Meeting $model): QueryBuilder
    {
        $query = $model->newQuery();
        
        // Check if the user is authenticated and not an admin or superadmin
        if (auth()->check() && !auth()->user()->hasRole(['admin', 'superadmin'])) {
            // Filter meetings where the authenticated user is an attendee
            $query->whereHas('attendees', function ($q) {
                $q->where('user_id', auth()->id());
            });
        }
        
        // Handle global search
        if (request()->has('search') && request('search')['value']) {
            $search = request('search')['value'];
            $query->where(function ($q) use ($search) {
                foreach ($this->searchableColumns as $column) {
                    $q->orWhere($column, 'like', "%{$search}%");
                }
            });
        }
        
        // Handle column-specific search
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
            ->setTableId('meetings-table')
            ->columns($this->getColumns())
            ->dom($this->getCommonDom())
            ->orderBy(0)
            ->buttons(
                array_merge(
                    $this->dtActionButtons('meetings', 'Meeting'),
                )
            )
            ->parameters([
                'initComplete' => 'function() {
                    ' . $this->initBulkDeleteScript('meeting_ids[]') . '
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
                        'targets' => '_all', 
                        'className' => 'dt-head-nowrap' 
                    ]
                ]
            ]);
    }
    public function getColumns(): array
    {
        return [
            $this->checkboxColumn(),
            Column::make('title')->title(__('field.title')),
            Column::make('meeting_location')->title(__('field.meeting_location')),
            Column::make('meeting_date')->title(__('field.meeting_date')),
            Column::make('start_time')->title(__('field.start_time')),
            Column::make('end_time')->title(__('field.end_time')),
            Column::make('meeting_type')->title(__('field.meeting_type')),
            Column::make('status')->title(__('field.status')),
            $this->actionColumn()
        ];
    }
    
    protected function filename(): string
    {
        return 'Meeting_' . date('YmdHis');
    }
    
    protected function getRoutes(): array
    {
        return [
            'create' => 'admin.meetings.create',
            'view' => 'admin.meetings.show',
            'edit' => 'admin.meetings.edit',
            'delete' => 'admin.meetings.destroy',
        ];
    }
}