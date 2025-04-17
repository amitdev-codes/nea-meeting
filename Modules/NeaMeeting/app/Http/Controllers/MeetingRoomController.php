<?php

namespace Modules\NeaMeeting\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use Modules\NeaMeeting\Models\MeetingRoom;
use Modules\NeaMeeting\DataTables\MeetingRoomDataTable;
use Modules\NeaMeeting\Http\Requests\StoreMeetingRoomRequest;
use Modules\NeaMeeting\Http\Requests\UpdateMeetingRoomRequest;
use App\Http\Controllers\BaseAdminController;

class MeetingRoomController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;
    
    protected $model = MeetingRoom::class;
    protected string $resourcePermission = 'meeting-rooms';
    protected string $resourceName = 'meeting-rooms';
    protected string $formView = 'neameeting::pages.meetingrooms.meetingroomForm';
    
    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }
    
    public function index(MeetingRoomDataTable $dataTable)
    {
        return $dataTable->render('pages.resources.index');
    }
    
    public function create(Request $request)
    {
        return $this->renderForm($this->formView);
    }
    
    public function store(StoreMeetingRoomRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            MeetingRoom::create($request->validated());
            if ($request->has('save_and_add_more')) {
                return redirect()->route('admin.meeting-rooms.create')
                ->with('success', 'Group Member created successfully. Add another one.');
            }
        }, 'admin.meeting-rooms.index', 'MeetingRoom created successfully.', 'Failed to create the MeetingRoom.');
    }
    
    public function show(MeetingRoom $meetingRoom)
    {
        return view('neameeting::pages.meeting-rooms.show', ['resource' => $meetingRoom]);
    }
    
    public function edit(MeetingRoom $meetingRoom)
    {
        return $this->renderForm($this->formView, $meetingRoom);
    }
    
    public function update(UpdateMeetingRoomRequest $request, MeetingRoom $meetingRoom)
    {
        return $this->handleRequest($request, function () use ($request, $meetingRoom) {
            $meetingRoom->update($request->validated());
        }, 'admin.meeting-rooms.index', 'MeetingRoom updated successfully.', 'Failed to update the MeetingRoom.');
    }
    
    public function destroy(Request $request, MeetingRoom $meetingRoom)
    {
        return $this->handleRequest($request, function () use ($meetingRoom) {
            $meetingRoom->delete();
        }, 'admin.meeting-rooms.index', 'MeetingRoom deleted successfully.', 'Failed to delete the MeetingRoom.');
    }
}