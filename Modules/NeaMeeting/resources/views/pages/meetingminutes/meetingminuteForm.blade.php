@extends('pages.resources.form')

@section('form-fields')
<div class="mb-3 col-sm-6">
    <x-forms.input-select2 name="meeting_id" :options="$meetings
        ->map(function ($meeting) {
            return [$meeting->id, $meeting->title];
        })
        ->toArray()" :value="old('meeting_id', isset($model) ? $model->meeting_id :'')"
        placeholder="{{ __('Select a meeting') }}" required />
</div>

    <div class="mb-3 col-sm-6">
        <x-forms.input-textarea name="content" :label="__('field.content')" :value="old('content', $model->content ?? '')" />
    </div>

    <div class="col-md-12 mt-6">
        <x-forms.input-dropzone name="minutes" :maxFileSize="5" :maxFiles="5" mediaName="MeetingMinute"
            id="minutes" :model="isset($model) ? $model : null" />
    </div>

    <div class="mb-3 col-sm-6">
        <x-forms.input-select2 name="recorded_by" :options="$users
            ->map(function ($user) {
                return [$user->id, $user->username];
            })
            ->toArray()" :value="old('recorded_by', isset($model) ? $model->recorded_by :'')"
            placeholder="{{ __('Select a User') }}" required />
    </div>

    <div class="mb-3 col-md-4">
        <x-forms.input-switch name="approved" label="approved" :value="old('approved', $model->approved ?? 1)" />
        {{-- <x-forms.input name="approved" :label="__('field.approved')" :value="old('approved', $model->approved ?? '')" /> --}}
    </div>
@endsection