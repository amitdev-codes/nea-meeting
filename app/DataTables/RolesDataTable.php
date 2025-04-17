<?php

namespace App\DataTables;

use App\Models\Role;
use App\Traits\CommonDataTableFunctions;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class RolesDataTable extends DataTable
{
    use CommonDataTableFunctions;
    protected array $searchableColumns = ['role_name'];

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('checkbox', fn ($role) => $this->renderCheckbox('roles_ids[]', $role->id))
            ->addColumn('role_name', function ($role) {
                return $role->name . ' (' . ($role->name_np ?? 'N/A') . ')';
            })
            ->addColumn('users_count', fn ($role) => cache()->remember(
                "role_{$role->id}_users_count",
                now()->addHour(),
                fn () => $role->users()->count()
            ))
            ->addColumn('status', fn ($row) => $this->getStatusBadge($row->status))
            ->addColumn('action', $this->addActionColumn(
                'form',
                $this->getRoutes(),
                $this->getPermissions('roles'),
            ))
            ->setRowId('id')
            ->rawColumns(['checkbox', 'action','role_name']);
    }

    public function query(Role $model): QueryBuilder
    {
       $query=$model->newQuery()->with('permissions', 'users');
                   // Handle global search
                   if (request()->has('search') && request('search')['value']) {
                    $search = request('search')['value'];
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('name_np', 'like', "%{$search}%");
                    });
                }
        
                // Handle individual column searches
                if (request()->has('columns')) {
                    foreach (request('columns') as $i => $column) {
                        if (isset($column['search']['value']) && $column['search']['value'] !== '') {
                            $value = $column['search']['value'];
                            if ($column['data'] === 'role_name') {
                                $query->where(function ($q) use ($value) {
                                    $q->where('name', 'like', "%{$value}%")
                                      ->orWhere('name_np', 'like', "%{$value}%");
                                });
                            }
                        }
                    }
                }
    
                return $query;
    }

    public function html(): HtmlBuilder
    {

        return $this->builder()
            ->setTableId('roles-table')
            ->columns($this->getColumns())
            ->dom($this->getCommonDom())
            ->orderBy(0)
            ->buttons(
                array_merge(
                    $this->dtActionButtons('roles','Role'),
                )
            )
            ->parameters([
                'initComplete' => 'function() {
                    ' . $this->initBulkDeleteScript('roles_ids[]') . '
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
                        'targets' => '_all', // Applies to all columns
                        'className' => 'dt-head-nowrap' // Prevents text wrapping in headers
                    ]
                ]
            ]);
    
    }

    public function getColumns(): array
    {
        return [
            $this->checkboxColumn(),
            Column::make('role_name')->title('Role Name'),
            Column::make('users_count')->title('Users'),
            $this->actionColumn(),
        ];
    }

    protected function filename(): string
    {
        return $this->generateFilename('Roles');
    }
    protected function getRoutes(): array
    {
        return [
            'create' => 'admin.roles.create',
            'view' => 'admin.roles.show',
            'edit' => 'admin.roles.edit',
            'delete' => 'admin.roles.destroy',
        ];
    }

}
