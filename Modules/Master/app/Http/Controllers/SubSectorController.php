<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use Modules\Master\Models\Sector;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use Modules\Master\Models\SubSector;
use App\Http\Controllers\BaseAdminController;
use Modules\Master\DataTables\SubSectorDataTable;
use Modules\Master\Http\Requests\StoreSubSectorRequest;
use Modules\Master\Http\Requests\UpdateSubSectorRequest;

class SubSectorController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;

    protected $model = SubSector::class;
    protected string $resourcePermission = 'sub-sectors';
    protected string $resourceName = 'sub-sectors';
    protected string $formView = 'master::pages.sub-sectors.subSectorForm';

    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }

    public function index(SubSectorDataTable $dataTable)
    {
        return $this->renderDataTable($dataTable);
    }

    public function create(Request $request)
    {
        return $this->renderModalForm($this->formView);
    }

    public function store(StoreSubSectorRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            SubSector::create($request->validated());
        }, 'admin.sub-sectors.index', 'SubSector created successfully.', 'Failed to create the SubSector.');
    }

    public function show(SubSector $subSector)
    {
        return view('master::pages.sub-sectors.show', ['resource' => $subSector]);
    }

    public function edit(SubSector $subSector)
    {
        return $this->renderModalForm($this->formView, $subSector);
    }

    public function update(UpdateSubSectorRequest $request, SubSector $subSector)
    {
        return $this->handleRequest($request, function () use ($request, $subSector) {
            $subSector->update($request->validated());
        }, 'admin.sub-sectors.index', 'SubSector updated successfully.', 'Failed to update the SubSector.');
    }

    public function destroy(Request $request, SubSector $subSector)
    {
        return $this->handleRequest($request, function () use ($subSector) {
            $subSector->delete();
        }, 'admin.sub-sectors.index', 'SubSector deleted successfully.', 'Failed to delete the SubSector.');
    }
}