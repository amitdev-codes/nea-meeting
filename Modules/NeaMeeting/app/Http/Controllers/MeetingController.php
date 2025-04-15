<?php

namespace Modules\NeaMeeting\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\MeetingCreated;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use Modules\NeaMeeting\Models\Meeting;
use App\Http\Controllers\BaseAdminController;
use Modules\NeaMeeting\DataTables\MeetingDataTable;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Modules\NeaMeeting\Http\Requests\StoreMeetingRequest;
use Modules\NeaMeeting\Http\Requests\UpdateMeetingRequest;

class MeetingController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;
    
    protected $model = Meeting::class;
    protected string $resourcePermission = 'meetings';
    protected string $resourceName = 'meetings';
    protected string $formView = 'neameeting::pages.meetings.meetingForm';
    
    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }
    
    public function index(MeetingDataTable $dataTable)
    {
        return $dataTable->render('pages.resources.index');
    }
    
    public function create(Request $request)
    {
        return $this->renderForm($this->formView);
    }
    
    public function store(StoreMeetingRequest $request)
    {
        return $this->handleRequest($request,function () use ($request) {
                $meeting = Meeting::create($request->validated());
                // Handle media upload if present (from previous Spatie integration)
                
                $this->handleMediaUploads($request, $meeting);
                if ($request->hasFile('meetingDocuments')) {
                    $meeting->addMedia($request->file('meetingDocuments'))->toMediaCollection('meetingDocuments');
                }
                // event(new MeetingCreated($meeting));
                if ($request->has('save_and_add_more')) {
                    return redirect()
                        ->route('admin.meetings.create')
                        ->with('success', 'Meeting created successfully. Add another one.');
                }
            
            },
            'admin.meetings.index','Meeting created successfully.','Failed to create the Meeting.'
        );
    }
    
    public function show(Meeting $meeting)
    {
        $meeting->load(['media' => function($query) {
            $query->where('collection_name', 'meetings');
        }]);
        return view('neameeting::pages.meetings.show', ['resource' => $meeting]);
    }
    
    public function edit(Meeting $meeting)
    {
        $meeting->load(['media' => function($query) {
            $query->where('collection_name', 'meetings');
        }]);
        return $this->renderForm($this->formView, $meeting);
    }
    
    public function update(UpdateMeetingRequest $request, Meeting $meeting)
    {
        return $this->handleRequest($request, function () use ($request, $meeting) {
            $meeting->update($request->validated());
            event(new MeetingCreated($meeting));
            $this->handleMediaUploads($request, $meeting);
            
        }, 'admin.meetings.index', 'Meeting updated successfully.', 'Failed to update the Meeting.');
    }
    
    public function destroy(Request $request, Meeting $meeting)
    {
        return $this->handleRequest($request, function () use ($meeting) {
            $meeting->delete();
        }, 'admin.meetings.index', 'Meeting deleted successfully.', 'Failed to delete the Meeting.');
    }
    private function handleMediaUploads(Request $request, Meeting $meeting)
    {
        // Handle media deletion requests
        if ($request->has('meetingDocuments')) {
            foreach ($request->input('meetingDocuments', []) as $mediaId) {
                $media = Media::find($mediaId);
                if ($media && $media->model_id == $meeting->id) {
                    $media->delete();
                }
            }
        }

        // Add new media uploads
        if ($request->has('meetingDocuments')) {
            foreach ($request->input('meetingDocuments', []) as $fileName) {
                $filePath = storage_path('app/temp/dropzone/' . $fileName);
                if (file_exists($filePath)) {
                    $meeting->addMedia($filePath)->toMediaCollection('meetings');
                }
            }
        }
    }
}