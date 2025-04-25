<?php

namespace Modules\Settings\Http\Controllers;

use Exception;
use App\Models\Contact;
use App\Mail\ContactMail;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\BaseAdminController;
use Modules\Settings\Models\EmailConfiguration;
use Modules\Settings\Services\DynamicEmailService;
use Modules\Settings\DataTables\EmailConfigurationDataTable;
use Modules\Settings\Http\Requests\StoreEmailConfigurationRequest;
use Modules\Settings\Http\Requests\UpdateEmailConfigurationRequest;

class EmailConfigurationController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;
    protected $emailService;
    
    protected $model = EmailConfiguration::class;
    protected string $resourcePermission = 'email-configurations';
    protected string $resourceName = 'email-configurations';
    protected string $formView = 'settings::pages.emailConfigurations.emailConfigurationForm';
    
    public function __construct(ResponseService $responseService,DynamicEmailService $emailService)
    {
        parent::__construct($responseService);
        $this->emailService = $emailService;
    }
    
    public function index(EmailConfigurationDataTable $dataTable)
    {
        return $dataTable->render('pages.resources.index');
    }
    
    public function create(Request $request)
    {
        return $this->renderForm($this->formView);
    }
    
    public function store(StoreEmailConfigurationRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            if ($request->has('is_active') && $request->is_active) {
                EmailConfiguration::where('id', '!=', 0)->update(['is_active' => false]);
            }
            EmailConfiguration::create($request->validated());
            if ($request->has('save_and_add_more')) {
                return redirect()->route('admin.email-configurations.create')
                ->with('success', 'Group Member created successfully. Add another one.');
            }
        }, 'admin.email-configurations.index', 'EmailConfiguration created successfully.', 'Failed to create the EmailConfiguration.');
    }
    
    public function show(EmailConfiguration $emailConfiguration)
    {
        return view('settings::pages.email-configurations.show', ['resource' => $emailConfiguration]);
    }
    
    public function edit(EmailConfiguration $emailConfiguration)
    {
        return $this->renderForm($this->formView, $emailConfiguration);
    }
    
    public function update(UpdateEmailConfigurationRequest $request, EmailConfiguration $emailConfiguration)
    {
        return $this->handleRequest($request, function () use ($request, $emailConfiguration) {
            $emailConfiguration->update($request->validated());
        }, 'admin.email-configurations.index', 'EmailConfiguration updated successfully.', 'Failed to update the EmailConfiguration.');
    }
    
    public function destroy(Request $request, EmailConfiguration $emailConfiguration)
    {
        return $this->handleRequest($request, function () use ($emailConfiguration) {
            $emailConfiguration->delete();
        }, 'admin.email-configurations.index', 'EmailConfiguration deleted successfully.', 'Failed to delete the EmailConfiguration.');
    }

  
    public function activate(EmailConfiguration $emailConfiguration)
    {
        // EmailConfiguration::where('id', '!=', $emailConfiguration->id)->update(['is_active' => false]);
        // $emailConfiguration->update(['is_active' => true]);

        // return redirect()->route('admin.email-configurations.index')
        //     ->with('success', 'Email configuration activated successfully');
    }

    public function test()
    {

            $to = 'amitdev67@gmail.com';
            $contact = new Contact();
            $contact->message = 'This is a test email sent to verify email configuration.';
            $contact->name = 'amit';
            $contact->email = 'amitdev67@gmail.com';
            $contact->subject = 'testing';
            $contact->save();

            // dd('test');

            
            try {
                Mail::to($to)->send(new ContactMail($contact));
                
                $emailStatus = 'Email sent successfully';
                return redirect()->route('admin.email-configurations.index')
                    ->with('success', $emailStatus);
                    
            } catch (Exception $e) {
                dd($e);
                Log::error('Email sending error: ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString(),
                ]);
                
            }

    }
}