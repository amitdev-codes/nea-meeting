<?php

namespace Modules\NeaMeeting\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use Modules\NeaMeeting\Models\MeetingMinute;
use Modules\NeaMeeting\DataTables\MeetingMinuteDataTable;
use Modules\NeaMeeting\Http\Requests\StoreMeetingMinuteRequest;
use Modules\NeaMeeting\Http\Requests\UpdateMeetingMinuteRequest;
use App\Http\Controllers\BaseAdminController;

class MeetingMinuteController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;
    
    protected $model = MeetingMinute::class;
    protected string $resourcePermission = 'meetingminutes';
    protected string $resourceName = 'meetingminutes';
    protected string $formView = 'neameeting::pages.meetingminutes.meetingminuteForm';
    
    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }
    
    public function index(MeetingMinuteDataTable $dataTable)
    {
        return $this->renderDataTable($dataTable);
    }
    
    public function create(Request $request)
    {
        return $this->renderModalForm($this->formView);
    }
    
    public function store(StoreMeetingMinuteRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            MeetingMinute::create($request->validated());
            if ($request->has('save_and_add_more')) {
                return redirect()->route('admin.meetingminutes.create')
                ->with('success', 'Group Member created successfully. Add another one.');
            }
        }, 'admin.meetingminutes.index', 'MeetingMinute created successfully.', 'Failed to create the MeetingMinute.');
    }
    
    public function show(MeetingMinute $meetingminute)
    {
        return view('neameeting::pages.meetingminutes.show', ['resource' => $meetingminute]);
    }
    
    public function edit(MeetingMinute $meetingminute)
    {
        return $this->renderModalForm($this->formView, $meetingminute);
    }
    
    public function update(UpdateMeetingMinuteRequest $request, MeetingMinute $meetingminute)
    {
        return $this->handleRequest($request, function () use ($request, $meetingminute) {
            $meetingminute->update($request->validated());
        }, 'admin.meetingminutes.index', 'MeetingMinute updated successfully.', 'Failed to update the MeetingMinute.');
    }
    
    public function destroy(Request $request, MeetingMinute $meetingminute)
    {
        return $this->handleRequest($request, function () use ($meetingminute) {
            $meetingminute->delete();
        }, 'admin.meetingminutes.index', 'MeetingMinute deleted successfully.', 'Failed to delete the MeetingMinute.');
    }
}