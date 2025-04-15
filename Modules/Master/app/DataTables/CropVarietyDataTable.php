<?php

namespace Modules\Master\DataTables;

use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Cache;
use Modules\Master\Models\CropVariety;
use Yajra\DataTables\EloquentDataTable;
use App\Traits\CommonDataTableFunctions;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class CropVarietyDataTable extends DataTable
{
    use CommonDataTableFunctions;

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    protected array $searchableColumns = ['crop','name','description','status'];
    protected array $dropdownColumns = [
        'crop' => [
            'options' => [], // Will be populated in dataTable()
            'searchBy' => 'id' // Search by caste_id
        ]
    ];
    public function __construct()
    {
        parent::__construct();
        $this->dropdownColumns['crop']['options'] = Cache::get('crops')->pluck('name', 'id')->toArray();
    }
    


    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('checkbox', fn ($row) => $this->renderCheckbox('cropVariety_ids[]', $row->id))
            ->addColumn('crop', function ($row) {
                return $row->crop->name . ' (' . ($row->crop->name_np ?? 'N/A') . ')';
            })
            ->addColumn('name', function ($row) {
                return $row->name . ' (' . ($row->name_np ?? 'N/A') . ')';
            })
            ->addColumn('status', fn ($row) => $this->getStatusBadge($row->status))
            ->addColumn('action', $this->addActionColumn(
                'modal', // Form type
                $this->getRoutes(),
                $this->getPermissions('crop-varieties')
            ))
            ->rawColumns(['checkbox', 'action','status','name','crop']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @param \Modules\Master\Models\CropVariety $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(CropVariety $model): QueryBuilder
    {
        $dropdownFields = [
            'crop' => 'crop_id'
        ];
        
        $query = $model->newQuery()
        ->select('crop_varieties.*') 
        ->leftJoin('crops', 'crops.id', '=', 'crop_varieties.crop_id');

        // Handle global search
        if (request()->has('search') && request('search')['value']) {
            $search = request('search')['value'];
            $query->where(function ($q) use ($search) {
                foreach ($this->searchableColumns as $column) {
                    $q->orWhere($column, 'like', "%{$search}%");
                }
            });
        }

        // Handle individual column searches
        if (request()->has('columns')) {
            foreach (request('columns') as $i => $column) {
                


                if (isset($column['search']['value']) && $column['search']['value'] !== '') {
                    $value = $column['search']['value'];
                    if ($column['data'] === 'description') {
                        $query->where('crop_varieties.description', 'like', "%{$value}%");
                    }
                    if ($column['data'] === 'name') {
                        $query->where(function ($q) use ($value) {
                            $q->where('crop_varieties.name', 'like', "%{$value}%")
                              ->orWhere('crop_varieties.name_np', 'like', "%{$value}%");
                        });
                    }
                    if ($column['data'] === 'crop') {
                        $query->where(function ($q) use ($value) {
                            $q->where('crops.name', 'like', "%{$value}%")
                              ->orWhere('crops.name_np', 'like', "%{$value}%");
                        });
                    }
                }

            }
        }

        return $query;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        $routeName = route("admin.crop-varieties.import");
        return $this->builder()
            ->setTableId('cropVarieties-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom($this->getCommonDom())
            ->orderBy(0)
            // ->buttons($this->dtActionModalButtons('CropVariety'))
            ->buttons(
                array_merge(
                    $this->dtActionModalButtons('crop-varieties','CropVariety'),
                    $this->importButton($routeName) // Pass the route name here
                )
            )
            ->parameters([
                'initComplete' => 'function() {
                    ' . $this->initBulkDeleteScript('cropVariety_ids[]') . '
                    ' . $this->initDeleteScript() . '
                    ' . $this->initColumnSearch() . '
                    ' . $this->initStickyColumnsStyles() . ' 
                }',
            ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            $this->checkboxColumn(),
            Column::make('crop')->title(__('field.crop')),
            Column::make('name')->title(__('field.name')),
            Column::make('description')->title(__('field.description')),
            Column::make('status')->title(__('field.status')),
            // Add your columns here
            $this->actionColumn(),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'CropVariety_' . date('YmdHis');
    }

    /**
     * Get routes for action column.
     *
     * @return array
     */
    protected function getRoutes(): array
    {
        return [
            'create' => 'admin.crop-varieties.create',
            'view' => 'admin.crop-varieties.show',
            'edit' => 'admin.crop-varieties.edit',
            'delete' => 'admin.crop-varieties.destroy',
        ];
    }
}