<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use App\Models\CumulativeProgress;
use App\DataTables\CumulativeProgressDataTable;
use App\Http\Requests\StoreCumulativeProgressRequest;
use App\Http\Requests\UpdateCumulativeProgressRequest;
use App\Http\Controllers\BaseAdminController;

class CumulativeProgressController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    
    protected $model = CumulativeProgress::class;
    protected string $resourcePermission = 'cumulative-progress';
    protected string $resourceName = 'cumulative-progress';
    protected string $formView = 'pages.cumulative-progress.cumulativeProgressForm';
    
    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }
    
    public function index(CumulativeProgressDataTable $dataTable)
    {
        return $dataTable->render('pages.resources.index');
    }
    
    public function create(Request $request)
    {
        return $this->renderForm($this->formView);
    }
    
    public function store(StoreCumulativeProgressRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            CumulativeProgress::create($request->validated());
        }, 'admin.cumulative-progress.index', 'CumulativeProgress created successfully.', 'Failed to create the CumulativeProgress.');
    }
    
    public function show(CumulativeProgress $cumulativeProgress)
    {
        return view('pages.cumulative-progress.show', ['resource' => $cumulativeProgress]);
    }
    
    public function edit(CumulativeProgress $cumulativeProgress)
    {
        return $this->renderForm($this->formView, $cumulativeProgress);
    }
    
    public function update(UpdateCumulativeProgressRequest $request, CumulativeProgress $cumulativeProgress)
    {
        return $this->handleRequest($request, function () use ($request, $cumulativeProgress) {
            $cumulativeProgress->update($request->validated());
        }, 'admin.cumulative-progress.index', 'CumulativeProgress updated successfully.', 'Failed to update the CumulativeProgress.');
    }
    
    public function destroy(Request $request, CumulativeProgress $cumulativeProgress)
    {
        return $this->handleRequest($request, function () use ($cumulativeProgress) {
            $cumulativeProgress->delete();
        }, 'admin.cumulative-progress.index', 'CumulativeProgress deleted successfully.', 'Failed to delete the CumulativeProgress.');
    }
}