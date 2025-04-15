<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use Modules\Master\Models\Section;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\BaseAdminController;
use Modules\Master\DataTables\SpecialistDataTable;
use Modules\Master\Http\Requests\StoreSpecialistRequest;
use Modules\Master\Http\Requests\UpdateSpecialistRequest;

class SectionController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;
    
    protected $model = Section::class;
    protected string $resourcePermission = 'sections';
    protected string $resourceName = 'sections';
    protected string $formView = 'master::pages.sections.sectionForm';
    
    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }
    
    public function index(SectionDataTable $dataTable)
    {
        return $this->renderDataTable($dataTable);
    }
    
    public function create(Request $request)
    {
        return $this->renderModalForm($this->formView);
    }
    
    public function store(StoreSectionRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            Section::create($request->validated());
        }, 'admin.sections.index', 'Section created successfully.', 'Failed to create the Section.');
    }
    
    public function show(Section $section)
    {
        return view('master::pages.show', ['resource' => $section]);
    }
    
    public function edit(Section $section)
    {
        return $this->renderModalForm($this->formView, $section);
    }
    
    public function update(UpdateSectionRequest $request, Section $section)
    {
        return $this->handleRequest($request, function () use ($request, $section) {
            $section->update($request->validated());
        }, 'admin.sections.index', 'Section updated successfully.', 'Failed to update the Section.');
    }
    
    public function destroy(Request $request, Section $section)
    {
        return $this->handleRequest($request, function () use ($section) {
            $section->delete();
        }, 'admin.sections.index', 'Section deleted successfully.', 'Failed to delete the Section.');
    }
}