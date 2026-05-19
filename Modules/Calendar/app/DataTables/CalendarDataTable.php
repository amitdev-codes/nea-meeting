<?php

namespace Modules\Calendar\DataTables;


use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use App\Traits\CommonDataTableFunctions;
use Yajra\DataTables\Services\DataTable;
use Modules\Calendar\Models\NepaliCalendar;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class CalendarDataTable extends DataTable
{
    use CommonDataTableFunctions;
    
    protected array $searchableColumns = ['bs_year', 'month'];
    protected array $dropdownColumns = [  
        'bs_year' => [
            'options' => [], // Will be populated in dataTable()
            'searchBy' => 'id' // Search by caste_id
        ],
        'month' => [
            'options' => [], // Will be populated in dataTable()
            'searchBy' => 'id' // Search by caste_id
        ],];

    public function __construct()
    {
        parent::__construct();
        // Fetch distinct years and months for dropdowns
        $this->dropdownColumns['bs_year']['options'] = NepaliCalendar::distinct()
            ->pluck('bs_year', 'bs_year')
            ->toArray();
        $this->dropdownColumns['month']['options'] = NepaliCalendar::distinct()
            ->pluck('month', 'month')
            ->toArray();
    }
        
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('checkbox', fn ($row) => $this->renderCheckbox('nepalicalendar_ids[]', $row->id))
            ->addColumn('years', function ($row) {
                return $row->bs_year;
            })
            ->addColumn('action', $this->addActionColumn(
                'modal',
                $this->getRoutes(),
                $this->getPermissions('nepali-calendars')
            ))
            ->rawColumns(['checkbox', 'action']);
    }
    
    public function query(NepaliCalendar $model): QueryBuilder
    {
        $query = $model->newQuery();
        $dropdownFields = [
            'years' => 'bs_year',
            'months' => 'month',
        ];
        
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
            ->setTableId('nepali-calendars-table')
            ->columns($this->getColumns())
            ->dom($this->getCommonDom())
            ->buttons(
                array_merge(
                    $this->dtActionModalButtons('nepali-calendars', 'NepaliCalendar'),
                )
            )
            ->parameters([
                'initComplete' => 'function() {
                    ' . $this->initBulkDeleteScript('nepalicalendar_ids[]') . '
                    ' . $this->initDeleteScript() . '
                    ' . $this->initColumnSearch() . '
                    ' . $this->initStickyColumnsStyles() . ' 
                }'
            ]);
    }
    
    public function getColumns(): array
    {
        return [
            $this->checkboxColumn(),
            Column::make('bs_year')->title(__('field.years')),
            Column::make('month')->title(__('field.month')),
            Column::make('days')->title(__('field.days')),
            Column::make('start_date')->title(__('field.start_date')),
            $this->actionColumn()
        ];
    }
    
    protected function filename(): string
    {
        return 'NepaliCalendar' . date('YmdHis');
    }
    
    protected function getRoutes(): array
    {
        return [
            'create' => 'admin.nepali-calendars.create',
            'view' => 'admin.nepali-calendars.show',
            'edit' => 'admin.nepali-calendars.edit',
            'delete' => 'admin.nepali-calendars.destroy',
        ];
    }
}