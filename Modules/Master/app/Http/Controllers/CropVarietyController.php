<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Master\Models\Crop;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use Modules\Master\Models\CropVariety;
use App\Http\Controllers\BaseAdminController;
use Modules\Master\DataTables\CropVarietyDataTable;
use Modules\Master\Http\Requests\StoreCropVarietyRequest;
use Modules\Master\Http\Requests\UpdateCropVarietyRequest;

class CropVarietyController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;

    protected $model = CropVariety::class;
    protected string $resourcePermission = 'crop-varieties';
    protected string $resourceName = 'crop-varieties';
    protected string $formView = 'master::pages.crop-varieties.cropvarietyForm';

    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(CropVarietyDataTable $dataTable)
    {
        return $this->renderDataTable($dataTable);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $crops=Crop::get(['id','name','name_np']);
        return $this->renderModalForm($this->formView,null,['crops'=>$crops]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCropVarietyRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            CropVariety::create($request->validated());
        }, 'admin.crop-varieties.index', 'CropVariety created successfully.', 'Failed to create the CropVariety.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CropVariety $cropVariety)
    {
        return view('master::pages.crop-varieties.show', ['resource' => $cropVariety]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CropVariety $cropVariety)
    {
        $crops=Crop::get(['id','name','name_np']);
        return $this->renderModalForm($this->formView,$cropVariety,['crops'=>$crops]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCropVarietyRequest $request, CropVariety $cropVariety)
    {
        return $this->handleRequest($request, function () use ($request, $cropVariety) {
            $cropVariety->update($request->validated());
        }, 'admin.crop-varieties.index', 'CropVariety updated successfully.', 'Failed to update the CropVariety.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, CropVariety $cropVariety)
    {
        return $this->handleRequest($request, function () use ($cropVariety) {
            $cropVariety->delete();
        }, 'admin.crop-varieties.index', 'CropVariety deleted successfully.', 'Failed to delete the CropVariety.');
    }
    public function import(Request $request)
    {
        $storeRequest = new StoreCropVarietyRequest();
        $rules = $storeRequest->rules();
        unset($rules['name']);
        $rules['name'] = 'required|max:10';// Simplified rule for import
        return $this->handleImport($request, 'name', $rules, 'admin.crop-varieties.index');
    }
}