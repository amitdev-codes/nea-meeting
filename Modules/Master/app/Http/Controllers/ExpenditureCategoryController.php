<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use Modules\Master\Models\ExpenditureCategory;
use Modules\Master\DataTables\ExpenditureCategoryDataTable;
use Modules\Master\Http\Requests\StoreExpenditureCategoryRequest;
use Modules\Master\Http\Requests\UpdateExpenditureCategoryRequest;
use App\Http\Controllers\BaseAdminController;

class ExpenditureCategoryController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;

    protected $model = ExpenditureCategory::class;
    protected string $resourcePermission = 'expenditure-categories';
    protected string $resourceName = 'expenditure-categories';
    protected string $formView = 'master::pages.expenditure-categories.expenditureCategoryForm';

    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }

    public function index(ExpenditureCategoryDataTable $dataTable)
    {
        return $this->renderDataTable($dataTable);
    }

    public function create(Request $request)
    {
        return $this->renderModalForm($this->formView);
    }

    public function store(StoreExpenditureCategoryRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            ExpenditureCategory::create($request->validated());
        }, 'admin.expenditure-categories.index', 'ExpenditureCategory created successfully.', 'Failed to create the ExpenditureCategory.');
    }

    public function show(ExpenditureCategory $expenditureCategory)
    {
        return view('master::pages.expenditure-categories.show', ['resource' => $expenditureCategory]);
    }

    public function edit(ExpenditureCategory $expenditureCategory)
    {
        return $this->renderModalForm($this->formView, $expenditureCategory);
    }

    public function update(UpdateExpenditureCategoryRequest $request, ExpenditureCategory $expenditureCategory)
    {
        return $this->handleRequest($request, function () use ($request, $expenditureCategory) {
            $expenditureCategory->update($request->validated());
        }, 'admin.expenditure-categories.index', 'ExpenditureCategory updated successfully.', 'Failed to update the ExpenditureCategory.');
    }

    public function destroy(Request $request, ExpenditureCategory $expenditureCategory)
    {
        return $this->handleRequest($request, function () use ($expenditureCategory) {
            $expenditureCategory->delete();
        }, 'admin.expenditure-categories.index', 'ExpenditureCategory deleted successfully.', 'Failed to delete the ExpenditureCategory.');
    }
}