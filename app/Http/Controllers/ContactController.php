<?php

namespace App\Http\Controllers;

use App\DataTables\ContactDataTable;
use App\Http\Requests\updateContactFormRequest;
use App\Models\Contact;
use App\Mail\ContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;

class ContactController extends Controller
{

    // Display a listing of contacts
    public function index(ContactDataTable $dataTable)
    {
        abort_if(Gate::denies('view contacts'), 403, 'You do not have access to this page.');
        return $dataTable->render('pages.contacts.index');
    }

    // Display the specified contact
    public function show(Contact $contact)
    {
        abort_if(Gate::denies('view contacts'), 403, 'You do not have access to this page.');
        return view('pages.contacts.show', compact('contact'));
    }

    // Show the form for editing the specified contact
    public function edit(Request $request,Contact $contact)
    {
        if ($request->ajax()) {
            abort_if(Gate::denies('edit contacts'), 403, 'You do not have access to this page.');
            $view = View::make('pages.contacts.contact-form', compact('contact'))->render();
            return response()->json(['html' => $view]);
        } else {
            return response()->json(['status' => 'error', 'message' => "Invalid ajax request!"]);
        }
    }

    // Update the specified contact in storage
    public function update(updateContactFormRequest $request, Contact $contact)
    {
        if ($request->ajax()) {
            try {
                $contact->update($request->validated());
                return response()->json([ 'status' => 'success', 'message' => 'Contact form updated successfully.' ], 200);
            } catch (\Exception $e) {
                return response()->json([ 'status' => 'error', 'message' => 'Failed to update contact form. Please try again.' ], 500);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => "Invalid ajax request!"]);
        }
    }

    public function destroy(Request $request, Contact $contact)
    {
        if ($request->ajax()) {
            abort_if(Gate::denies('delete contacts'), 403, 'You do not have access to this resource.');
            try {
                $contact->delete();
                return response()->json(['status' => 'success', 'message' => 'Contact message deleted successfully.'], 200);
            } catch (\Exception $e) {
                return response()->json(['status' => 'error', 'message' => 'Failed to delete contact message. Please try again.'], 500);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => "Invalid ajax request!"]);
        }
    }
}
