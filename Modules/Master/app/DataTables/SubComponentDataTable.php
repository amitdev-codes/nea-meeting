<?php

namespace Modules\Master\DataTables;

use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Cache;
use Modules\Master\Models\SubComponent;
use Yajra\DataTables\EloquentDataTable;
use App\Traits\CommonDataTableFunctions;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class SubComponentDataTable extends DataTable
{
    use CommonDataTableFunctions;
    protected $tableName = 'sub_components';
    protected $searchableColumns = ['name', 'component', 'code', 'description'];
    protected $exactMatchColumns = ['status', 'component_id'];
    protected $dropdownFields = [
        'component' => 'sub_components.component_id', 
    ];
    protected $relationshipColumns = [
        'component' => [
            'table' => 'components',
            'fields' => ['name', 'name_np']
        ]
    ];
    protected array $dropdownColumns = [
        'component' => [
            'options' => [],
            'searchBy' => 'id' 
        ],
    ];

    public function __construct()
    {
        parent::__construct();
        $this->dropdownColumns['component']['options'] = Cache::get('components')->pluck('name', 'id')->toArray();
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $components = Cache::get('components')->pluck('name', 'id')->toArray();

        return datatables()
            ->eloquent($query)
            ->addColumn('checkbox', fn ($subComponent) => $this->renderCheckbox('subComponents_ids[]', $subComponent->id))
            ->addColumn('component', function ($subComponent) use ($components) {
                $componentName = $components[$subComponent->component_id] ?? 'N/A';
                return $this->renderEditableCell('component_id', $componentName, $subComponent->id, 'select', $components);
            })
            ->addColumn('name', function ($subComponent) {
                return $this->renderEditableCell('name', $subComponent->name . ' (' . ($subComponent->name_np ?? 'N/A') . ')', $subComponent->id);
            })
            ->addColumn('description', fn ($subComponent) => $this->renderEditableCell('description', $subComponent->description ?? '', $subComponent->id))
            ->addColumn('status', fn ($subComponent) => $this->getStatusBadge($subComponent->status))
            ->addColumn('action', $this->addActionColumn(
                'modal',
                $this->getRoutes(),
                $this->getPermissions('sub-components')
            ))
            ->rawColumns(['checkbox', 'action', 'status', 'name', 'component', 'description']);
    }

    public function query(SubComponent $model): QueryBuilder
    {
        $query = $model->newQuery()
        ->select('sub_components.*', 'components.name as component_name', 'components.name_np as component_name_np')
        ->leftJoin('components', 'sub_components.component_id', '=', 'components.id');
        $query = $this->applyGlobalSearch($query, request('search.value'));
        $query = $this->applyColumnSpecificSearch($query);
        return $query;
    }

    public function html(): HtmlBuilder
    {
        $routeName = route("admin.sub-components.import");

        return $this->builder()
            ->setTableId('subComponents-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom($this->getCommonDom())
            ->orderBy(1)
            ->buttons(
                array_merge(
                    $this->dtActionModalButtons('sub-components', 'SubComponent'),
                    $this->importButton($routeName)
                )
            )
            ->parameters([
                'initComplete' => 'function() {
                    ' . $this->initBulkDeleteScript('subComponents_ids[]') . '
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
            Column::make('component')->title(__('field.component')),
            Column::make('name')->title(__('field.name')),
            Column::make('description')->title(__('field.description')),
            Column::make('status')->title(__('field.status')),
            $this->actionColumn(),
        ];
    }

    protected function filename(): string
    {
        return 'SubComponent_' . date('YmdHis');
    }

    protected function getRoutes(): array
    {
        return [
            'create' => 'admin.sub-components.create',
            'view' => 'admin.sub-components.show',
            'edit' => 'admin.sub-components.edit',
            'delete' => 'admin.sub-components.destroy',
        ];
    }
}