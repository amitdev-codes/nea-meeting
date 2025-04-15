<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use Modules\Master\Models\Organization;
use Modules\Master\DataTables\OrganizationDataTable;
use Modules\Master\Http\Requests\StoreOrganizationRequest;
use Modules\Master\Http\Requests\UpdateOrganizationRequest;
use App\Http\Controllers\BaseAdminController;

class OrganizationController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;
    
    protected $model = Organization::class;
    protected string $resourcePermission = 'organizations';
    protected string $resourceName = 'organizations';
    protected string $formView = 'master::pages.organizations.organizationForm';
    
    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }
    
    public function index(OrganizationDataTable $dataTable)
    {
        return $this->renderDataTable($dataTable);
    }
    
    public function create(Request $request)
    {
        return $this->renderModalForm($this->formView);
    }
    
    public function store(StoreOrganizationRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            Organization::create($request->validated());
            if ($request->has('save_and_add_more')) {
                return redirect()->route('admin.organizations.create')
                ->with('success', 'Group Member created successfully. Add another one.');
            }
        }, 'admin.organizations.index', 'Organization created successfully.', 'Failed to create the Organization.');
    }
    
    public function show(Organization $organization)
    {
        return view('master::pages.organizations.show', ['resource' => $organization]);
    }
    
    public function edit(Organization $organization)
    {
        return $this->renderModalForm($this->formView, $organization);
    }
    
    public function update(UpdateOrganizationRequest $request, Organization $organization)
    {
        return $this->handleRequest($request, function () use ($request, $organization) {
            $organization->update($request->validated());
        }, 'admin.organizations.index', 'Organization updated successfully.', 'Failed to update the Organization.');
    }
    
    public function destroy(Request $request, Organization $organization)
    {
        return $this->handleRequest($request, function () use ($organization) {
            $organization->delete();
        }, 'admin.organizations.index', 'Organization deleted successfully.', 'Failed to delete the Organization.');
    }
}