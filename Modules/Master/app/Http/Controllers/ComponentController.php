<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use Modules\Master\Models\Component;
use App\Http\Controllers\BaseAdminController;
use Modules\Master\DataTables\ComponentDataTable;
use Modules\Master\Http\Requests\StoreComponentRequest;
use Modules\Master\Http\Requests\UpdateComponentRequest;

class ComponentController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;

    protected $model = Component::class;
    protected string $resourcePermission = 'components';
    protected string $resourceName = 'components';
    protected string $formView = 'master::pages.components.componentForm';

    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(ComponentDataTable $dataTable)
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
    public function store(StoreComponentRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            Component::create($request->validated());
        }, 'admin.components.index', 'Component created successfully.', 'Failed to create the Component.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Component $component)
    {
        return view('master::pages.components.show', ['resource' => $component]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Component $component)
    {
        return $this->renderModalForm($this->formView, $component);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateComponentRequest $request, Component $component)
    {
        return $this->handleRequest($request, function () use ($request, $component) {
            $component->update($request->validated());
        }, 'admin.components.index', 'Component updated successfully.', 'Failed to update the Component.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Component $component)
    {
        return $this->handleRequest($request, function () use ($component) {
            $component->delete();
        }, 'admin.components.index', 'Component deleted successfully.', 'Failed to delete the Component.');
    }
    public function import(Request $request)
    {
        $storeRequest = new StoreComponentRequest();
        $rules = $storeRequest->rules();
        unset($rules['name']);
        $rules['name'] = 'required|max:10';// Simplified rule for import
        return $this->handleImport($request, 'name', $rules, 'admin.components.index');
    }
}