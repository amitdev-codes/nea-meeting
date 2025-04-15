<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use Modules\Master\Models\District;
use Modules\Master\Models\Province;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\BaseAdminController;
use Modules\Master\DataTables\DistrictsDataTable;
use Modules\Master\Http\Requests\StoreDistrictRequest;
use Modules\Master\Http\Requests\UpdateDistrictRequest;

class DistrictController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;

    protected $model = District::class;
    protected string $resourcePermission = 'districts';
    protected string $resourceName = 'districts';
    protected string $formView = 'master::pages.districts.districtsForm';

    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }

    public function index(DistrictsDataTable $dataTable)
    {
        return $this->renderDataTable($dataTable);
    }

    public function show(District $district)
    {
        return view('master::pages.districts.show', ['resource' => $district]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return $this->renderModalForm($this->formView);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDistrictRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            District::create($request->validated());
        }, 'admin.districts.index','District Created successfully.', 'Failed to create the District.');
    }

    /**
     * Show the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(District $district)
    {
        return $this->renderModalForm($this->formView,$district);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDistrictRequest $request, District $district)
    {
        return $this->handleRequest($request, function () use ($request, $district) {
            $district->update($request->validated());
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, District $district)
    {
        return $this->handleRequest($request, function () use ($district) {
            $district->delete();
        },'admin.districts.index','District deleted successfully.', 'Failed to delete the district.'
     );
    }

    public function import(Request $request)
    {
        $storeRequest = new StoreDistrictRequest();
        $rules = $storeRequest->rules();
        unset($rules['code']);
        $rules['code'] = 'required|max:10';// Simplified rule for import
        return $this->handleImport($request, 'code', $rules, 'admin.provinces.index');
    }

}
