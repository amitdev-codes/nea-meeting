<?php

namespace Modules\Master\Http\Controllers;

use App\Http\Controllers\BaseAdminController;
use App\Services\ResponseService;
use App\Traits\BulkDeletableTrait;
use App\Traits\HandlesExceptions;
use Illuminate\Http\Request;
use Modules\Master\DataTables\LengthUnitsDataTable;
use Modules\Master\Http\Requests\StoreLengthUnitRequest;
use Modules\Master\Http\Requests\UpdateLengthUnitRequest;
use Modules\Master\Models\LengthUnit;

class LengthUnitController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;

    protected $model = LengthUnit::class;
    protected string $resourcePermission = 'length-units';
    protected string $resourceName = 'length-units';
    protected string $formView = 'master::pages.lengthUnits.lengthUnitsForm';

    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }

    public function index(LengthUnitsDataTable $dataTable)
    {
        return $this->renderDataTable($dataTable);
    }

    public function show(LengthUnit $lengthUnit)
    {
        return view('master::pages.lengthUnits.show', ['resource' => $lengthUnit]);
    }

    public function create()
    {
        return $this->renderModalForm($this->formView);
    }

    public function edit(LengthUnit $lengthUnit)
    {
        return $this->renderModalForm($this->formView, $lengthUnit);
    }

    public function store(StoreLengthUnitRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            LengthUnit::create($request->validated());
        }, 'admin.lengthUnits.index','LengthUnit Created successfully.', 'Failed to create the LengthUnit.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLengthUnitRequest $request, LengthUnit $lengthUnit)
    {
        return $this->handleRequest($request, function () use ($request, $lengthUnit) {
            $lengthUnit->update($request->validated());
        }, 'admin.lengthUnits.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, LengthUnit $lengthUnit)
    {

        return $this->handleRequest($request, function () use ($lengthUnit) {
            $lengthUnit->delete();
        }, 'admin.lengthUnits.index','LengthUnit deleted successfully.', 'Failed to delete the LengthUnit.');
    }
}
