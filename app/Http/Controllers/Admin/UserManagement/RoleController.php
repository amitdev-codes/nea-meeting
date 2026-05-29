<?php

namespace App\Http\Controllers\Admin\UserManagement;

use App\DataTables\RolesDataTable;
use App\Http\Controllers\BaseAdminController;
use App\Models\Resource;
use App\Services\ResponseService;
use App\Services\RoleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Throwable;

class RoleController extends BaseAdminController
{
    protected $model = Role::class;

    protected string $resourcePermission = 'roles';

    protected string $resourceName = 'roles';

    protected string $formView = 'pages.roles.roleForm';

    public function __construct(protected RoleService $roleService, ResponseService $responseService)
    {
        parent::__construct($responseService);
    }

    public function index(RolesDataTable $dataTable)
    {
        return $dataTable->render('pages.resources.index');
    }

    public function store(Request $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:roles,name',

                'permissions' => 'nullable|array',
            ]);
            $this->roleService->createRole(
                [
                    'name' => $validated['name'],
                    'guard_name' => 'web',
                    'code' => $validated['name'],

                ],
                $validated['permissions'] ?? []
            );
        }, 'admin.roles.index');
    }

    public function create()
    {
        return $this->renderForm($this->formView, null);
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

    /**
     * @throws Throwable
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
            'standalone_permissions' => 'nullable|array',
        ]);

        $permissions = array_merge(
            $validated['permissions'] ?? [],
            $validated['standalone_permissions'] ?? []
        );

        $permissions = array_unique($permissions);
        $this->roleService->updateRole(
            $role,
            $request->only(['name']),
            $permissions
        );

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Request $request, Role $role)
    {
        return $this->handleRequest(
            $request,
            function () use ($role) {
                $this->roleService->deleteRole($role);
            },
            'admin.roles.index',
            'Role deleted successfully.',
            'Failed to delete the role.'
        );
    }
}
