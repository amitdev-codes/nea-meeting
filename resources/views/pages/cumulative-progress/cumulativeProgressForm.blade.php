@extends('pages.resources.form')
@section('form-fields')

    <div class="mb-3 col-sm-3">
        <x-forms.input-select2 name="fiscal_year_id" :options="$fiscalYears
            ->map(function ($fiscalYear) {
                return [$fiscalYear->id, $fiscalYear->code];
            })
            ->toArray()" :value="old('fiscal_year_id', isset($model) ? $model->fiscal_year_id :'')"
            placeholder="{{ __('Select a Fiscal Year') }}" required />
    </div>

    <div class="mb-3 col-sm-3">
        <x-forms.input-datePicker name="project_start_date" type="text"  :enableTime="false" id="project_start_date" placeholder="YYYY-MM-DD" :label="__('field.project_start_date')" :value="old('project_start_date', $model->project_start_date ?? '')" required/>
    </div>
    <div class="mb-3 col-sm-3">
        <x-forms.input-datePicker name="project_end_date" type="text"  :enableTime="false" placeholder="YYYY-MM-DD" id="project_end_date" :label="__('field.project_end_date')" :value="old('project_end_date', $model->project_end_date ?? '')" required/>
    </div>
    <div class="mb-3 col-sm-3">
        <x-forms.input-decimal name="total_given_expenditure" :label="__('field.total_given_expenditure')" :value="old('total_given_expenditure', $model->total_given_expenditure ?? '')" />
    </div>
    <div class="mb-3 col-sm-3">
        <x-forms.input-decimal name="total_budget" :label="__('field.total_budget')" :value="old('total_budget', $model->total_budget ?? '')" />
    </div>
    <div class="mb-3 col-sm-3">
        <x-forms.input-decimal name="total_disbursed" :label="__('field.total_disbursed')" :value="old('total_disbursed', $model->total_disbursed ?? '')" />
    </div>
    <div class="mb-3 col-sm-3">
        <x-forms.input-decimal name="total_group_formed_target" :label="__('field.total_group_formed_target')" :value="old('total_group_formed_target', $model->total_group_formed_target ?? '')" />
    </div>
    <div class="col-sm-3 mt-6">
        <x-forms.input-switch name="status" label="status" :value="old('status', $model->status ?? 1)" />
    </div>
@endsection