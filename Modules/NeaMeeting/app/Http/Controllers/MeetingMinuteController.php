<?php

namespace Modules\NeaMeeting\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use Modules\NeaMeeting\Models\Meeting;
use Modules\NeaMeeting\Models\MeetingMinute;
use App\Http\Controllers\BaseAdminController;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Modules\NeaMeeting\DataTables\MeetingMinuteDataTable;
use Modules\NeaMeeting\Http\Requests\StoreMeetingMinuteRequest;
use Modules\NeaMeeting\Http\Requests\UpdateMeetingMinuteRequest;

class MeetingMinuteController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;
    
    protected $model = MeetingMinute::class;
    protected string $resourcePermission = 'meeting-minutes';
    protected string $resourceName = 'meeting-minutes';
    protected string $formView = 'neameeting::pages.meetingminutes.meetingminuteForm';
    
    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }
    
    public function index(MeetingMinuteDataTable $dataTable)
    {
        return $dataTable->render('pages.resources.index');
    }
    
    public function create(Request $request)
    {
        return $this->renderForm($this->formView, null,['meetings' => Meeting::all(),'users' => User::all()]);
    }
    
    public function store(StoreMeetingMinuteRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            $meetingMinute=MeetingMinute::create($request->validated());
            if ($request->hasFile('minutes')) {
                $meetingMinute->addMedia($request->file('minutes'))->toMediaCollection('MeetingMinute');
            }
            
            if ($request->has('save_and_add_more')) {
                return redirect()->route('admin.meeting-minutes.create')
                ->with('success', 'Group Member created successfully. Add another one.');
            }
        }, 'admin.meeting-minutes.index', 'MeetingMinute created successfully.', 'Failed to create the MeetingMinute.');
    }
    
    public function show(MeetingMinute $meetingMinute)
    {
        return view('neameeting::pages.meeting-minutes.show', ['resource' => $meetingMinute]);
    }
    
    public function edit(MeetingMinute $meetingMinute)
    {
        $meetingMinute->load(['media' => function($query) {
            $query->where('collection_name', 'MeetingMinute');
        }]);
        return $this->renderForm($this->formView, $meetingMinute,['meetings' => Meeting::all(),'users' => User::all()]);
    }
    
    public function update(UpdateMeetingMinuteRequest $request, MeetingMinute $meetingMinute)
    {
        return $this->handleRequest($request, function () use ($request, $meetingMinute) {
            $meetingMinute->update($request->validated());
        }, 'admin.meeting-minutes.index', 'MeetingMinute updated successfully.', 'Failed to update the MeetingMinute.');
    }
    
    public function destroy(Request $request, MeetingMinute $meetingMinute)
    {
        return $this->handleRequest($request, function () use ($meetingMinute) {
            $meetingMinute->delete();
        }, 'admin.meeting-minutes.index', 'MeetingMinute deleted successfully.', 'Failed to delete the MeetingMinute.');
    }
    private function handleMediaUploads(Request $request, Meeting $meeting)
    {
        // Handle media deletion requests
        if ($request->has('minutes')) {
            foreach ($request->input('minutes', []) as $mediaId) {
                $media = Media::find($mediaId);
                if ($media && $media->model_id == $meeting->id) {
                    $media->delete();
                }
            }
        }

        // Add new media uploads
        if ($request->has('minutes')) {
            foreach ($request->input('minutes', []) as $fileName) {
                $filePath = storage_path('app/temp/dropzone/' . $fileName);
                if (file_exists($filePath)) {
                    $meeting->addMedia($filePath)->toMediaCollection('MeetingMinute');
                }
            }
        }
    }
}