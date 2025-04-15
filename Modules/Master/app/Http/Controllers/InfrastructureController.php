<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use Modules\Master\Models\Infrastructure;
use Modules\Master\DataTables\InfrastructureDataTable;
use Modules\Master\Http\Requests\StoreInfrastructureRequest;
use Modules\Master\Http\Requests\UpdateInfrastructureRequest;
use App\Http\Controllers\BaseAdminController;

class InfrastructureController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;
    
    protected $model = Infrastructure::class;
    protected string $resourcePermission = 'infrastructures';
    protected string $resourceName = 'infrastructures';
    protected string $formView = 'master::pages.infrastructures.infrastructureForm';
    
    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }
    
    public function index(InfrastructureDataTable $dataTable)
    {
        return $this->renderDataTable($dataTable);
    }
    
    public function create(Request $request)
    {
        return $this->renderModalForm($this->formView);
    }
    
    public function store(StoreInfrastructureRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            Infrastructure::create($request->validated());
            if ($request->has('save_and_add_more')) {
                return redirect()->route('admin.infrastructures.create')
                ->with('success', 'Group Member created successfully. Add another one.');
            }
        }, 'admin.infrastructures.index', 'Infrastructure created successfully.', 'Failed to create the Infrastructure.');
    }
    
    public function show(Infrastructure $infrastructure)
    {
        return view('master::pages.infrastructures.show', ['resource' => $infrastructure]);
    }
    
    public function edit(Infrastructure $infrastructure)
    {
        return $this->renderModalForm($this->formView, $infrastructure);
    }
    
    public function update(UpdateInfrastructureRequest $request, Infrastructure $infrastructure)
    {
        return $this->handleRequest($request, function () use ($request, $infrastructure) {
            $infrastructure->update($request->validated());
        }, 'admin.infrastructures.index', 'Infrastructure updated successfully.', 'Failed to update the Infrastructure.');
    }
    
    public function destroy(Request $request, Infrastructure $infrastructure)
    {
        return $this->handleRequest($request, function () use ($infrastructure) {
            $infrastructure->delete();
        }, 'admin.infrastructures.index', 'Infrastructure deleted successfully.', 'Failed to delete the Infrastructure.');
    }
}