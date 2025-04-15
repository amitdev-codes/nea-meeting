<?php

namespace Modules\Landingpage\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use Modules\Landingpage\Models\LandingPageMenu;
use Modules\Landingpage\DataTables\LandingPageMenuDataTable;
use Modules\Landingpage\Http\Requests\StoreLandingPageMenuRequest;
use Modules\Landingpage\Http\Requests\UpdateLandingPageMenuRequest;
use App\Http\Controllers\BaseAdminController;

class LandingPageMenuController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;
    
    protected $model = LandingPageMenu::class;
    protected string $resourcePermission = 'landing-page-menus';
    protected string $resourceName = 'landing-page-menus';
    protected string $formView = 'landingpage::pages.landing-page-menus.landingPageMenuForm';
    
    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }
    
    public function index(LandingPageMenuDataTable $dataTable)
    {
        return $this->renderDataTable($dataTable);
    }
    
    public function create(Request $request)
    {
        return $this->renderForm($this->formView);
    }
    
    public function store(StoreLandingPageMenuRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            LandingPageMenu::create($request->validated());
            if ($request->has('save_and_add_more')) {
                return redirect()->route('admin.landing-page-menus.create')
                ->with('success', 'Group Member created successfully. Add another one.');
            }
        }, 'admin.landing-page-menus.index', 'LandingPageMenu created successfully.', 'Failed to create the LandingPageMenu.');
    }
    
    public function show(LandingPageMenu $landingPageMenu)
    {
        return view('landingpage::pages.landing-page-menus.show', ['resource' => $landingPageMenu]);
    }
    
    public function edit(LandingPageMenu $landingPageMenu)
    {
        return $this->renderForm($this->formView, $landingPageMenu);
    }
    
    public function update(UpdateLandingPageMenuRequest $request, LandingPageMenu $landingPageMenu)
    {
        return $this->handleRequest($request, function () use ($request, $landingPageMenu) {
            $landingPageMenu->update($request->validated());
        }, 'admin.landing-page-menus.index', 'LandingPageMenu updated successfully.', 'Failed to update the LandingPageMenu.');
    }
    
    public function destroy(Request $request, LandingPageMenu $landingPageMenu)
    {
        return $this->handleRequest($request, function () use ($landingPageMenu) {
            $landingPageMenu->delete();
        }, 'admin.landing-page-menus.index', 'LandingPageMenu deleted successfully.', 'Failed to delete the LandingPageMenu.');
    }
}