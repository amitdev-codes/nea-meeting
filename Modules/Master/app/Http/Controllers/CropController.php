<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Master\Models\Crop;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use Modules\Master\DataTables\CropDataTable;
use App\Http\Controllers\BaseAdminController;
use Modules\Master\Http\Requests\StoreCropRequest;
use Modules\Master\Http\Requests\UpdateCropRequest;

class CropController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;

    protected $model = Crop::class;
    protected string $resourcePermission = 'crops';
    protected string $resourceName = 'crops';
    protected string $formView = 'master::pages.crops.cropForm';

    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(CropDataTable $dataTable)
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
    public function store(StoreCropRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            Crop::create($request->validated());
        }, 'admin.crops.index', 'Crop created successfully.', 'Failed to create the Crop.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Crop $crop)
    {
              return view('master::pages.crops.show', ['resource' => $crop]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Crop $crop)
    {
              return $this->renderModalForm($this->formView, $crop);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCropRequest $request, Crop $crop)
    {
        return $this->handleRequest($request, function () use ($request, $crop) {
            $crop->update($request->validated());
        }, 'admin.crops.index', 'Crop updated successfully.', 'Failed to update the Crop.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Crop $crop)
    {
       
        return $this->handleRequest($request, function () use ($crop) {
            $crop->delete();
        }, 'admin.crops.index', 'Crop deleted successfully.', 'Failed to delete the Crop.');
    }
    public function import(Request $request)
    {
        $storeRequest = new StoreCropRequest();
        $rules = $storeRequest->rules();
        unset($rules['name']);
        $rules['name'] = 'required|max:10';// Simplified rule for import
        return $this->handleImport($request, 'name', $rules, 'admin.stores.index');
    }
}