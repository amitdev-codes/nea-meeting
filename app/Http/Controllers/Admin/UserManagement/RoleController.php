<?php

namespace App\Http\Controllers\Admin\UserManagement;

use App\DataTables\RolesDataTable;
use App\Http\Controllers\BaseAdminController;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Services\ResponseService;
use App\Traits\BulkDeletableTrait;
use App\Traits\HandlesExceptions;
use Spatie\Permission\Models\Role;
use App\Models\Resource;

class RoleController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;

    protected $model = Role::class;
    protected string $resourcePermission = 'roles';
    protected string $resourceName = 'roles';
    protected string $formView = 'pages.roles.roleForm';

    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }

    public function index(RolesDataTable $dataTable)
    {
        return $dataTable->render('pages.resources.index');
    }

    public function create()
    {
        $resources = Resource::all();
        return $this->renderForm($this->formView, null, ['resources' => $resources]);
    }

    public function store(StoreRoleRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            $validatedData = $request->validated();
            $role = Role::create(['name' => $validatedData['name'], 'guard_name' => 'web']);
            $this->syncPermissions($role, $validatedData['permissions'] ?? []);
        }, 'admin.roles.index');
    }

    public function show(Role $role)
    {
        $permissions = $role->permissions->pluck('name')->toArray();
        return view('pages.roles.show', ['resource' => $role, 'permissions' => $permissions]);
    }

    public function edit(Role $role)
    {
        $resources = Resource::all();
        return $this->renderForm($this->formView, $role, ['resources' => $resources, 'model' => $role]);
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        // dd($request->all(),$request->validated());
        return $this->handleRequest($request, function () use ($request, $role) {
            $validated = $request->validated();
            $role->update(['name' => $validated['name']]);
            $this->syncPermissions($role, $validated['permissions'] ?? []);
        }, 'admin.roles.index');
    }

    public function destroy(Role $role)
    {

        return $this->handleRequest($request, function () use ($role) {
            if ($role->users()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This role is assigned to users and cannot be deleted.',
                ], 422);
            }
            $role->delete();
        }, 'admin.roles.index');
    }

    private function syncPermissions(Role $role, array $permissions)
    {
        $permissionList = [];
        foreach ($permissions as $key => $actions) {
            foreach (['view', 'create', 'edit', 'delete'] as $action) {
                if (isset($actions[$action]) && $actions[$action]) {
                    $permissionList[] = "{$action}-{$key}";
                }
            }
        }
        $role->syncPermissions($permissionList);
    }
}