<?php

namespace Modules\Master\DataTables;

use Yajra\DataTables\Html\Column;
use Modules\Master\Models\Cluster;
use Modules\Master\Models\District;
use Modules\Master\Models\Province;
use Modules\Master\Models\LocalLevel;
use Yajra\DataTables\EloquentDataTable;
use App\Traits\CommonDataTableFunctions;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ClusterDataTable extends DataTable
{
    use CommonDataTableFunctions;

    protected array $searchableColumns = ['code','cluster_type','name'];

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('checkbox', fn ($row) => $this->renderCheckbox('clusters[]', $row->id))
            ->addColumn('name', function ($row) {
                return $row->name . ' (' . ($row->name_np ?? 'N/A') . ')';
            })
            ->addColumn('cluster_type', function ($cluster) {
                return $cluster->clusterType->name . ' (' . ($cluster->clusterType->name_np ?? 'N/A') . ')';
            })
            ->addColumn('provinces_name', function ($cluster) {
                $provinces = $cluster->provinces ?? [];
                
                if (empty($provinces)) {
                    return 'N/A';
                }
                
                return collect($provinces)->map(function ($province_id) {
                    return Province::find($province_id)->name ?? 'N/A';
                })->implode(', ');
            })
            ->addColumn('districts_name', function ($cluster) {
                $districts = $cluster->districts ?? [];
                
                if (empty($districts)) {
                    return 'N/A';
                }
                
                return collect($districts)->map(function ($district_id) {
                    return District::find($district_id)->name ?? 'N/A';
                })->implode(', ');
            })
            ->addColumn('local_levels_name', function ($cluster) {
                $local_levels = $cluster->local_levels ?? [];
                
                if (empty($local_levels)) {
                    return 'N/A';
                }
                
                // Assuming you have a LocalLevel model
                return collect($local_levels)->map(function ($local_level_id) {
                    return LocalLevel::find($local_level_id)->name ?? 'N/A';
                })->implode(', ');
            })
            ->addColumn('status', fn ($row) => $this->getStatusBadge($row->status))
            ->addColumn('action', $this->addActionColumn(
                'form',
                $this->getRoutes(),
                $this->getPermissions('clusters')
            ))
            ->rawColumns(['checkbox', 'action', 'status']);
    }

    public function query(Cluster $model): QueryBuilder
    {
        $query = $model->newQuery();
        $query->where('status',true);

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
        $routeName = route("admin.clusters.import");
        return $this->builder()
            ->setTableId('clusters-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom($this->getCommonDom())
            ->orderBy(0)
            ->buttons(
                array_merge(
                    $this->dtActionButtons('clusters','Cluster'),
                    $this->importButton($routeName) // Pass the route name here
                )
            )
            ->parameters([
                'initComplete' => 'function() {
                    ' . $this->initBulkDeleteScript('clusters[]') . '
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
            Column::make('code')->title(__('field.code')),
            Column::make('name')->title(__('field.name'))->addClass('wrap-text')->width('5%'),
            Column::make('cluster_type')->title(__('field.cluster_type')),
            Column::make('provinces_name')->title(__('field.provinces_name'))->addClass('wrap-text')->width('10%'),
            Column::make('districts_name')->title(__('field.districts_name'))->addClass('wrap-text')->width('10%'),
            Column::make('local_levels_name')->title(__('field.local_levels_name'))->addClass('wrap-text')->width('20%'),

            Column::make('status')->title(__('field.status'))->width('10%'),
            $this->actionColumn()
        ];
    }

    protected function filename(): string
    {
        return 'Cluster_' . date('YmdHis');
    }

    protected function getRoutes(): array
    {
        return [
            'create' => 'admin.clusters.create',
            'view' => 'admin.clusters.show',
            'edit' => 'admin.clusters.edit',
            'delete' => 'admin.clusters.destroy',
        ];
    }

}