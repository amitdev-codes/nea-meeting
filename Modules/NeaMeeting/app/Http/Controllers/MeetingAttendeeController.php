<?php

namespace Modules\NeaMeeting\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use Modules\NeaMeeting\Models\MeetingAttendee;
use Modules\NeaMeeting\DataTables\MeetingAttendeeDataTable;
use Modules\NeaMeeting\Http\Requests\StoreMeetingAttendeeRequest;
use Modules\NeaMeeting\Http\Requests\UpdateMeetingAttendeeRequest;
use App\Http\Controllers\BaseAdminController;

class MeetingAttendeeController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;
    
    protected $model = MeetingAttendee::class;
    protected string $resourcePermission = 'meeting-attendees';
    protected string $resourceName = 'meeting-attendees';
    protected string $formView = 'neameeting::pages.meeting-attendees.meetingattendeeForm';
    
    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }
    
    public function index(MeetingAttendeeDataTable $dataTable)
    {
        return $dataTable->render('pages.resources.index');
    }
    
    public function create(Request $request)
    {
        return $this->renderForm($this->formView);
    }
    
    public function store(StoreMeetingAttendeeRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            MeetingAttendee::create($request->validated());
            if ($request->has('save_and_add_more')) {
                return redirect()->route('admin.meeting-attendees.create')
                ->with('success', 'Group Member created successfully. Add another one.');
            }
        }, 'admin.meeting-attendees.index', 'MeetingAttendee created successfully.', 'Failed to create the MeetingAttendee.');
    }
    
    public function show(MeetingAttendee $meetingattendee)
    {
        return view('neameeting::pages.meeting-attendees.show', ['resource' => $meetingattendee]);
    }
    
    public function edit(MeetingAttendee $meetingattendee)
    {
        return $this->renderForm($this->formView, $meetingattendee);
    }
    
    public function update(UpdateMeetingAttendeeRequest $request, MeetingAttendee $meetingattendee)
    {
        return $this->handleRequest($request, function () use ($request, $meetingattendee) {
            $meetingattendee->update($request->validated());
        }, 'admin.meeting-attendees.index', 'MeetingAttendee updated successfully.', 'Failed to update the MeetingAttendee.');
    }
    
    public function destroy(Request $request, MeetingAttendee $meetingattendee)
    {
        return $this->handleRequest($request, function () use ($meetingattendee) {
            $meetingattendee->delete();
        }, 'admin.meeting-attendees.index', 'MeetingAttendee deleted successfully.', 'Failed to delete the MeetingAttendee.');
    }
}