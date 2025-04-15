@extends('pages.modal.modalForm')
@section('form-fields')
    <div class="mb-3 col-md-4">
        <x-forms.input name="name" :value="old('name', $permission->name ?? '')" />
    </div>
    </div>
@endsection