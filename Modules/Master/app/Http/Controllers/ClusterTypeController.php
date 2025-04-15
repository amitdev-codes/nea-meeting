<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use Modules\Master\Models\ClusterType;
use Modules\Master\DataTables\ClusterTypeDataTable;
use Modules\Master\Http\Requests\StoreClusterTypeRequest;
use Modules\Master\Http\Requests\UpdateClusterTypeRequest;
use App\Http\Controllers\BaseAdminController;

class ClusterTypeController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;

    protected $model = ClusterType::class;
    protected string $resourcePermission = 'cluster-types';
    protected string $resourceName = 'cluster-types';
    protected string $formView = 'master::pages.cluster-types.clusterTypeForm';

    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }

    public function index(ClusterTypeDataTable $dataTable)
    {
        return $this->renderDataTable($dataTable);
    }

    public function create(Request $request)
    {
        return $this->renderModalForm($this->formView);
    }

    public function store(StoreClusterTypeRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            ClusterType::create($request->validated());
        }, 'admin.cluster-types.index', 'ClusterType created successfully.', 'Failed to create the ClusterType.');
    }

    public function show(ClusterType $clusterType)
    {
        return view('master::pages.cluster-types.show', ['resource' => $clusterType]);
    }

    public function edit(ClusterType $clusterType)
    {
        return $this->renderModalForm($this->formView, $clusterType);
    }

    public function update(UpdateClusterTypeRequest $request, ClusterType $clusterType)
    {
        return $this->handleRequest($request, function () use ($request, $clusterType) {
            $clusterType->update($request->validated());
        }, 'admin.cluster-types.index', 'ClusterType updated successfully.', 'Failed to update the ClusterType.');
    }

    public function destroy(Request $request, ClusterType $clusterType)
    {
        return $this->handleRequest($request, function () use ($clusterType) {
            $clusterType->delete();
        }, 'admin.cluster-types.index', 'ClusterType deleted successfully.', 'Failed to delete the ClusterType.');
    }
}