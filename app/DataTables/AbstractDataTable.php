<?php

namespace App\DataTables;

use App\Traits\CommonDataTableFunctions;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Column;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;

abstract class AbstractDataTable extends DataTable
{
    use CommonDataTableFunctions;

    /**
     * The table ID for the DataTable.
     */
    protected string $tableId;

    /**
     * The human-readable name of the entity.
     */
    protected string $entity;

    /**
     * The form type (modal, page, etc.)
     */
    protected string $formType = 'modal';

    /**
     * The checkbox name used for bulk operations.
     */
    protected string $checkboxName = 'ids[]';

    /**
     * Configure the DataTable.
     */
    abstract public function dataTable(QueryBuilder $query): EloquentDataTable;

    /**
     * Get the query source of dataTable.
     */
    abstract public function query(object $model): QueryBuilder;

    /**
     * Get the dataTable columns definition.
     */
    abstract public function getColumns(): array;

    /**
     * Define available routes for CRUD operations.
     */
    abstract protected function getRoutes(): array;

    /**
     * Define required permissions for actions.
     */
    protected function getPermissions(): array
    {
        return [];
    }

    /**
     * Build the HTML configuration for the DataTable.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId($this->tableId)
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom($this->getCommonDom())
            ->orderBy(1)
            ->buttons($this->getActionButtons())
            ->parameters([
                'initComplete' => 'function() {
                    ' . $this->initBulkDeleteScript($this->checkboxName) . '
                    ' . $this->initDeleteScript() . '
                }',
            ]);
    }

    /**
     * Get appropriate action buttons based on form type.
     */
    protected function getActionButtons(): array
    {
        return $this->formType === 'modal' 
            ? $this->dtActionModalButtons($this->entity)
            : $this->dtActionPageButtons($this->entity);
    }

    /**
     * Get non-modal action buttons.
     */
    protected function dtActionPageButtons(string $entityName = 'Item'): array
    {
        $buttons = $this->dtActionModalButtons($entityName);
        
        // Replace modal create with direct link
        $createRoute = $this->getRoutes()['create'] ?? null;
        if ($createRoute) {
            $buttons[1] = [
                'text' => '<i data-feather="plus"></i> Add New ' . $entityName,
                'className' => 'create-new btn btn-primary',
                'action' => 'function (e, dt, node, config) {
                    window.location.href = "' . route($createRoute) . '";
                }'
            ];
        }
        
        return $buttons;
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return $this->entity . '_' . date('YmdHis');
    }
}