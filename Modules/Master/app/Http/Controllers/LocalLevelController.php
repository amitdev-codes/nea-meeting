<?php

namespace Modules\Master\Http\Controllers;

use App\Http\Controllers\BaseAdminController;
use App\Services\ResponseService;
use App\Traits\BulkDeletableTrait;
use App\Traits\HandlesExceptions;
use Illuminate\Http\Request;
use Modules\Master\DataTables\LocalLevelsDataTable;
use Modules\Master\Http\Requests\StoreLocalLevelRequest;
use Modules\Master\Http\Requests\UpdateLocalLevelRequest;
use Modules\Master\Models\District;
use Modules\Master\Models\LocalLevel;

class LocalLevelController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;

    protected $model = LocalLevel::class;
    protected string $resourcePermission = 'local-levels';
    protected string $resourceName = 'local-levels';
    protected string $formView = 'master::pages.localLevels.localLevelsForm';

    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }

    public function index(LocalLevelsDataTable $dataTable)
    {
        return $this->renderDataTable($dataTable);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function show(LocalLevel $localLevel)
    {
        return view('master::pages.localLevels.show', ['resource' => $localLevel]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->renderModalForm($this->formView);
    }

    public function store(StoreLocalLevelRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            LocalLevel::create($request->validated());
        }, 'admin.local-levels.index','Local level Created successfully.', 'Failed to create the Local level.');
    }

    public function edit(LocalLevel $localLevel)
    {
        return $this->renderModalForm($this->formView, $localLevel);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLocalLevelRequest $request, LocalLevel $localLevel)
    {
        return $this->handleRequest($request, function () use ($request, $localLevel) {
            $localLevel->update($request->validated());
        }, 'admin.local-levels.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, LocalLevel $localLevel)
    {

        return $this->handleRequest($request, function () use ($localLevel) {
            $localLevel->delete();
        }, 'admin.local-levels.index','Local level deleted successfully.', 'Failed to delete the Local level.');
    }
    public function import(Request $request)
    {
        $storeRequest = new StoreLocalLevelRequest();
        $rules = $storeRequest->rules();
        unset($rules['code']);
        $rules['code'] = 'required|max:10';// Simplified rule for import
        return $this->handleImport($request, 'code', $rules, 'admin.local-levels.index');
    }
}
