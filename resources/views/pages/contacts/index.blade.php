@extends('layouts/contentNavbarLayout')

@section('content')
<x-breadcrumb title="All Contacts" :items="[['label' => 'All Contacts']]" />
<div class="container-xxl">
    <div class="card">
    <div class="card-header">
            Table header
        </div>
        <div class="card-body  table-responsive text-nowrap">
            {{ $dataTable->table() }}
        </div>
    </div>
</div>

<x-modal id="contactsModal" />
@endsection

@push('script')
{{ $dataTable->scripts(attributes: ['type' => 'module']) }}

<script type="module">
    $(document).on('click', '.edit-btn', function() {
        let modelId = $(this).data('id');
        let editUrl = "{{ route('admin.contacts.edit', ':id') }}".replace(':id', modelId);
        AjaxRequestHandler.edit(editUrl, '#contactsModal', 'Edit Contact');
    });
    $(document).on('submit', '#contactForm', function(e) {
        e.preventDefault();
        AjaxRequestHandler.storeOrUpdate('#contactForm', '#contactsModal', '#contact-table');
    });
    $(document).ready(function() {
        $(document).on('click', '.delete-btn', function() {
            let modelId = $(this).data('id');
            let deleteUrl = "{{ route('admin.contacts.destroy', ':id') }}".replace(':id', modelId);
            AjaxRequestHandler.destroy(deleteUrl, '#contact-table');
        });
    });
</script>
@endpush