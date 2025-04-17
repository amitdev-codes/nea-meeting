<?php

namespace Modules\NeaMeeting\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use Modules\NeaMeeting\Models\MeetingNotifiedUser;
use Modules\NeaMeeting\DataTables\MeetingNotifiedUserDataTable;
use Modules\NeaMeeting\Http\Requests\StoreMeetingNotifiedUserRequest;
use Modules\NeaMeeting\Http\Requests\UpdateMeetingNotifiedUserRequest;
use App\Http\Controllers\BaseAdminController;

class MeetingNotifiedUserController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;
    
    protected $model = MeetingNotifiedUser::class;
    protected string $resourcePermission = 'meetingnotifiedusers';
    protected string $resourceName = 'meetingnotifiedusers';
    protected string $formView = 'neameeting::pages.meetingnotifiedusers.meetingnotifieduserForm';
    
    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }
    
    public function index(MeetingNotifiedUserDataTable $dataTable)
    {
        return $this->renderDataTable($dataTable);
    }
    
    public function create(Request $request)
    {
        return $this->renderModalForm($this->formView);
    }
    
    public function store(StoreMeetingNotifiedUserRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            MeetingNotifiedUser::create($request->validated());
            if ($request->has('save_and_add_more')) {
                return redirect()->route('admin.meetingnotifiedusers.create')
                ->with('success', 'Group Member created successfully. Add another one.');
            }
        }, 'admin.meetingnotifiedusers.index', 'MeetingNotifiedUser created successfully.', 'Failed to create the MeetingNotifiedUser.');
    }
    
    public function show(MeetingNotifiedUser $meetingnotifieduser)
    {
        return view('neameeting::pages.meetingnotifiedusers.show', ['resource' => $meetingnotifieduser]);
    }
    
    public function edit(MeetingNotifiedUser $meetingnotifieduser)
    {
        return $this->renderModalForm($this->formView, $meetingnotifieduser);
    }
    
    public function update(UpdateMeetingNotifiedUserRequest $request, MeetingNotifiedUser $meetingnotifieduser)
    {
        return $this->handleRequest($request, function () use ($request, $meetingnotifieduser) {
            $meetingnotifieduser->update($request->validated());
        }, 'admin.meetingnotifiedusers.index', 'MeetingNotifiedUser updated successfully.', 'Failed to update the MeetingNotifiedUser.');
    }
    
    public function destroy(Request $request, MeetingNotifiedUser $meetingnotifieduser)
    {
        return $this->handleRequest($request, function () use ($meetingnotifieduser) {
            $meetingnotifieduser->delete();
        }, 'admin.meetingnotifiedusers.index', 'MeetingNotifiedUser deleted successfully.', 'Failed to delete the MeetingNotifiedUser.');
    }
}