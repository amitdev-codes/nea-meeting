<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use App\Models\SSBeneficiariesForm;
use App\DataTables\SSBeneficiariesFormDataTable;
use App\Http\Requests\StoreSSBeneficiariesFormRequest;
use App\Http\Requests\UpdateSSBeneficiariesFormRequest;
use App\Http\Controllers\BaseAdminController;

class SSBeneficiariesFormController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;
    
    protected $model = SSBeneficiariesForm::class;
    protected string $resourcePermission = 'ssbeneficiariesforms';
    protected string $resourceName = 'ssbeneficiariesforms';
    protected string $formView = '::pages.ssbeneficiariesforms.ssbeneficiariesformForm';
    
    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }
    
    public function index(SSBeneficiariesFormDataTable $dataTable)
    {
        return $this->renderDataTable($dataTable);
    }
    
    public function create(Request $request)
    {
        return $this->renderModalForm($this->formView);
    }
    
    public function store(StoreSSBeneficiariesFormRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            SSBeneficiariesForm::create($request->validated());
            if ($request->has('save_and_add_more')) {
                return redirect()->route('admin.ssbeneficiariesforms.create')
                ->with('success', 'Group Member created successfully. Add another one.');
            }
        }, 'admin.ssbeneficiariesforms.index', 'SSBeneficiariesForm created successfully.', 'Failed to create the SSBeneficiariesForm.');
    }
    
    public function show(SSBeneficiariesForm $ssbeneficiariesform)
    {
        return view('::pages.ssbeneficiariesforms.show', ['resource' => $ssbeneficiariesform]);
    }
    
    public function edit(SSBeneficiariesForm $ssbeneficiariesform)
    {
        return $this->renderModalForm($this->formView, $ssbeneficiariesform);
    }
    
    public function update(UpdateSSBeneficiariesFormRequest $request, SSBeneficiariesForm $ssbeneficiariesform)
    {
        return $this->handleRequest($request, function () use ($request, $ssbeneficiariesform) {
            $ssbeneficiariesform->update($request->validated());
        }, 'admin.ssbeneficiariesforms.index', 'SSBeneficiariesForm updated successfully.', 'Failed to update the SSBeneficiariesForm.');
    }
    
    public function destroy(Request $request, SSBeneficiariesForm $ssbeneficiariesform)
    {
        return $this->handleRequest($request, function () use ($ssbeneficiariesform) {
            $ssbeneficiariesform->delete();
        }, 'admin.ssbeneficiariesforms.index', 'SSBeneficiariesForm deleted successfully.', 'Failed to delete the SSBeneficiariesForm.');
    }
}