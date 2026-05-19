<?php

namespace Modules\Calendar\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use App\Http\Controllers\Controller;
use Modules\Calendar\Models\NepaliCalendar;
use App\Http\Controllers\BaseAdminController;
use Modules\Calendar\DataTables\CalendarDataTable;
use Modules\Calendar\Http\Requests\UpdateNepaliCalendarRequest;

class NepaliCalendarController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;

    protected $model = NepaliCalendar::class;
    protected string $resourcePermission = 'nepali-calendars';
    protected string $resourceName = 'nepali-calendars';
    protected string $formView = 'calendar::pages.nepaliCalendar.NepaliCalendarForm';

    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }

    public function index(CalendarDataTable $dataTable)
    {
        return $this->renderDataTable($dataTable);
    }

    public function create(Request $request)
    {
        return $this->renderModalForm($this->formView);
    }

    public function store(StoreNepaliCalendarRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            NepaliCalendar::create($request->validated());
        }, 'admin.nepali-calendars.index', 'NepaliCalendar created successfully.', 'Failed to create the NepaliCalendar.');
    }

    public function show(NepaliCalendar $nepaliCalendar)
    {
        return view('calendar::pages.nepali-calendars.show', ['resource' => $nepaliCalendar]);
    }

    public function edit(NepaliCalendar $nepaliCalendar)
    {
        return $this->renderModalForm($this->formView, $nepaliCalendar);
    }

    public function update(UpdateNepaliCalendarRequest $request, NepaliCalendar $nepaliCalendar)
    {
        return $this->handleRequest($request, function () use ($request, $nepaliCalendar) {
            $nepaliCalendar->update($request->validated());
        }, 'admin.nepali-calendars.index', 'NepaliCalendar updated successfully.', 'Failed to update the NepaliCalendar.');
    }

    public function destroy(Request $request, NepaliCalendar $nepaliCalendar)
    {
        return $this->handleRequest($request, function () use ($nepaliCalendar) {
            $nepaliCalendar->delete();
        }, 'admin.nepali-calendars.index', 'NepaliCalendar deleted successfully.', 'Failed to delete the NepaliCalendar.');
    }
}
