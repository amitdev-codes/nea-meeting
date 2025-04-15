<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use Modules\Master\Models\Asset;
use Modules\Master\DataTables\AssetDataTable;
use Modules\Master\Http\Requests\StoreAssetRequest;
use Modules\Master\Http\Requests\UpdateAssetRequest;
use App\Http\Controllers\BaseAdminController;

class AssetController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;
    
    protected $model = Asset::class;
    protected string $resourcePermission = 'assets';
    protected string $resourceName = 'assets';
    protected string $formView = 'master::pages.assets.assetForm';
    
    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }
    
    public function index(AssetDataTable $dataTable)
    {
        return $this->renderDataTable($dataTable);
    }
    
    public function create(Request $request)
    {
        return $this->renderModalForm($this->formView);
    }
    
    public function store(StoreAssetRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            Asset::create($request->validated());
            if ($request->has('save_and_add_more')) {
                return redirect()->route('admin.assets.create')
                ->with('success', 'Group Member created successfully. Add another one.');
            }
        }, 'admin.assets.index', 'Asset created successfully.', 'Failed to create the Asset.');
    }
    
    public function show(Asset $asset)
    {
        return view('master::pages.assets.show', ['resource' => $asset]);
    }
    
    public function edit(Asset $asset)
    {
        return $this->renderModalForm($this->formView, $asset);
    }
    
    public function update(UpdateAssetRequest $request, Asset $asset)
    {
        return $this->handleRequest($request, function () use ($request, $asset) {
            $asset->update($request->validated());
        }, 'admin.assets.index', 'Asset updated successfully.', 'Failed to update the Asset.');
    }
    
    public function destroy(Request $request, Asset $asset)
    {
        return $this->handleRequest($request, function () use ($asset) {
            $asset->delete();
        }, 'admin.assets.index', 'Asset deleted successfully.', 'Failed to delete the Asset.');
    }
}