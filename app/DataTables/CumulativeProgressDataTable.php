<?php

namespace App\DataTables;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\Models\CumulativeProgress;
use Yajra\DataTables\EloquentDataTable;
use App\Traits\CommonDataTableFunctions;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class CumulativeProgressDataTable extends DataTable
{
    use CommonDataTableFunctions;
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    protected array $searchableColumns = ['fiscal_year'];
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('checkbox', fn ($row) => $this->renderCheckbox('cumulative-progress[]', $row->id))
            ->addColumn('status', fn ($row) => $this->getStatusBadge($row->status))
            ->addColumn('fiscal_year', function ($row) {
                return $row->fiscal_year->code;
            })
            ->addColumn('action', $this->addActionColumn(
                'form',
                $this->getRoutes(),
                $this->getPermissions('cumulative-progress')
            ))
            ->rawColumns(['checkbox', 'action', 'status']);
    }

    public function query(CumulativeProgress $model): QueryBuilder
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
            ->setTableId('cumulative-progress-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom($this->getCommonDom())
            ->orderBy(1)
            ->buttons(
                array_merge(
                    $this->dtActionButtons('cumulative-progress', 'CumulativeProgress'),
                )
            )
            ->parameters([
                'initComplete' => 'function() {
                    ' . $this->initBulkDeleteScript('cumulative-progress[]') . '
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
            Column::make('fiscal_year')->title(__('field.fiscal_year')),
            Column::make('project_start_date')->title(__('field.project_start_date')),
            Column::make('project_end_date')->title(__('field.project_end_date')),
            Column::make('total_estimated_expenditure')->title(__('field.total_estimated_expenditure')),
            Column::make('total_given_expenditure')->title(__('field.total_given_expenditure')),
            Column::make('total_budget')->title(__('field.total_budget')),
            Column::make('total_disbursed')->title(__('field.total_disbursed')),
            Column::make('total_group_formed_target')->title(__('field.total_group_formed_target')),
            Column::make('status')->title(__('field.status')),
            $this->actionColumn(),
        ];
    }

    protected function filename(): string
    {
        return 'Group_' . date('YmdHis');
    }

    protected function getRoutes(): array
    {
        return [
            'create' => 'admin.cumulative-progress.create',
            'view' => 'admin.cumulative-progress.show',
            'edit' => 'admin.cumulative-progress.edit',
            'delete' => 'admin.cumulative-progress.destroy',
        ];
    }
}
