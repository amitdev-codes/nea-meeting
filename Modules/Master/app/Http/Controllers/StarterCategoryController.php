<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use Modules\Master\Models\StarterCategory;
use Modules\Master\DataTables\StarterCategoryDataTable;
use Modules\Master\Http\Requests\StoreStarterCategoryRequest;
use Modules\Master\Http\Requests\UpdateStarterCategoryRequest;
use App\Http\Controllers\BaseAdminController;

class StarterCategoryController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;

    protected $model = StarterCategory::class;
    protected string $resourcePermission = 'starter-categories';
    protected string $resourceName = 'starter-categories';
    protected string $formView = 'master::pages.starter-categories.starterCategoryForm';

    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }

    public function index(StarterCategoryDataTable $dataTable)
    {
        return $this->renderDataTable($dataTable);
    }

    public function create(Request $request)
    {
        return $this->renderModalForm($this->formView);
    }

    public function store(StoreStarterCategoryRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            StarterCategory::create($request->validated());
        }, 'admin.starter-categories.index', 'StarterCategory created successfully.', 'Failed to create the StarterCategory.');
    }

    public function show(StarterCategory $starterCategory)
    {
        return view('master::pages.starter-categories.show', ['resource' => $starterCategory]);
    }

    public function edit(StarterCategory $starterCategory)
    {
        return $this->renderModalForm($this->formView, $starterCategory);
    }

    public function update(UpdateStarterCategoryRequest $request, StarterCategory $starterCategory)
    {
        return $this->handleRequest($request, function () use ($request, $starterCategory) {
            $starterCategory->update($request->validated());
        }, 'admin.starter-categories.index', 'StarterCategory updated successfully.', 'Failed to update the StarterCategory.');
    }

    public function destroy(Request $request, StarterCategory $starterCategory)
    {
        return $this->handleRequest($request, function () use ($starterCategory) {
            $starterCategory->delete();
        }, 'admin.starter-categories.index', 'StarterCategory deleted successfully.', 'Failed to delete the StarterCategory.');
    }
}