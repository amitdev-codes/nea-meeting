<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use Modules\Master\Models\LiveStockBreed;
use App\Http\Controllers\BaseAdminController;
use Modules\Master\DataTables\LiveStockBreedDataTable;
use Modules\Master\Http\Requests\StoreLiveStockBreedRequest;
use Modules\Master\Http\Requests\UpdateLiveStockBreedRequest;

class LiveStockBreedController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;
    
    protected $model = LiveStockBreed::class;
    protected string $resourcePermission = 'livestock-breeds';
    protected string $resourceName = 'livestock-breeds';
    protected string $formView = 'master::pages.livestock-breeds.liveStockBreedForm';
    
    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }
    
    public function index(LiveStockBreedDataTable $dataTable)
    {
        return $this->renderDataTable($dataTable);
    }
    
    public function create(Request $request)
    {
        return $this->renderModalForm($this->formView);
    }
    
    public function store(StoreLiveStockBreedRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            LiveStockBreed::create($request->validated());
        }, 'admin.livestock-breeds.index', 'LiveStockBreed created successfully.', 'Failed to create the LiveStockBreed.');
    }
    
    public function show(LiveStockBreed $liveStockBreed)
    {
        return view('master::pages.livestock-breeds.show', ['resource' => $liveStockBreed]);
    }
    
    public function edit(LiveStockBreed $liveStockBreed)
    {
        return $this->renderModalForm($this->formView, $liveStockBreed);
    }
    
    public function update(UpdateLiveStockBreedRequest $request, LiveStockBreed $liveStockBreed)
    {
        return $this->handleRequest($request, function () use ($request, $liveStockBreed) {
            $liveStockBreed->update($request->validated());
        }, 'admin.livestock-breeds.index', 'LiveStockBreed updated successfully.', 'Failed to update the LiveStockBreed.');
    }
    
    public function destroy(Request $request, LiveStockBreed $liveStockBreed)
    {
        return $this->handleRequest($request, function () use ($liveStockBreed) {
            $liveStockBreed->delete();
        }, 'admin.livestock-breeds.index', 'LiveStockBreed deleted successfully.', 'Failed to delete the LiveStockBreed.');
    }
}