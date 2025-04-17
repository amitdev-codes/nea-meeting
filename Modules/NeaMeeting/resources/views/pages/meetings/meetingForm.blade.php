@extends('pages.resources.form')

@section('form-fields')
    <div class="mb-3 col-md-3">
        <x-forms.input name="title" :label="__('field.title')" :value="old('title', $model->title ?? '')" />
    </div>
    <div class="mb-3 col-md-3">
        <x-forms.input name="meeting_location" :label="__('field.meeting_location')" :value="old('meeting_location', $model->meeting_location ?? '')" />
    </div>
    <div class="mb-3 col-md-3">
        <x-forms.input-select2 name="meeting_room_id" :options="$meeting_rooms
            ->map(function ($meeting_room) {
                return [$meeting_room->id, $meeting_room->name ?? ''];
            })
            ->toArray()" :value="old('meeting_room_id', isset($model) ? $model->meeting_room_id ?? '' : '')"
            placeholder="{{ __('Select Meeting Room') }}" required />
    </div>
    <div class="mb-3 col-md-3">
        <x-forms.input-select2 name="meeting_type" :options="\App\Enums\MeetingType::toArray()" :value="old('meeting_type', isset($model) ? $model->meeting_type ?? '' : '')"
            placeholder="{{ __('Select Meeting Type') }}" required />
    </div>


    <x-forms.input-nepali-datePicker name="meeting_date" id="nepaliDate" class="form-control" datepicker
        label="{{ __('field.meeting_date') }}" value="{{ old('meeting_date', $model->meeting_date ?? '') }}"
        placeholder="Select Meeting Date" required />

    <div class="mb-3 col-md-4">
        <x-forms.input-time name="start_time" id="startTime" :label="__('field.start_time')" :value="old(
            'start_time',
            isset($model) ? $model->start_time ?? \Carbon\Carbon::parse($model->start_time)->format('H:i') : '',
        )"
            placeholder="Select Start Time" required />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input-time name="end_time" id="endTime" :label="__('field.end_time')" :value="old(
            'end_time',
            isset($model) ? $model->end_time ?? \Carbon\Carbon::parse($model->end_time)->format('H:i') : '',
        )"
            placeholder="Select End Time" required />
    </div>

    <!-- Add Virtual Meeting Option -->
    <div class="mb-3 col-md-4">
        <x-forms.input-switch name="is_virtual" :label="__('virtual_meeting')" :checked="old('is_virtual', $model->is_virtual ?? false)" />
    </div>

    <!-- Virtual Meeting Link (conditionally displayed) -->
    <div class="mb-3 col-md-8 virtual-meeting-link"
        style="{{ old('is_virtual', $model->is_virtual ?? false) ? '' : 'display: none;' }}">
        <x-forms.input name="virtual_meeting_link" :label="__('Virtual Meeting Link')" :value="old('virtual_meeting_link', $model->virtual_meeting_link ?? '')" />
    </div>

    <!-- Attendees Section -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Meeting Attendees</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 mb-3">
                    <x-forms.input-select2 name="organizations" :label="__('Select Offices')" :options="$organizations
                        ->map(function ($organization) {
                            return [$organization->id, $organization->name . ' (' . $organization->name_np . ')'];
                        })
                        ->toArray()" :value="old(
                        'organizations',
                        isset($model) ? $model->meetingOrganizations->pluck('organization_id')->toArray() : [],
                    )"
                        placeholder="Select Offices" multiple />
                </div>

                <div class="col-12 mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="send_notifications" name="send_notifications"
                            checked>
                        <label class="form-check-label" for="send_notifications">Send notifications to attendees</label>
                    </div>
                </div>
            </div>

            <!-- Notification Options - Only shown when send_notifications is checked -->
            <div class="row" id="notification-options">
                <div class="col-md-6">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="send_email" name="send_email" checked>
                        <label class="form-check-label" for="send_email">Send Email</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="send_sms" name="send_sms" checked>
                        <label class="form-check-label" for="send_sms">Send SMS</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 mt-6">
        <x-forms.input-dropzone name="meetingDocuments" :maxFileSize="5" :maxFiles="5" mediaName="meetings"
            id="meetingDocuments" :model="isset($model) ? $model : null" />
    </div>

    <div class="mb-3 col-md-12">
        <x-forms.input-textarea name="description" :label="__('field.description')" :value="old('description', $model->description ?? '')" />
    </div>
@endsection

@push('scripts')
    <script type="module">
        // Handle virtual meeting toggle
        $('input[name="is_virtual"]').change(function() {
            if ($(this).is(':checked')) {
                $('.virtual-meeting-link').show();
            } else {
                $('.virtual-meeting-link').hide();
            }
        });

        // Handle notification options toggle
        $('#send_notifications').change(function() {
            if ($(this).is(':checked')) {
                $('#notification-options').show();
            } else {
                $('#notification-options').hide();
            }
        });
    </script>
@endpush
