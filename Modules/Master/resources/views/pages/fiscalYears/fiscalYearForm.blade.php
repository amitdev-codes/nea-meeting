@extends('layouts/contentNavbarLayout')

@section('content')
<x-breadcrumb :model="$modelClass" :action="isset($model) ? 'edit' : 'create'" />
<div class="container-xxl">
    <div class="row">
        <div class="col-md-12">
            <form
                action="{{ isset($model) ? route('admin.fiscal-years.update', $model->id) : route('admin.fiscal-years.store') }}"
                method="POST">
                @csrf
                @if (isset($model))
                @method('PUT')
                @endif
                <div class="card mb-6">
                    <div class="card-body pt-4">
                        <div class="row">
                            <div class="mb-3 col-md-4">
                                <x-forms.input name="code" :label="__('fiscalYear.code')" :value="old('code', $model->code ?? '')" />
                            </div>

                            <div class="mb-3 col-md-4">
                                <x-forms.input-datePicker name="date_from_bs" :label="__('field.date_from_bs')" type="text"
                                    placeholder="YYYY-MM-DD" :value="old('date_from_bs', $model->date_from_bs ?? '')" id="flatpickr-date-from"
                                    class="flatpickr-input active" required />
                            </div>

                            <div class="mb-3 col-md-4">
                                <x-forms.input-datePicker name="date_to_bs" type="text" placeholder="YYYY-MM-DD"
                                    :label="__('field.date_to_bs')" :value="old('date_to_bs', $model->date_to_bs ?? '')" id="flatpickr-date-to"
                                    class="flatpickr-input active" required />
                            </div>

                            <div class="mb-3 col-md-4">
                                <x-forms.input-datePicker name="date_from_ad" :label="__('field.date_from_ad')" type="text"
                                    placeholder="YYYY-MM-DD" :value="old('date_from_ad', $model->date_from_ad ?? '')" id="flatpickr-date-from"
                                    class="flatpickr-input active" required />
                            </div>

                            <div class="mb-3 col-md-4">
                                <x-forms.input-datePicker name="date_to_ad" type="text" placeholder="YYYY-MM-DD"
                                    :label="__('field.date_to_ad')" :value="old('date_to_ad', $model->date_to_ad ?? '')" id="flatpickr-date-to"
                                    class="flatpickr-input active" required />
                            </div>

                            <div class="col-md-4 mt-6">
                                <x-forms.input-switch name="is_current" label="is_current" :value="old('is_current', $model->is_current ?? 1)" />
                            </div>
                        </div>

                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2">
                                {{ isset($model) ? __('button.update') : __('button.submit') }}
                            </button>
                            <button type="reset" class="btn btn-outline-secondary">{{ __('button.reset') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection