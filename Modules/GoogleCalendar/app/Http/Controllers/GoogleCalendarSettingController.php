<?php

namespace Modules\GoogleCalendar\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use Modules\GoogleCalendar\Models\GoogleCalendarSetting;
use Modules\GoogleCalendar\DataTables\GoogleCalendarSettingDataTable;
use Modules\GoogleCalendar\Http\Requests\StoreGoogleCalendarSettingRequest;
use Modules\GoogleCalendar\Http\Requests\UpdateGoogleCalendarSettingRequest;
use App\Http\Controllers\BaseAdminController;

class GoogleCalendarSettingController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;
    
    protected $model = GoogleCalendarSetting::class;
    protected string $resourcePermission = 'google-calendar-settings';
    protected string $resourceName = 'google-calendar-settings';
    protected string $formView = 'googlecalendar::pages.googleCalendarSettings.googleCalendarSettingForm';
    
    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }
    
    public function index(GoogleCalendarSettingDataTable $dataTable)
    {
        return $dataTable->render('pages.resources.index');
    }
    
    public function create(Request $request)
    {
        return $this->renderForm($this->formView);
    }
    
    public function store(StoreGoogleCalendarSettingRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            GoogleCalendarSetting::create($request->validated());
            if ($request->has('save_and_add_more')) {
                return redirect()->route('admin.google-calendar-settings.create')
                ->with('success', 'Group Member created successfully. Add another one.');
            }
        }, 'admin.google-calendar-settings.index', 'GoogleCalendarSetting created successfully.', 'Failed to create the GoogleCalendarSetting.');
    }
    
    public function show(GoogleCalendarSetting $googleCalendarSetting)
    {
        return view('googlecalendar::pages.google-calendar-settings.show', ['resource' => $googleCalendarSetting]);
    }
    
    public function edit(GoogleCalendarSetting $googleCalendarSetting)
    {
        return $this->renderForm($this->formView, $googleCalendarSetting);
    }
    
    public function update(UpdateGoogleCalendarSettingRequest $request, GoogleCalendarSetting $googleCalendarSetting)
    {
        return $this->handleRequest($request, function () use ($request, $googleCalendarSetting) {
            $googleCalendarSetting->update($request->validated());
        }, 'admin.google-calendar-settings.index', 'GoogleCalendarSetting updated successfully.', 'Failed to update the GoogleCalendarSetting.');
    }
    
    public function destroy(Request $request, GoogleCalendarSetting $googleCalendarSetting)
    {
        return $this->handleRequest($request, function () use ($googleCalendarSetting) {
            $googleCalendarSetting->delete();
        }, 'admin.google-calendar-settings.index', 'GoogleCalendarSetting deleted successfully.', 'Failed to delete the GoogleCalendarSetting.');
    }
}