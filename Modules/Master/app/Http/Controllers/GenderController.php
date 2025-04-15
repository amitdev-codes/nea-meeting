<?php

namespace Modules\Master\Http\Controllers;

use App\Http\Controllers\BaseAdminController;
use App\Services\ResponseService;
use App\Traits\BulkDeletableTrait;
use App\Traits\HandlesExceptions;
use Illuminate\Http\Request;
use Modules\Master\DataTables\GendersDataTable;
use Modules\Master\Http\Requests\StoreGenderRequest;
use Modules\Master\Http\Requests\UpdateGenderRequest;
use Modules\Master\Models\Gender;

class GenderController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;

    protected $model = Gender::class;

    protected string $resourcePermission = 'genders';

    protected string $resourceName = 'genders';
    protected string $formView = 'master::pages.genders.gendersForm';

    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }

    public function index(GendersDataTable $dataTable)
    {
        return $this->renderDataTable($dataTable);
    }

    public function show(Gender $gender)
    {
        return view('master::pages.genders.show', ['resource' => $gender]);
    }

    public function create()
    {
        return $this->renderModalForm($this->formView);
    }

    public function edit(Gender $gender)
    {
        return $this->renderModalForm($this->formView, $gender);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGenderRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            Gender::create($request->validated());
        }, 'admin.genders.index','Gender created successfully.', 'Failed to create the Gender.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGenderRequest $request, Gender $gender)
    {
        return $this->handleRequest($request, function () use ($request, $gender) {
            $gender->update($request->validated());
        }, 'admin.genders.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Gender $gender)
    {
        return $this->handleRequest($request, function () use ($gender) {
            $gender->delete();
        }, 'admin.genders.index','Gender deleted successfully.', 'Failed to delete the Gender.');
    }
}
