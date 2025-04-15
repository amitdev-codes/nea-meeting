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
    protected string $resourcePermission = 'meetingrooms';
    protected string $resourceName = 'meetingrooms';
    protected string $formView = 'neameeting::pages.meetingrooms.meetingroomForm';
    
    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }
    
    public function index(MeetingRoomDataTable $dataTable)
    {
        return $this->renderDataTable($dataTable);
    }
    
    public function create(Request $request)
    {
        return $this->renderModalForm($this->formView);
    }
    
    public function store(StoreMeetingRoomRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            MeetingRoom::create($request->validated());
            if ($request->has('save_and_add_more')) {
                return redirect()->route('admin.meetingrooms.create')
                ->with('success', 'Group Member created successfully. Add another one.');
            }
        }, 'admin.meetingrooms.index', 'MeetingRoom created successfully.', 'Failed to create the MeetingRoom.');
    }
    
    public function show(MeetingRoom $meetingroom)
    {
        return view('neameeting::pages.meetingrooms.show', ['resource' => $meetingroom]);
    }
    
    public function edit(MeetingRoom $meetingroom)
    {
        return $this->renderModalForm($this->formView, $meetingroom);
    }
    
    public function update(UpdateMeetingRoomRequest $request, MeetingRoom $meetingroom)
    {
        return $this->handleRequest($request, function () use ($request, $meetingroom) {
            $meetingroom->update($request->validated());
        }, 'admin.meetingrooms.index', 'MeetingRoom updated successfully.', 'Failed to update the MeetingRoom.');
    }
    
    public function destroy(Request $request, MeetingRoom $meetingroom)
    {
        return $this->handleRequest($request, function () use ($meetingroom) {
            $meetingroom->delete();
        }, 'admin.meetingrooms.index', 'MeetingRoom deleted successfully.', 'Failed to delete the MeetingRoom.');
    }
}