<?php

namespace Modules\NeaMeeting\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use Modules\NeaMeeting\Models\MeetingRecurrence;
use Modules\NeaMeeting\DataTables\MeetingRecurrenceDataTable;
use Modules\NeaMeeting\Http\Requests\StoreMeetingRecurrenceRequest;
use Modules\NeaMeeting\Http\Requests\UpdateMeetingRecurrenceRequest;
use App\Http\Controllers\BaseAdminController;

class MeetingRecurrenceController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;
    
    protected $model = MeetingRecurrence::class;
    protected string $resourcePermission = 'meetingrecurrences';
    protected string $resourceName = 'meetingrecurrences';
    protected string $formView = 'neameeting::pages.meetingrecurrences.meetingrecurrenceForm';
    
    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }
    
    public function index(MeetingRecurrenceDataTable $dataTable)
    {
        return $this->renderDataTable($dataTable);
    }
    
    public function create(Request $request)
    {
        return $this->renderModalForm($this->formView);
    }
    
    public function store(StoreMeetingRecurrenceRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            MeetingRecurrence::create($request->validated());
            if ($request->has('save_and_add_more')) {
                return redirect()->route('admin.meetingrecurrences.create')
                ->with('success', 'Group Member created successfully. Add another one.');
            }
        }, 'admin.meetingrecurrences.index', 'MeetingRecurrence created successfully.', 'Failed to create the MeetingRecurrence.');
    }
    
    public function show(MeetingRecurrence $meetingrecurrence)
    {
        return view('neameeting::pages.meetingrecurrences.show', ['resource' => $meetingrecurrence]);
    }
    
    public function edit(MeetingRecurrence $meetingrecurrence)
    {
        return $this->renderModalForm($this->formView, $meetingrecurrence);
    }
    
    public function update(UpdateMeetingRecurrenceRequest $request, MeetingRecurrence $meetingrecurrence)
    {
        return $this->handleRequest($request, function () use ($request, $meetingrecurrence) {
            $meetingrecurrence->update($request->validated());
        }, 'admin.meetingrecurrences.index', 'MeetingRecurrence updated successfully.', 'Failed to update the MeetingRecurrence.');
    }
    
    public function destroy(Request $request, MeetingRecurrence $meetingrecurrence)
    {
        return $this->handleRequest($request, function () use ($meetingrecurrence) {
            $meetingrecurrence->delete();
        }, 'admin.meetingrecurrences.index', 'MeetingRecurrence deleted successfully.', 'Failed to delete the MeetingRecurrence.');
    }
}