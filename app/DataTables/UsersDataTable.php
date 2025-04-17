<?php

namespace App\DataTables;

use App\Models\Role;
use App\Models\User;
use Yajra\DataTables\Html\Column;
use Modules\Master\Models\Cluster;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\EloquentDataTable;
use App\Traits\CommonDataTableFunctions;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class UsersDataTable extends DataTable
{
    use CommonDataTableFunctions;

    protected array $searchableColumns = ['code', 'username', 'email', 'role_name', 'mobile_no', 'organization_name','role_name'];
    protected array $dropdownColumns = [

    ];
    public function __construct()
    {
        parent::__construct();
        $this->dropdownColumns['organization_name']['options'] = Cache::get('organizations')->pluck('name', 'id')->toArray();
        $this->dropdownColumns['role_name']['options'] = Cache::get('roles')->pluck('name', 'id')->toArray();
    }

    protected function roleOptions(): array
    {
        return Role::pluck('name', 'name')->all(); 
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('checkbox', fn ($user) => $this->renderCheckbox('users_ids[]', $user->id))
            ->addColumn('role_name', fn ($user) => $this->generateBadges($user->roles->pluck('name')))
            ->addColumn('organization_name', function ($user) {
                return $user->organization_id 
                    ? $user->organizations->name . ' (' . ($user->organizations->name_np ?? 'N/A') . ')' 
                    : 'N/A';
            })
            ->addColumn('status', fn ($row) => $this->getStatusBadge($row->status))
            ->addColumn('action', $this->addActionColumn(
                'form',
                $this->getRoutes(),
                $this->getPermissions('users'),
            ))
            ->rawColumns(['checkbox', 'role_name', 'action', 'status','cluster_name']);
    }

    public function query(User $model): QueryBuilder
    {
        $query = $model->newQuery()->with('roles');
        
        $dropdownFields = [
            'organization_name' => 'organization_id',
            'section_name' => 'section_id',
            'role_name' => 'roles.name',
        ];
    
        // Global search (top search bar)
        if (request()->has('search') && request('search')['value']) {
            $search = request('search')['value'];
            $query->where(function ($q) use ($search, $dropdownFields) {
                foreach ($this->searchableColumns as $column) {
                    if (array_key_exists($column, $dropdownFields)) {
                        continue;
                    }
                    $q->orWhere($column, 'like', "%{$search}%");
                }
                $q->orWhereHas('roles', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        }
    
        // Column-specific search (filter row)
        if (request()->has('columns')) {
            foreach (request('columns') as $i => $column) {
                if (isset($column['search']['value']) && $column['search']['value'] !== '') {
                    $value = $column['search']['value'];
                    $columnData = $column['data'];
                    
                    if (in_array($columnData, $this->searchableColumns)) {
                        if ($columnData == 'role_name') {
                            $query->whereHas('roles', function ($q) use ($value) {
                                $q->where('id', '=', "{$value}");
                            });
                        } elseif (array_key_exists($columnData, $dropdownFields)) {
                            $query->where($dropdownFields[$columnData], $value);
                        } else {
                            $query->where($columnData, 'like', "%{$value}%");
                        }
                    }
                }
            }
        }
    
        return $query;
    }
    

    public function html(): HtmlBuilder
    {


        return $this->builder()
            ->setTableId('users-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom($this->getCommonDom())
            ->orderBy(1)
            ->buttons(
                array_merge(
                    $this->dtActionButtons('users','User'),
                )
            )
            ->parameters([
                'initComplete' => 'function() {
                    ' . $this->initBulkDeleteScript('users_ids[]') . '
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
            Column::make('username')->title(__('field.name'))->addClass('wrap-text'),
            Column::make('organization_name')->title(__('field.organization_id'))->addClass('wrap-text'),
            Column::make('role_name')->title(__('field.role_name'))->addClass('wrap-text')->width('1%'),
            Column::make('mobile_no')->title(__('field.mobile_no'))->addClass('wrap-text'),
            Column::make('email')->title(__('field.email'))->addClass('wrap-text'),
            Column::make('status')->title(__('field.status')),
            $this->actionColumn(),
        ];
    }

    protected function filename(): string
    {
        return $this->generateFilename('Users');
    }

    protected function getRoutes(): array
    {
        return [
            'create' => 'admin.users.create',
            'view' => 'admin.users.show',
            'edit' => 'admin.users.edit',
            'delete' => 'admin.users.destroy',
        ];
    }
}