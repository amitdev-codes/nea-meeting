<?php

namespace App\Http\Controllers\Admin\UserManagement;

use App\DataTables\PermissionsDataTable;
use App\Http\Controllers\BaseAdminController;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Services\ResponseService;
use App\Traits\BulkDeletableTrait;
use App\Traits\HandlesExceptions;
use Spatie\Permission\Models\Permission;

class PermissionController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;

    protected $model = Permission::class;
    protected string $resourcePermission = 'permissions';
    protected string $resourceName = 'permissions';
    protected string $formView = 'pages.permissions.permissionForm';

    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(PermissionsDataTable $dataTable)
    {
        return $this->renderDataTable($dataTable);
    }

    public function show(Permission $permission)
    {
        return view('pages.permissions.show', ['resource' => $permission]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->renderModalForm($this->formView);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermissionRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            Permission::create($request->validated());
        }, 'admin.permissions.index','permissions Created successfully.', 'Failed to create the permissions.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        return $this->renderModalForm($this->formView,$permission);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        return $this->handleRequest($request, function () use ($request, $permission) {
            $permission->update($request->validated());
        }, 'admin.permissions.index','permissions Created successfully.', 'Failed to create the permissions.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        return $this->handleRequest($request, function () use ($permission) {
            $permission->delete();
        }, 'admin.permissions.index');
    }
}
