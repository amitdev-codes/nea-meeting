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
        <x-forms.input name="meeting_type" :label="__('field.meeting_type')" :value="old('meeting_type', $model->meeting_type ?? '')" />
    </div>

    <x-forms.input-nepali-datePicker name="meeting_date" id="nepaliDate" class="form-control" datepicker
        label="{{ __('field.meeting_date') }}" value="{{ old('meeting_date', $model->meeting_date ?? '') }}"
        placeholder="Select Meeting Date" required />

        <div class="mb-3 col-md-4">
            <x-forms.input-time 
                name="start_time" 
                id="startTime" 
                :label="__('field.start_time')" 
                :value="old('start_time', isset($model) ?$model->start_time ?? \Carbon\Carbon::parse($model->start_time)->format('H:i') : '')" 
                placeholder="Select Start Time" 
                required 
            />
        </div>
        <div class="mb-3 col-md-4">
            <x-forms.input-time 
                name="end_time" 
                id="endTime" 
                :label="__('field.end_time')" 
                :value="old('end_time', isset($model) ?$model->end_time ?? \Carbon\Carbon::parse($model->end_time)->format('H:i') : '')" 
                placeholder="Select End Time" 
                required 
            />
        </div>
        <div class="col-md-12 mt-6">
            <x-forms.input-dropzone name="meetingDocuments" :maxFileSize="5" :maxFiles="5" mediaName="meetings"
                id="meetingDocuments" :model="isset($model) ? $model : null" />
        </div>
    
    <div class="mb-3 col-md-12">
        <x-forms.input-textarea name="description" :label="__('field.description')" :value="old('description', $model->description ?? '')" />
    </div>
@endsection
