<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Modules\Master\Models\Province;
use Modules\Master\Imports\ProvinceImport;
use App\Http\Controllers\BaseAdminController;
use Modules\Master\DataTables\ProvincesDataTable;
use Modules\Master\Http\Requests\StoreProvinceRequest;
use Modules\Master\Http\Requests\UpdateProvinceRequest;

class ProvinceController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;

    protected $model = Province::class;
    protected string $resourcePermission = 'provinces';
    protected string $resourceName = 'provinces';
    protected string $formView = 'master::pages.provinces.provinceForm';

    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }

    public function index(ProvincesDataTable $dataTable)
    {
        return $this->renderDataTable($dataTable);
    }

    public function show(Province $province)
    {
        return view('master::pages.provinces.show', ['resource' => $province]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->renderModalForm($this->formView);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProvinceRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            Province::create($request->validated());
        },'admin.provinces.index','Province Created successfully.', 'Failed to create the province.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Province $province)
    {
        return $this->renderModalForm($this->formView, $province);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProvinceRequest $request, Province $province)
    {
        return $this->handleRequest($request, function () use ($request, $province) {
            $province->update($request->validated());
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Province $province)
    {
        return $this->handleRequest($request,function () use ($province) {
                $province->delete();
            },'admin.provinces.index','Province deleted successfully.', 'Failed to delete the province.'
        );
    }

    //inline edit
    public function inlineEdit(Request $request, Province $province)
    {
        $allowedFields = ['code', 'name', 'name_np'];
        $validationRules = [
            'code' => 'required|string|max:10',
            'name' => 'required|string|max:255',
            'name_np' => 'required|string|max:255',
        ];
        
        return $this->handleInlineEdit($request, $province, $allowedFields, $validationRules);
    }

    public function import(Request $request)
    {
        $storeRequest = new StoreProvinceRequest();
        $rules = $storeRequest->rules();
        unset($rules['code']);
        $rules['code'] = 'required|max:10';
        return $this->handleImport($request, 'code', $rules, 'admin.provinces.index');
    }

}