@extends('layouts/contentNavbarLayout')

@section('content')
    <x-breadcrumb title="Conatct Message Details" :items="[['label' => 'All Messages', 'route' => 'admin.contacts.index'], ['label' => 'Conatct Message Details']]" />


    <div class="contact-message card p-sm-12 p-6">
        <div class="card-body contact-message-header rounded">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-heading">
                    <i class='bx bx-mail-send me-2 bx-lg'></i>
                    <span class="message-heading fw-bold">New Contact Message</span>
                </div>
                <div>
                    <div class="text-heading">
                        <span>Date Sent:</span>
                        <span class="fw-medium">{{ $contact->created_at->format('d M, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body my-4">
            <div class="table-responsive mb-4">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td class="col-2 ps-0 d-flex align-items-center">
                                <i class="bx bx-user"></i>
                                <span class="fw-medium ms-2">Name</span>
                            </td>
                            <td class="col-10">
                                <span class="fw-medium text-capitalize">
                                    {{ $contact->name }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="col-2 ps-0 d-flex align-items-center">
                                <i class="bx bx-envelope"></i>
                                <span class="fw-medium ms-2">Email</span>
                            </td>
                            <td class="col-10">
                                <span>{{ $contact->email }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="col-2 ps-0 d-flex align-items-center">
                                <i class='bx bx-text'></i>
                                <span class="fw-medium ms-2">Subject</span>
                            </td>
                            <td class="col-10">
                                <span class="fw-semibold">{{ $contact->subject }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="col-2 ps-0 d-flex align-items-center">
                                <i class='bx bx-message-dots'></i>
                                <span class="fw-medium ms-2">Message</span>
                            </td>
                            <td class="col-10">
                                <div class="message-body rounded p-2">
                                    <p class="fw-medium">{{ $contact->message }}</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary me-2 text-white">
                <i class='bx bx-left-arrow me-2'></i>Back
            </a>
        </div>
    @endsection
