<?php

namespace Modules\Master\DataTables;

use Modules\Master\Models\Sector;
use App\Traits\CommonDataTableFunctions;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SectorDataTable extends DataTable
{
    use CommonDataTableFunctions;

    protected array $searchableColumns = ['code','name'];

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('checkbox', fn ($row) => $this->renderCheckbox('sectors[]', $row->id))
            ->addColumn('status', fn ($row) => $this->getStatusBadge($row->status))
            ->addColumn('name', function ($row) {
                return $row->name . ' (' . ($row->name_np ?? 'N/A') . ')';
            })
            ->addColumn('action', $this->addActionColumn(
                'modal',
                $this->getRoutes(),
                $this->getPermissions('sectors')
            ))
            ->rawColumns(['checkbox', 'action','status']);
    }

    public function query(Sector $model): QueryBuilder
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
        $routeName = route("admin.sectors.import");
        return $this->builder()
            ->setTableId('sectors-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom($this->getCommonDom())
            ->orderBy(0)
            ->buttons(
                array_merge(
                    $this->dtActionModalButtons('sectors','Sector'),
                    $this->importButton($routeName) // Pass the route name here
                )
            )
            ->parameters([
                'initComplete' => 'function() {
                    ' . $this->initBulkDeleteScript('sectors[]') . '
                    ' . $this->initDeleteScript() . '
                    ' . $this->initColumnSearch() . '
                    ' . $this->initStickyColumnsStyles() . '  // Add sticky column styles here
                }',
            ]);
    }

    public function getColumns(): array
    {
        return [
            $this->checkboxColumn(),
            Column::make('code')->title(__('field.code')),
            Column::make('name')->title(__('field.name')),
            Column::make('status')->title(__('field.status')),
            $this->actionColumn(),
        ];
    }

    protected function filename(): string
    {
        return 'Sector_' . date('YmdHis');
    }

    protected function getRoutes(): array
    {
        return [
            'create' => 'admin.sectors.create',
            'view' => 'admin.sectors.show',
            'edit' => 'admin.sectors.edit',
            'delete' => 'admin.sectors.destroy',
        ];
    }

}