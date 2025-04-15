<?php

namespace Modules\NeaMeeting\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use Modules\NeaMeeting\Models\Notification;
use Modules\NeaMeeting\DataTables\NotificationDataTable;
use Modules\NeaMeeting\Http\Requests\StoreNotificationRequest;
use Modules\NeaMeeting\Http\Requests\UpdateNotificationRequest;
use App\Http\Controllers\BaseAdminController;

class NotificationController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;
    
    protected $model = Notification::class;
    protected string $resourcePermission = 'notifications';
    protected string $resourceName = 'notifications';
    protected string $formView = 'neameeting::pages.notifications.notificationForm';
    
    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }
    
    public function index(NotificationDataTable $dataTable)
    {
        return $this->renderDataTable($dataTable);
    }
    
    public function create(Request $request)
    {
        return $this->renderModalForm($this->formView);
    }
    
    public function store(StoreNotificationRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            Notification::create($request->validated());
            if ($request->has('save_and_add_more')) {
                return redirect()->route('admin.notifications.create')
                ->with('success', 'Group Member created successfully. Add another one.');
            }
        }, 'admin.notifications.index', 'Notification created successfully.', 'Failed to create the Notification.');
    }
    
    public function show(Notification $notification)
    {
        return view('neameeting::pages.notifications.show', ['resource' => $notification]);
    }
    
    public function edit(Notification $notification)
    {
        return $this->renderModalForm($this->formView, $notification);
    }
    
    public function update(UpdateNotificationRequest $request, Notification $notification)
    {
        return $this->handleRequest($request, function () use ($request, $notification) {
            $notification->update($request->validated());
        }, 'admin.notifications.index', 'Notification updated successfully.', 'Failed to update the Notification.');
    }
    
    public function destroy(Request $request, Notification $notification)
    {
        return $this->handleRequest($request, function () use ($notification) {
            $notification->delete();
        }, 'admin.notifications.index', 'Notification deleted successfully.', 'Failed to delete the Notification.');
    }
}