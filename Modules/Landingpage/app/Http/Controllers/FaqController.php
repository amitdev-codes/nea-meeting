<?php

namespace Modules\Landingpage\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use Modules\Landingpage\Models\Faq;
use Modules\Landingpage\DataTables\FaqDataTable;
use Modules\Landingpage\Http\Requests\StoreFaqRequest;
use Modules\Landingpage\Http\Requests\UpdateFaqRequest;
use App\Http\Controllers\BaseAdminController;

class FaqController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;
    
    protected $model = Faq::class;
    protected string $resourcePermission = 'faqs';
    protected string $resourceName = 'faqs';
    protected string $formView = 'landingpage::pages.faqs.faqForm';
    
    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }
    
    public function index(FaqDataTable $dataTable)
    {
        return $this->renderDataTable($dataTable);
    }
    
    public function create(Request $request)
    {
        return $this->renderModalForm($this->formView);
    }
    
    public function store(StoreFaqRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            Faq::create($request->validated());
            if ($request->has('save_and_add_more')) {
                return redirect()->route('admin.faqs.create')
                ->with('success', 'Group Member created successfully. Add another one.');
            }
        }, 'admin.faqs.index', 'Faq created successfully.', 'Failed to create the Faq.');
    }
    
    public function show(Faq $faq)
    {
        return view('landingpage::pages.faqs.show', ['resource' => $faq]);
    }
    
    public function edit(Faq $faq)
    {
        return $this->renderModalForm($this->formView, $faq);
    }
    
    public function update(UpdateFaqRequest $request, Faq $faq)
    {
        return $this->handleRequest($request, function () use ($request, $faq) {
            $faq->update($request->validated());
        }, 'admin.faqs.index', 'Faq updated successfully.', 'Failed to update the Faq.');
    }
    
    public function destroy(Request $request, Faq $faq)
    {
        return $this->handleRequest($request, function () use ($faq) {
            $faq->delete();
        }, 'admin.faqs.index', 'Faq deleted successfully.', 'Failed to delete the Faq.');
    }
}