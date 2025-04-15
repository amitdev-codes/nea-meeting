<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use Modules\Master\Models\Sector;
use Modules\Master\DataTables\SectorDataTable;
use Modules\Master\Http\Requests\StoreSectorRequest;
use Modules\Master\Http\Requests\UpdateSectorRequest;
use App\Http\Controllers\BaseAdminController;

class SectorController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;

    protected $model = Sector::class;
    protected string $resourcePermission = 'sectors';
    protected string $resourceName = 'sectors';
    protected string $formView = 'master::pages.sectors.sectorForm';

    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }

    public function index(SectorDataTable $dataTable)
    {
        return $this->renderDataTable($dataTable);
    }

    public function create(Request $request)
    {
        return $this->renderModalForm($this->formView);
    }

    public function store(StoreSectorRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            Sector::create($request->validated());
        }, 'admin.sectors.index', 'Sector created successfully.', 'Failed to create the Sector.');
    }

    public function show(Sector $sector)
    {
        return view('master::pages.sectors.show', ['resource' => $sector]);
    }

    public function edit(Sector $sector)
    {
        return $this->renderModalForm($this->formView, $sector);
    }

    public function update(UpdateSectorRequest $request, Sector $sector)
    {
        return $this->handleRequest($request, function () use ($request, $sector) {
            $sector->update($request->validated());
        }, 'admin.sectors.index', 'Sector updated successfully.', 'Failed to update the Sector.');
    }

    public function destroy(Request $request, Sector $sector)
    {
        $this->authorizeResource('delete');
        return $this->handleRequest($request, function () use ($sector) {
            $sector->delete();
        }, 'admin.sectors.index', 'Sector deleted successfully.', 'Failed to delete the Sector.');
    }
}