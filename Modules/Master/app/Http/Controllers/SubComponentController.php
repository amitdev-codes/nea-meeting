<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use Modules\Master\Models\Component;
use Modules\Master\Models\SubComponent;
use App\Http\Controllers\BaseAdminController;
use Modules\Master\DataTables\SubComponentDataTable;
use Modules\Master\Http\Requests\StoreSubComponentRequest;
use Modules\Master\Http\Requests\UpdateSubComponentRequest;

class SubComponentController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;

    protected $model = SubComponent::class;
    protected string $resourcePermission = 'sub-components';
    protected string $resourceName = 'sub-components';
    protected string $formView = 'master::pages.sub-components.subcomponentForm';

    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(SubComponentDataTable $dataTable)
    {
        return $this->renderDataTable($dataTable);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $components = Component::get(['id','name', 'name_np']);
        return $this->renderModalForm($this->formView, null, ['components' => $components]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubComponentRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            SubComponent::create($request->validated());
        }, 'admin.sub-components.index', 'SubComponent created successfully.', 'Failed to create the SubComponent.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SubComponent $subComponent)
    {
        return view('master::pages.sub-components.show', ['resource' => $subComponent]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubComponent $subComponent)
    {
        $components = Component::get(['id','name', 'name_np']);
        return $this->renderModalForm($this->formView, $subComponent,['components' => $components]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubComponentRequest $request, SubComponent $subComponent)
    {
        return $this->handleRequest($request, function () use ($request, $subComponent) {
            $subComponent->update($request->validated());
        }, 'admin.sub-components.index', 'SubComponent updated successfully.', 'Failed to update the SubComponent.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, SubComponent $subComponent)
    {
        return $this->handleRequest($request, function () use ($subComponent) {
            $subComponent->delete();
        }, 'admin.sub-components.index', 'SubComponent deleted successfully.', 'Failed to delete the SubComponent.');
    }
    public function import(Request $request)
    {
        $storeRequest = new StoreSubComponentRequest();
        $rules = $storeRequest->rules();
        unset($rules['name']);
        $rules['name'] = 'required|max:10';// Simplified rule for import
        return $this->handleImport($request, 'name', $rules, 'admin.sub-components.index');
    }
    public function getSubComponents(Request $request)
    {
        $componentId = $request->input('component_id');
        $subComponents = SubComponent::where('component_id', $componentId)->get();
        return response()->json($subComponents);
    }
}