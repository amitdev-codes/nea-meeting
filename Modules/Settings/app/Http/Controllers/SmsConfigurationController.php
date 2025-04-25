<?php

namespace Modules\Settings\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\BaseAdminController;
use Modules\Settings\Models\SmsConfiguration;
use Modules\Settings\Services\Sms\SmsServiceFactory;
use Modules\Settings\DataTables\SmsConfigurationDataTable;
use Modules\Settings\Http\Requests\StoreSmsConfigurationRequest;
use Modules\Settings\Http\Requests\UpdateSmsConfigurationRequest;

class SmsConfigurationController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;
    
    protected $model = SmsConfiguration::class;
    protected string $resourcePermission = 'sms-configurations';
    protected string $resourceName = 'sms-configurations';
    protected string $formView = 'settings::pages.smsConfigurations.smsConfigurationForm';
    
    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }
    
    public function index(SmsConfigurationDataTable $dataTable)
    {
        return $dataTable->render('pages.resources.index');
    }
    
    public function create(Request $request)
    {
        return $this->renderForm($this->formView);
    }
    
    public function store(StoreSmsConfigurationRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
                    // If this is set as active, deactivate all other configurations
                if ($request->has('is_active') && $request->is_active) {
                    SmsConfiguration::where('id', '!=', 0)->update(['is_active' => false]);
                }
            SmsConfiguration::create($request->validated());
            if ($request->has('save_and_add_more')) {
                return redirect()->route('admin.sms-configurations.create')
                ->with('success', 'Group Member created successfully. Add another one.');
            }
        }, 'admin.sms-configurations.index', 'SmsConfiguration created successfully.', 'Failed to create the SmsConfiguration.');
    }
    
    public function show(SmsConfiguration $smsConfiguration)
    {
        return view('settings::pages.sms-configurations.show', ['resource' => $smsConfiguration]);
    }
    
    public function edit(SmsConfiguration $smsConfiguration)
    {
        return $this->renderForm($this->formView, $smsConfiguration);
    }
    
    public function update(UpdateSmsConfigurationRequest $request, SmsConfiguration $smsConfiguration)
    {
        return $this->handleRequest($request, function () use ($request, $smsConfiguration) {
            if ($request->has('is_active') && $request->is_active) {
                SmsConfiguration::where('id', '!=', $smsConfiguration->id)->update(['is_active' => false]);
            }
            $smsConfiguration->update($request->validated());
        }, 'admin.sms-configurations.index', 'SmsConfiguration updated successfully.', 'Failed to update the SmsConfiguration.');
    }
    
    public function destroy(Request $request, SmsConfiguration $smsConfiguration)
    {
        return $this->handleRequest($request, function () use ($smsConfiguration) {
            $smsConfiguration->delete();
        }, 'admin.sms-configurations.index', 'SmsConfiguration deleted successfully.', 'Failed to delete the SmsConfiguration.');
    }
    public function activate(SmsConfiguration $smsConfiguration)
    {
        SmsConfiguration::where('id', '!=', $smsConfiguration->id)->update(['is_active' => false]);
        $smsConfiguration->update(['is_active' => true]);

        return redirect()->route('admin.sms-configurations.index')
            ->with('success', 'SMS configuration activated successfully');
    }
    public function test(Request $request, SmsConfiguration $smsConfiguration)
    {
        try {
            // Temporarily activate this configuration for testing
            $wasActive = $smsConfiguration->is_active;
            $smsConfiguration->update(['is_active' => true]);
            
            // Get the appropriate service based on provider
            $smsService =SmsServiceFactory::create();
            $test_number='9800908674';
            $test_message='test message for neamms';
            
            // Send test message
            $result = $smsService->send($test_number, $test_message);
            
            // Restore original active state
            if (!$wasActive) {
                $smsConfiguration->update(['is_active' => false]);
            }
            
            if ($result['success']) {
                return redirect()->back()->with('success', 'Test SMS sent successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to send test SMS: ' . $result['message']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error testing SMS: ' . $e->getMessage());
        }
    }
}