@extends('pages.modal.modalForm')
@section('form-fields')
    <div class="mb-3 col-md-4">
        <x-forms.input name="start_date" :label="__('field.start_date')" :value="old('start_date', $model->start_date ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="bs_year" :label="__('field.bs_year')" :value="old('bs_year', $model->bs_year ?? '')" />
    </div>

    <div class="mb-3 col-md-4">
        <x-forms.input name="month" :label="__('field.month')" :value="old('month', $model->month ?? '')" />
    </div>

    <div class="mb-3 col-md-4">
        <x-forms.input name="days" :label="__('field.field_days')" :value="old('days', $model->days ?? '')" />
    </div>
@endsection
