<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use Modules\Master\Models\Caste;
use Modules\Master\DataTables\CasteDataTable;
use Modules\Master\Http\Requests\StoreCasteRequest;
use Modules\Master\Http\Requests\UpdateCasteRequest;
use App\Http\Controllers\BaseAdminController;

class CasteController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;

    protected $model = Caste::class;
    protected string $resourcePermission = 'castes';
    protected string $resourceName = 'castes';
    protected string $formView = 'master::pages.castes.casteForm';

    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }

    public function index(CasteDataTable $dataTable)
    {
        return $this->renderDataTable($dataTable);
    }

    public function create(Request $request)
    {
        return $this->renderModalForm($this->formView);
    }

    public function store(StoreCasteRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            Caste::create($request->validated());
        }, 'admin.castes.index', 'Caste created successfully.', 'Failed to create the Caste.');
    }

    public function show(Caste $caste)
    {
        return view('master::pages.castes.show', ['resource' => $caste]);
    }

    public function edit(Caste $caste)
    {
        return $this->renderModalForm($this->formView, $caste);
    }

    public function update(UpdateCasteRequest $request, Caste $caste)
    {
        return $this->handleRequest($request, function () use ($request, $caste) {
            $caste->update($request->validated());
        }, 'admin.castes.index', 'Caste updated successfully.', 'Failed to update the Caste.');
    }

    public function destroy(Request $request, Caste $caste)
    {
        return $this->handleRequest($request, function () use ($caste) {
            $caste->delete();
        }, 'admin.castes.index', 'Caste deleted successfully.', 'Failed to delete the Caste.');
    }
}