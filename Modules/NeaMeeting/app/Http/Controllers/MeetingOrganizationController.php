<?php

namespace Modules\NeaMeeting\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use Modules\NeaMeeting\Models\MeetingOrganization;
use Modules\NeaMeeting\DataTables\MeetingOrganizationDataTable;
use Modules\NeaMeeting\Http\Requests\StoreMeetingOrganizationRequest;
use Modules\NeaMeeting\Http\Requests\UpdateMeetingOrganizationRequest;
use App\Http\Controllers\BaseAdminController;

class MeetingOrganizationController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;
    
    protected $model = MeetingOrganization::class;
    protected string $resourcePermission = 'meetingorganizations';
    protected string $resourceName = 'meetingorganizations';
    protected string $formView = 'neameeting::pages.meetingorganizations.meetingorganizationForm';
    
    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }
    
    public function index(MeetingOrganizationDataTable $dataTable)
    {
        return $this->renderDataTable($dataTable);
    }
    
    public function create(Request $request)
    {
        return $this->renderModalForm($this->formView);
    }
    
    public function store(StoreMeetingOrganizationRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            MeetingOrganization::create($request->validated());
            if ($request->has('save_and_add_more')) {
                return redirect()->route('admin.meetingorganizations.create')
                ->with('success', 'Group Member created successfully. Add another one.');
            }
        }, 'admin.meetingorganizations.index', 'MeetingOrganization created successfully.', 'Failed to create the MeetingOrganization.');
    }
    
    public function show(MeetingOrganization $meetingorganization)
    {
        return view('neameeting::pages.meetingorganizations.show', ['resource' => $meetingorganization]);
    }
    
    public function edit(MeetingOrganization $meetingorganization)
    {
        return $this->renderModalForm($this->formView, $meetingorganization);
    }
    
    public function update(UpdateMeetingOrganizationRequest $request, MeetingOrganization $meetingorganization)
    {
        return $this->handleRequest($request, function () use ($request, $meetingorganization) {
            $meetingorganization->update($request->validated());
        }, 'admin.meetingorganizations.index', 'MeetingOrganization updated successfully.', 'Failed to update the MeetingOrganization.');
    }
    
    public function destroy(Request $request, MeetingOrganization $meetingorganization)
    {
        return $this->handleRequest($request, function () use ($meetingorganization) {
            $meetingorganization->delete();
        }, 'admin.meetingorganizations.index', 'MeetingOrganization deleted successfully.', 'Failed to delete the MeetingOrganization.');
    }
}