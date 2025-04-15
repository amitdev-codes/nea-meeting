<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use Modules\Master\Models\Cluster;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use Modules\Master\Models\District;
use Modules\Master\Models\Province;
use Modules\Master\Models\LocalLevel;
use Modules\Lmbis\Models\LmbisSection;
use Modules\Master\Models\ClusterType;
use App\Http\Controllers\BaseAdminController;
use Modules\Master\DataTables\ClusterDataTable;
use Modules\Master\Http\Requests\StoreClusterRequest;
use Modules\Master\Http\Requests\UpdateClusterRequest;

class ClusterController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;

    protected $model = Cluster::class;
    protected string $resourcePermission = 'clusters';
    protected string $resourceName = 'clusters';
    protected string $formView = 'master::pages.clusters.clusterForm';

    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(ClusterDataTable $dataTable)
    {
        return $dataTable->render('pages.resources.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $clusterTypes=ClusterType::get(['id','name','name_np']);
        return $this->renderForm($this->formView,null,['clusterTypes' => $clusterTypes]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClusterRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            Cluster::create($request->validated());
        }, 'admin.clusters.index', 'Cluster created successfully.', 'Failed to create the Cluster.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cluster $cluster)
    {
        return view('master::pages.clusters.show', ['resource' => $cluster]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cluster $cluster)
    {
        $clusterTypes=ClusterType::get(['id','name','name_np']);
        return $this->renderForm($this->formView,$cluster,['clusterTypes' => $clusterTypes]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClusterRequest $request, Cluster $cluster)
    {
        return $this->handleRequest($request, function () use ($request, $cluster) {
            $cluster->update($request->validated());
        }, 'admin.clusters.index', 'Cluster updated successfully.', 'Failed to update the Cluster.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Cluster $cluster)
    {
        return $this->handleRequest($request, function () use ($cluster) {
            $cluster->delete();
        }, 'admin.clusters.index', 'Cluster deleted successfully.', 'Failed to delete the Cluster.');
    }
}