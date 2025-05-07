<?php

namespace Modules\NeaMeeting\DataTables;

use Carbon\Carbon;
use App\Enums\MeetingType;
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
        'status',
        'meeting_type',
        'title',
        'meeting_location',
        'meeting_date',
    ];

    protected array $dropdownColumns = [
        'status' => [
            'options' => [], // Populated in constructor
            'searchBy' => 'value' // Search by enum value
        ],
        'meeting_type' => [
            'options' => [], // Populated in constructor
            'searchBy' => 'value' // Search by enum value
        ],
    ];

    public function __construct()
    {
        parent::__construct();

        // Populate meeting_type dropdown
        $this->dropdownColumns['meeting_type']['options'] = array_reduce(
            MeetingType::toArray(),
            function ($carry, $item) {
                $carry[$item[0]] = $item[1]; // Map value => formatted_name
                return $carry;
            },
            []
        );

        // Populate status dropdown (assuming MeetingStatus has a similar toArray method)
        $this->dropdownColumns['status']['options'] = array_reduce(
            MeetingStatus::toArray(),
            function ($carry, $item) {
                $carry[$item[0]] = $item[1]; // Map value => formatted_name
                return $carry;
            },
            []
        );
    }

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

                // Extract time from start_time
                $startTime = Carbon::parse($row->start_time)->format('H:i:s');
                $endTime = $row->end_time ? Carbon::parse($row->end_time)->format('H:i:s') : null;

                // Combine meeting_date_ad with time
                $startDateTime = Carbon::parse($row->meeting_date_ad . ' ' . $startTime);
                $endDateTime = $endTime ? Carbon::parse($row->meeting_date_ad . ' ' . $endTime) : null;

                // Determine the status
                $calculatedStatus = $row->status;

                if ($row->status !== MeetingStatus::Cancelled->value) {
                    if ($now->toDateString() > $meetingDate->toDateString()) {
                        $calculatedStatus = MeetingStatus::Completed->value;
                    } elseif ($now->toDateString() === $meetingDate->toDateString()) {
                        if ($now->lessThan($startDateTime)) {
                            $calculatedStatus = MeetingStatus::Scheduled->value;
                        } elseif ($now->greaterThanOrEqualTo($startDateTime)) {
                            if ($endDateTime && $now->lessThan($endDateTime)) {
                                $calculatedStatus = MeetingStatus::Ongoing->value;
                            } elseif (!$endDateTime) {
                                $calculatedStatus = MeetingStatus::Ongoing->value;
                            } else {
                                $calculatedStatus = MeetingStatus::Completed->value;
                            }
                        }
                    } else {
                        $calculatedStatus = MeetingStatus::Scheduled->value;
                    }
                }

                // Update status if changed
                if ($row->status !== $calculatedStatus) {
                    $row->update(['status' => $calculatedStatus]);
                }

                return $this->getMeetingStatusBadge($calculatedStatus);
            })
            // ->addColumn('action', $this->addActionColumn(
            //     'form',
            //     $this->getRoutes(),
            //     $this->getPermissions('meetings')
            // ))
            ->addColumn('action', function ($row) {
                // Get the Closure from addActionColumn and evaluate it with the current row
                $actionClosure = $this->addActionColumn(
                    'form',
                    $this->getRoutes(),
                    $this->getPermissions('meetings')
                );
                // Call the Closure to get the HTML string
                $actionHtml = call_user_func($actionClosure, $row);
    
                // Append the notification button if the user has permission
             
                    $notifyButton = '<a href="' . route('admin.meetings.notify', ['meeting' => $row->id]) . '" class="btn btn-sm btn-outline-info notify-btn ms-1" title="Send Notification"><i class="bx bx-bell"></i></a>';
                    $actionHtml = str_replace('</div>', $notifyButton . '</div>', $actionHtml);
                
    
                return $actionHtml;
            })
            ->rawColumns(['checkbox', 'action', 'status']);
    }

    public function query(Meeting $model): QueryBuilder
    {
        $query = $model->newQuery();
        $dropdownFields = [
            'meeting_type' => 'meeting_type',
            'status' => 'status'
        ];
        $today = Carbon::today();
        $filter = request()->query('filter', 'total'); // Get the filter parameter, default to 'total'
    
        // Organization-based filtering
        if (auth()->check() && !auth()->user()->hasRole(['admin', 'superadmin'])) {
            $organizationId = auth()->user()->organization_id;
            $query->whereRaw('JSON_CONTAINS(organizations, ?)', [json_encode((string)$organizationId)]);
        }
    
        // Time-based filtering based on the filter parameter
        switch ($filter) {
            case 'yesterday':
                $query->whereDate('meeting_date_ad', $today->copy()->subDay()->format('Y-m-d'));
                break;
            case 'today':
                $query->whereDate('meeting_date_ad', $today->format('Y-m-d'));
                break;
            case 'upcoming':
                $query->whereDate('meeting_date_ad', '>', $today->format('Y-m-d'));
                break;
            case 'this_month':
                $query->whereBetween('meeting_date_ad', [
                    $today->copy()->startOfMonth(),
                    $today->copy()->endOfMonth(),
                ]);
                break;
            case 'total':
            default:
                // No date filter for total meetings
                break;
        }
    
        // Default filter: Show only Ongoing or Scheduled meetings (unless filtered otherwise)
        $isStatusFiltered = false;
    
        // Check if status filter is applied (dropdown search)
        if (request()->has('columns')) {
            foreach (request('columns') as $column) {
                if (isset($column['data']) && $column['data'] === 'status' && !empty($column['search']['value'])) {
                    $statusValue = $column['search']['value'];
                    $isStatusFiltered = true;
                    // Apply status filter from dropdown
                    $query->where('status', $statusValue);
                }
            }
        }
    
        // Global search
        if (request()->has('search') && request('search')['value']) {
            $search = request('search')['value'];
            $query->where(function ($q) use ($search) {
                foreach ($this->searchableColumns as $column) {
                    $q->orWhere($column, 'like', "%{$search}%");
                }
                // Allow Completed meetings if searched explicitly in global search
                if (stripos($search, MeetingStatus::Completed->name) !== false) {
                    $q->orWhere('status', MeetingStatus::Completed->value);
                }
            });
        }
    
        // Column-specific search (excluding status, already handled)
        if (request()->has('columns')) {
            foreach (request('columns') as $i => $column) {
                if (isset($column['search']['value']) && $column['search']['value'] !== '' && $column['data'] !== 'status') {
                    $value = $column['search']['value'];
                    $columnData = $column['data'];
                    if (in_array($columnData, $this->searchableColumns)) {
                        if (array_key_exists($columnData, $dropdownFields)) {
                            $query->where($dropdownFields[$columnData], $value);
                        } else {
                            $query->where($columnData, 'like', "%{$value}%");
                        }
                    }
                }
            }
        }
    
        // Custom status ordering: Ongoing, Scheduled, Completed, Cancelled
        $query->orderByRaw("FIELD(status, 'Ongoing', 'Scheduled', 'Completed', 'Cancelled')")
        
              ->orderBy('start_time', 'asc');
    
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
            'notify' => 'admin.meetings.notify',
        ];
    }
}