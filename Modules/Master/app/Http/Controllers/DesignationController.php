<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use Modules\Master\Models\Designation;
use App\Http\Controllers\BaseAdminController;
use Modules\Master\DataTables\DesignationDataTable;
use Modules\Master\Http\Requests\StoreDesignationRequest;
use Modules\Master\Http\Requests\UpdateDesignationRequest;

class DesignationController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;

    protected $model = Designation::class;
    protected string $resourcePermission = 'designations';
    protected string $resourceName = 'designations';
    protected string $formView = 'master::pages.designations.designationForm';

    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(DesignationDataTable $dataTable)
    {
        return $this->renderDataTable($dataTable);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return $this->renderModalForm($this->formView);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDesignationRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            Designation::create($request->validated());
        }, 'admin.designations.index', 'Designation created successfully.', 'Failed to create the Designation.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Designation $designation)
    {
        return view('master::pages.designations.show', ['resource' => $designation]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Designation $designation)
    {
        return $this->renderModalForm($this->formView, $designation);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDesignationRequest $request, Designation $designation)
    {
        return $this->handleRequest($request, function () use ($request, $designation) {
            $designation->update($request->validated());
        }, 'admin.designations.index', 'Designation updated successfully.', 'Failed to update the Designation.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Designation $designation)
    {
        return $this->handleRequest($request, function () use ($designation) {
            $designation->delete();
        }, 'admin.designations.index', 'Designation deleted successfully.', 'Failed to delete the Designation.');
    }
}