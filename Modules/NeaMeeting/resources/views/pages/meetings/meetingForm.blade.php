@extends('pages.resources.form')

@section('form-fields')
    <div class="accordion" id="meetingFormAccordion">
        <!-- Section 1: Meeting Details -->
        <div class="card mb-3">
            <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#meetingDetails"
                        aria-expanded="true" aria-controls="meetingDetails">
                        <i class="bx bx-calendar-alt me-2"></i> {{ __('Meeting Details') }}
                    </button>
                </h5>
            </div>
            <div id="meetingDetails" class="collapse show" aria-labelledby="headingOne" data-parent="#meetingFormAccordion">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-3">
                            <x-forms.input name="title" :label="__('field.title')" :value="old('title', $model->title ?? '')" required />
                        </div>
                        <div class="mb-3 col-md-3">
                            <x-forms.input name="meeting_location" :label="__('field.meeting_location')" :value="old('meeting_location', $model->meeting_location ?? '')" />
                        </div>
                        <div class="mb-3 col-md-3">
                            <x-forms.input name="meeting_rooms" :label="__('field.meeting_rooms')" :value="old('meeting_rooms', $model->meeting_rooms ?? '')" />
                        </div>
                        <div class="mb-3 col-md-3">
                            <x-forms.input-select2 name="meeting_type" :options="\App\Enums\MeetingType::toArray()" :value="old('meeting_type', isset($model) ? $model->meeting_type ?? '' : '')"
                                placeholder="{{ __('Select Meeting Type') }}" required />
                        </div>
                    </div>
                    <div class="row">
                        <x-forms.input-nepali-datePicker name="meeting_date" id="nepaliDate" class="form-control" datepicker
                            label="{{ __('field.meeting_date') }}"
                            value="{{ old('meeting_date', $model->meeting_date ?? '') }}" placeholder="Select Meeting Date"
                            required />


                        <div class="mb-3 col-md-3">
                            <x-forms.input-time name="start_time" id="startTime" :label="__('field.start_time')" :value="old(
                                'start_time',
                                isset($model) && $model->start_time
                                    ? \Carbon\Carbon::parse($model->start_time)->format('H:i')
                                    : '',
                            )"
                                placeholder="Select Start Time" required />
                        </div>
                        <div class="mb-3 col-md-3">
                            <x-forms.input-time name="end_time" id="endTime" :label="__('field.end_time')" :value="old(
                                'end_time',
                                isset($model->end_time) ? \Carbon\Carbon::parse($model->end_time)->format('H:i') : '',
                            )"
                                placeholder="Select End Time" />
                        </div>
                        <div class="mt-5 col-md-3">
                            <x-forms.input-switch name="is_virtual" :label="__('virtual_meeting')" :value="old('is_virtual', $model->is_virtual ?? 0)" />
                        </div>
                    </div>

                    <!-- Virtual Meeting Link (conditionally displayed) -->
                    <div class="row">
                        <div class="mb-3 col-md-12 virtual-meeting-link"
                            style="{{ old('is_virtual', $model->is_virtual ?? false) ? '' : 'display: none;' }}">
                            <x-forms.input name="virtual_meeting_link" :label="__('Virtual Meeting Link')" :value="old('virtual_meeting_link', $model->virtual_meeting_link ?? '')" />
                        </div>
                    </div>


                </div>
            </div>
        </div>

        <!-- Section 2: Meeting Attendees -->
        <div class="card mb-3">
            <div class="card-header collapsed" id="headingTwo">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#meetingAttendees" aria-expanded="false" aria-controls="meetingAttendees">
                        <i class="bx bx-user me-2"></i> {{ __('Meeting Attendees') }}
                    </button>
                </h5>
            </div>
            <div id="meetingAttendees" class="collapse" aria-labelledby="headingTwo" data-bs-parent="#meetingFormAccordion">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <x-forms.input-select2 name="organizations" id="select2Organization" class="select2 form-select"
                                :options="$organizations
                                    ->map(
                                        fn($organization) => [
                                            $organization->id,
                                            $organization->name . ' - ' . $organization->name_np,
                                        ],
                                    )
                                    ->toArray()" :value="old(
                                    'organizations',
                                    isset($model)
                                        ? (is_array($model->organizations)
                                            ? $model->organizations
                                            : json_decode($model->organizations, true))
                                        : [],
                                )" placeholder="{{ __('Select Local Levels') }}"
                                multiple />
                        </div>
                        <div class="mb-3 col-md-2">
                            <x-forms.input-switch name="is_external" :label="__('is_external')" :value="old('is_external', $model->is_external ?? 0)" />
                        </div>
                        <!-- External Contacts Grid (conditionally displayed) -->
                        <div class="row">
                            <div class="mb-3 col-md-12 external-contacts-grid"
                                style="{{ old('is_external', $model->is_external ?? false) ? '' : 'display: none;' }}">
                                <div class="card">
                                    <div
                                        class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                                        <h5 class="mb-0 text-white">{{ __('External Contacts') }}</h5>
                                        <button type="button" class="btn btn-light btn-sm add-contact-row">
                                            <i class="bx bx-plus"></i> {{ __('Add Contact') }}
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered external-contacts-table">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>{{ __('Name') }}</th>
                                                        <th>{{ __('Email') }}</th>
                                                        <th>{{ __('Mobile') }}</th>
                                                        <th>{{ __('Phone') }}</th>
                                                        <th>{{ __('Office Name') }}</th>
                                                        <th>{{ __('Action') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $externalContacts = old(
                                                            'external_contacts',
                                                            isset($model) && $model->externalContacts->isNotEmpty()
                                                                ? $model->externalContacts->toArray()
                                                                : [],
                                                        );
                                                    @endphp
                                                    @forelse ($externalContacts as $index => $contact)
                                                        <tr class="contact-row">
                                                            <td><input type="text"
                                                                    name="external_contacts[{{ $index }}][name]"
                                                                    class="form-control"
                                                                    value="{{ $contact['name'] ?? '' }}"
                                                                    placeholder="{{ __('Enter Name') }}" /></td>
                                                            <td><input type="email"
                                                                    name="external_contacts[{{ $index }}][email]"
                                                                    class="form-control"
                                                                    value="{{ $contact['email'] ?? '' }}"
                                                                    placeholder="{{ __('Enter Email') }}" /></td>
                                                            <td><input type="text"
                                                                    name="external_contacts[{{ $index }}][mobile]"
                                                                    class="form-control"
                                                                    value="{{ $contact['mobile'] ?? '' }}"
                                                                    placeholder="{{ __('Enter Mobile') }}" /></td>
                                                            <td><input type="text"
                                                                    name="external_contacts[{{ $index }}][phone]"
                                                                    class="form-control"
                                                                    value="{{ $contact['phone'] ?? '' }}"
                                                                    placeholder="{{ __('Enter Phone') }}" /></td>
                                                            <td><input type="text"
                                                                    name="external_contacts[{{ $index }}][office_name]"
                                                                    class="form-control"
                                                                    value="{{ $contact['office_name'] ?? '' }}"
                                                                    placeholder="{{ __('Enter Office Name') }}" /></td>
                                                            <td>
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm remove-contact-row">
                                                                    <i class="bx bx-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr class="contact-row">
                                                            <td><input type="text" name="external_contacts[0][name]"
                                                                    class="form-control"
                                                                    placeholder="{{ __('Enter Name') }}" /></td>
                                                            <td><input type="email" name="external_contacts[0][email]"
                                                                    class="form-control"
                                                                    placeholder="{{ __('Enter Email') }}" /></td>
                                                            <td><input type="text" name="external_contacts[0][mobile]"
                                                                    class="form-control"
                                                                    placeholder="{{ __('Enter Mobile') }}" /></td>
                                                            <td><input type="text" name="external_contacts[0][phone]"
                                                                    class="form-control"
                                                                    placeholder="{{ __('Enter Phone') }}" /></td>
                                                            <td><input type="text"
                                                                    name="external_contacts[0][office_name]"
                                                                    class="form-control"
                                                                    placeholder="{{ __('Enter Office Name') }}" /></td>
                                                            <td>
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm remove-contact-row">
                                                                    <i class="bx bx-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mb-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" id="send_notifications"
                                        name="send_notifications" {{ old('send_notifications', true) ? 'checked' : '' }}>
                                    <label class="form-check-label"
                                        for="send_notifications">{{ __('Send notifications to attendees') }}</label>
                                </div>
                                <div class="form-check form-switch mb-0" id="notification-options"
                                    style="{{ old('send_notifications', true) ? '' : 'display: none;' }}">
                                    <input class="form-check-input" type="checkbox" id="send_email" name="send_email"
                                        {{ old('send_email', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="send_email">{{ __('Send Email') }}</label>
                                </div>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" id="send_sms" name="send_sms"
                                        {{ old('send_sms', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="send_sms">{{ __('Send SMS') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: Documents & Description -->
        <div class="card mb-3">
            <div class="card-header" id="headingThree">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#documentsDescription" aria-expanded="false"
                        aria-controls="documentsDescription">
                        <i class="bx bx-file-blank me-2"></i> {{ __('Documents & Description') }}
                    </button>

                </h5>
            </div>
            <div id="documentsDescription" class="collapse" aria-labelledby="headingThree"
                data-bs-parent="#meetingFormAccordion">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <x-forms.input-dropzone name="meetingDocuments" :maxFileSize="5" :maxFiles="5"
                                mediaName="meetings" id="meetingDocuments" :model="isset($model) ? $model : null" />
                        </div>

                        <div class="mb-3 col-md-12">
                            <x-forms.input-textarea name="description" :label="__('field.description')" :value="old('description', $model->description ?? '')"
                                rows="5" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (isset($model))
            <!-- Section 4: Meeting Status (only in update case) -->
            <div class="card mb-3">
                <div class="card-header collapsed" id="headingFour" data-toggle="collapse" data-target="#meetingStatus"
                    aria-expanded="false" aria-controls="meetingStatus">
                    <h5 class="mb-0">
                        <button class="btn btn-link" type="button" data-bs-toggle="collapse"
                            data-bs-target="#meetingStatus" aria-expanded="false" aria-controls="meetingStatus">
                            <i class="fas fa-info-circle mr-2"></i> {{ __('Meeting Status') }}
                        </button>
                    </h5>
                </div>
                <div id="meetingStatus" class="collapse" aria-labelledby="headingFour"
                    data-parent="#meetingFormAccordion">
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <x-forms.input-select2 name="status" :options="\App\Enums\MeetingStatus::toArray()" :value="old('status', $model->status ?? \App\Enums\MeetingStatus::Scheduled->value)"
                                    :label="__('field.status')" placeholder="{{ __('Select Status') }}" />
                            </div>

                            <!-- Remarks Field (shown when status is Cancelled) -->
                            <div class="mb-3 col-md-12 remarks-field"
                                style="{{ old('status', $model->status ?? '') === \App\Enums\MeetingStatus::Cancelled->value }}">
                                <x-forms.input-textarea name="remarks" :label="__('field.remarks')" :value="old('remarks', $model->remarks ?? '')"
                                    placeholder="{{ __('Enter reason for cancellation') }}" rows="3" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection


<style>
    .accordion .card-header {
        background-color: #f8f9fa;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .accordion .card-header:hover {
        background-color: #e9ecef;
    }

    .accordion .card-header h5 button {
        color: #495057;
        text-decoration: none;
        font-weight: 600;
    }

    .accordion .card-header h5 button:hover {
        color: #0056b3;
    }

    .accordion .card-header h5 button:not(.collapsed) {
        color: #0056b3;
    }

    .accordion .card-header h5 button:focus {
        box-shadow: none;
    }

    .external-contacts-table th {
        background-color: #f1f5f9;
    }

    .btn-light {
        background-color: #fff;
        border-color: #dee2e6;
    }

    .bg-primary {
        background: linear-gradient(45deg, #4e73df, #224abe);
    }

    .form-control:focus,
    .custom-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }

    .btn-primary,
    .btn-danger {
        transition: all 0.2s ease;
    }

    .btn-primary:hover,
    .btn-danger:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
</style>

@push('scripts')
    <script type="module">
        // Handle virtual meeting toggle
        $('input[name="is_virtual"]').change(function() {
            $('.virtual-meeting-link').toggle(this.checked);
        });

        // Handle external meeting toggle
        $('input[name="is_external"]').change(function() {
            $('.external-contacts-grid').toggle(this.checked);
        });

        // Handle notification options toggle
        $('#send_notifications').change(function() {
            $('#notification-options').toggle(this.checked);
        });

        // Handle status change to show/hide remarks field
        $('select[name="status"]').change(function() {
            $('.remarks-field').toggle($(this).val() === '{{ \App\Enums\MeetingStatus::Cancelled->value }}');
        });

        // Handle adding new contact row
        $('.add-contact-row').click(function() {
            let rowCount = $('.external-contacts-table tbody tr').length;
            let newRow = `
            <tr class="contact-row">
                <td><input type="text" name="external_contacts[${rowCount}][name]" class="form-control" placeholder="{{ __('Enter Name') }}" /></td>
                <td><input type="email" name="external_contacts[${rowCount}][email]" class="form-control" placeholder="{{ __('Enter Email') }}" /></td>
                <td><input type="text" name="external_contacts[${rowCount}][mobile]" class="form-control" placeholder="{{ __('Enter Mobile') }}" /></td>
                <td><input type="text" name="external_contacts[${rowCount}][phone]" class="form-control" placeholder="{{ __('Enter Phone') }}" /></td>
                <td><input type="text" name="external_contacts[${rowCount}][office_name]" class="form-control" placeholder="{{ __('Enter Office Name') }}" /></td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-contact-row">
                        <i class="bx bx-trash"></i>
                    </button>
                </td>
            </tr>`;
            $('.external-contacts-table tbody').append(newRow);
        });

        // Handle removing contact row
        $(document).on('click', '.remove-contact-row', function() {
            if ($('.external-contacts-table tbody tr').length > 1) {
                $(this).closest('tr').remove();
                $('.external-contacts-table tbody tr').each(function(index) {
                    $(this).find('input').each(function() {
                        let name = $(this).attr('name').replace(/external_contacts\[\d+\]/,
                            `external_contacts[${index}]`);
                        $(this).attr('name', name);
                    });
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: 'At least one contact row is required.',
                });
            }
        });
    </script>
    @endpush
