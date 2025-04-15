<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use Modules\Master\Models\LiveStock;
use Modules\Master\DataTables\LiveStockDataTable;
use Modules\Master\Http\Requests\StoreLiveStockRequest;
use Modules\Master\Http\Requests\UpdateLiveStockRequest;
use App\Http\Controllers\BaseAdminController;

class LiveStockController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;
    
    protected $model = LiveStock::class;
    protected string $resourcePermission = 'livestocks';
    protected string $resourceName = 'livestocks';
    protected string $formView = 'master::pages.livestocks.livestockForm';
    
    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }
    
    public function index(LiveStockDataTable $dataTable)
    {
        return $this->renderDataTable($dataTable);
    }
    
    public function create(Request $request)
    {
        return $this->renderModalForm($this->formView);
    }
    
    public function store(StoreLiveStockRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            LiveStock::create($request->validated());
        }, 'admin.livestocks.index', 'LiveStock created successfully.', 'Failed to create the LiveStock.');
    }
    
    public function show(LiveStock $liveStock)
    {
        return view('master::pages.livestocks.show', ['resource' => $liveStock]);
    }
    
    public function edit(LiveStock $liveStock)
    {
        return $this->renderModalForm($this->formView, $liveStock);
    }
    
    public function update(UpdateLiveStockRequest $request, LiveStock $liveStock)
    {
        return $this->handleRequest($request, function () use ($request, $liveStock) {
            $liveStock->update($request->validated());
        }, 'admin.livestocks.index', 'LiveStock updated successfully.', 'Failed to update the LiveStock.');
    }
    
    public function destroy(Request $request, LiveStock $liveStock)
    {
        return $this->handleRequest($request, function () use ($liveStock) {
            $liveStock->delete();
        }, 'admin.livestocks.index', 'LiveStock deleted successfully.', 'Failed to delete the LiveStock.');
    }
}