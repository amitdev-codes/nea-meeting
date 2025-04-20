<?php

namespace Modules\NeaMeeting\DataTables;

use Carbon\Carbon;
use App\Enums\MeetingStatus;
use Yajra\DataTables\Html\Column;
use Modules\NeaMeeting\Models\Meeting;
use Yajra\DataTables\EloquentDataTable;
use App\Traits\CommonDataTableFunctions;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

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
            ->addColumn('meeting_room', function ($row) {
                return isset($row->meetingRoom) ? $row->meetingRoom->name : null;
            })
            ->addColumn('status', function ($row) {
                // Get current date and time
                $now = Carbon::now();

                // Parse meeting date
                $meetingDate = Carbon::parse($row->meeting_date_ad);

                // Extract time from start_time and end_time
                $startTime = Carbon::parse($row->start_time)->format('H:i:s'); // Get only time (e.g., '00:00:00')
                $endTime = $row->end_time ? Carbon::parse($row->end_time)->format('H:i:s') : null;

                // Combine meeting_date_ad with time to create full DateTime
                $startDateTime = Carbon::parse($row->meeting_date_ad . ' ' . $startTime);
                $endDateTime = $endTime ? Carbon::parse($row->meeting_date_ad . ' ' . $endTime) : null;

                // Determine the status
                $calculatedStatus = $row->status; // Default to current status

                if ($row->status !== MeetingStatus::Cancelled->value) { // Respect Cancelled status
                    if ($now->lessThan($startDateTime)) {
                        $calculatedStatus = MeetingStatus::Scheduled->value;
                    } elseif ($endDateTime && $now->greaterThanOrEqualTo($startDateTime) && $now->lessThan($endDateTime)) {
                        $calculatedStatus = MeetingStatus::Ongoing->value;
                    } elseif ($endDateTime && $now->greaterThanOrEqualTo($endDateTime)) {
                        $calculatedStatus = MeetingStatus::Completed->value;
                    }
                }

                // Update the database if the status has changed
                if ($row->status !== $calculatedStatus) {
                    $row->update(['status' => $calculatedStatus]);
                }

                // Return the badge for the calculated status
                return $this->getMeetingStatusBadge($calculatedStatus);
            })
            ->addColumn('action', $this->addActionColumn(
                'form',
                $this->getRoutes(),
                $this->getPermissions('meetings')
            ))
            ->rawColumns(['checkbox', 'action', 'status']);
    }
    public function query(Meeting $model): QueryBuilder
    {
        $query = $model->newQuery();
        
        // Check if the user is authenticated and not an admin or superadmin
        if (auth()->check() && !auth()->user()->hasRole(['admin', 'superadmin'])) {
            $organizationId = auth()->user()->organization_id;
            $query->whereRaw('JSON_CONTAINS(organizations, ?)', [json_encode((string)$organizationId)]);
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
        $query->orderBy('meeting_date_ad', 'asc')->orderBy('start_time', 'asc');
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
            Column::make('title')->title(__('field.title')),
            Column::make('meeting_location')->title(__('field.meeting_location')),
            // Column::make('meeting_room')->title(__('field.meeting_room')),
            Column::make('meeting_date')->title(__('field.meeting_date')),
            Column::make('start_time')->title(__('field.start_time')),
            Column::make('end_time')->title(__('field.end_time')),
            Column::make('meeting_type')->title(__('field.meeting_type')),
            Column::make('status')->title(__('field.status')),
            $this->actionColumn('admin.meetings')
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