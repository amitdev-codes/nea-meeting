<?php

namespace Modules\NeaMeeting\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use Modules\NeaMeeting\Models\MeetingReminder;
use Modules\NeaMeeting\DataTables\MeetingReminderDataTable;
use Modules\NeaMeeting\Http\Requests\StoreMeetingReminderRequest;
use Modules\NeaMeeting\Http\Requests\UpdateMeetingReminderRequest;
use App\Http\Controllers\BaseAdminController;

class MeetingReminderController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;
    
    protected $model = MeetingReminder::class;
    protected string $resourcePermission = 'meetingreminders';
    protected string $resourceName = 'meetingreminders';
    protected string $formView = 'neameeting::pages.meetingreminders.meetingreminderForm';
    
    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }
    
    public function index(MeetingReminderDataTable $dataTable)
    {
        return $this->renderDataTable($dataTable);
    }
    
    public function create(Request $request)
    {
        return $this->renderModalForm($this->formView);
    }
    
    public function store(StoreMeetingReminderRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            MeetingReminder::create($request->validated());
            if ($request->has('save_and_add_more')) {
                return redirect()->route('admin.meetingreminders.create')
                ->with('success', 'Group Member created successfully. Add another one.');
            }
        }, 'admin.meetingreminders.index', 'MeetingReminder created successfully.', 'Failed to create the MeetingReminder.');
    }
    
    public function show(MeetingReminder $meetingreminder)
    {
        return view('neameeting::pages.meetingreminders.show', ['resource' => $meetingreminder]);
    }
    
    public function edit(MeetingReminder $meetingreminder)
    {
        return $this->renderModalForm($this->formView, $meetingreminder);
    }
    
    public function update(UpdateMeetingReminderRequest $request, MeetingReminder $meetingreminder)
    {
        return $this->handleRequest($request, function () use ($request, $meetingreminder) {
            $meetingreminder->update($request->validated());
        }, 'admin.meetingreminders.index', 'MeetingReminder updated successfully.', 'Failed to update the MeetingReminder.');
    }
    
    public function destroy(Request $request, MeetingReminder $meetingreminder)
    {
        return $this->handleRequest($request, function () use ($meetingreminder) {
            $meetingreminder->delete();
        }, 'admin.meetingreminders.index', 'MeetingReminder deleted successfully.', 'Failed to delete the MeetingReminder.');
    }
}