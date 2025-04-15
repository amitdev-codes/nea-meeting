<?php

namespace Modules\Master\Http\Controllers;

use App\Http\Controllers\BaseAdminController;
use App\Services\ResponseService;
use App\Traits\BulkDeletableTrait;
use App\Traits\HandlesExceptions;
use Illuminate\Http\Request;
use Modules\Master\DataTables\FiscalYearsDataTable;
use Modules\Master\Http\Requests\StoreFiscalYearRequest;
use Modules\Master\Http\Requests\UpdateFiscalYearRequest;
use Modules\Master\Models\FiscalYear;

class FiscalYearController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;

    protected $model = FiscalYear::class;
    protected string $resourcePermission = 'fiscal-years';
    protected string $resourceName = 'fiscal-years';
    protected string $formView = 'master::pages.fiscalYears.fiscalYearForm';

    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }

    public function index(FiscalYearsDataTable $dataTable)
    {


        return $dataTable->render('pages.resources.index');
    }

    public function show(FiscalYear $fiscalYear)
    {


        return view('master::pages.fiscalYears.show', ['resource' => $fiscalYear]);
    }

    public function create()
    {
        return $this->renderForm($this->formView, null);
    }

    public function edit(FiscalYear $fiscalYear)
    {
        return $this->renderForm($this->formView, $fiscalYear);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFiscalYearRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            FiscalYear::create($request->validated());
        }, 'admin.fiscalYears.index','Fiscal Year Created successfully.', 'Failed to create the Fiscal Year.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFiscalYearRequest $request, FiscalYear $fiscalYear)
    {
        return $this->handleRequest($request, function () use ($request, $fiscalYear) {
            $fiscalYear->update($request->validated());
        }, 'admin.fiscalYears.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, FiscalYear $fiscalYear)
    {
        return $this->handleRequest($request, function () use ($fiscalYear) {
            $fiscalYear->delete();
        }, 'admin.fiscalYears.index','Fiscal Year deleted successfully.', 'Failed to delete the Fiscal Year.');
    }
}
