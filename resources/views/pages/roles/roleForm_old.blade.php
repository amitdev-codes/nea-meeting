@extends('pages.resources.form')

@section('form-fields')
    <div class="card mb-6">
        <div class="card-body pt-4">
            <div class="row">
                <div class="mb-3 col-md-6">
                    <x-forms.input name="role_name" :label="__('field.role.name')" :value="old('role_name', $role->name ?? '')" />
                </div>


                <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-lock text-primary me-1"></i>
                        Role Permissions
                    </h5>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="permissions-toggle">
                        <label class="form-check-label" for="permissions-toggle">
                            <i class="ti ti-toggle-right me-1"></i>
                            Toggle All Permissions
                        </label>
                    </div>
                </div>
                <!-- Permissions Table -->
                <div class="table-responsive" style="max-height: 460px; overflow-y: auto;">
                    <table class="table table-bordered table-sm align-middle mb-0">
                        <thead class="sticky-top">
                            <tr class="bg-body-secondary">
                                <th rowspan="2" class="align-middle" style="min-width: 150px;">Resource Name
                                </th>
                                <th colspan="4" class="text-center py-1">Permissions</th>
                            </tr>
                            <tr class="align-middle bg-body-secondary">
                                <th class="text-center py-1" style="width: 80px;">
                                    <div class="form-check mb-0">

                                        <label class="form-check-label small" for="toggle-view">View</label>
                                    </div>
                                </th>
                                <th class="text-center py-1" style="width: 80px;">
                                    <div class="form-check mb-0">

                                        <label class="form-check-label small" for="toggle-create">Create</label>
                                    </div>
                                </th>
                                <th class="text-center py-1" style="width: 80px;">
                                    <div class="form-check mb-0">
                                        <label class="form-check-label small" for="toggle-edit">Edit</label>
                                    </div>
                                </th>
                                <th class="text-center py-1" style="width: 80px;">
                                    <div class="form-check mb-0">
                                        <label class="form-check-label small" for="toggle-delete">Delete</label>
                                    </div>
                                </th>
                            </tr>
                            <tr class="align-middle bg-body-secondary">
                                <th></th>
                                <th class="text-center py-1">
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="checkbox" id="toggle-view">
                                    </div>
                                </th>
                                <th class="text-center py-1">
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="checkbox" id="toggle-create">
                                    </div>
                                </th>
                                <th class="text-center py-1">
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="checkbox" id="toggle-edit">
                                    </div>
                                </th>
                                <th class="text-center py-1">
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="checkbox" id="toggle-delete">
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $resources = [
                                    ['name' => 'Users', 'icon' => 'user'],
                                    ['name' => 'Roles', 'icon' => 'shield'],
                                    ['name' => 'Products', 'icon' => 'box'],
                                    ['name' => 'Categories', 'icon' => 'folder'],
                                    ['name' => 'Orders', 'icon' => 'cart'],
                                    ['name' => 'Reports', 'icon' => 'file'],
                                    ['name' => 'Customers', 'icon' => 'group'],
                                    ['name' => 'Invoices', 'icon' => 'file'],
                                    ['name' => 'Payments', 'icon' => 'credit-card'],
                                    ['name' => 'Settings', 'icon' => 'cog'],
                                ];
                            @endphp

                            @foreach ($resources as $resource)
                                <tr>
                                    <td class="py-1">
                                        <i class="bx bx-{{ $resource['icon'] }} text-primary me-1"></i>
                                        {{ $resource['name'] }}
                                    </td>
                                    <td class="text-center py-1">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input view-permission" type="checkbox"
                                                name="permissions[{{ strtolower($resource['name']) }}][view]">
                                        </div>
                                    </td>
                                    <td class="text-center py-1">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input create-permission" type="checkbox"
                                                name="permissions[{{ strtolower($resource['name']) }}][create]">
                                        </div>
                                    </td>
                                    <td class="text-center py-1">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input edit-permission" type="checkbox"
                                                name="permissions[{{ strtolower($resource['name']) }}][edit]">
                                        </div>
                                    </td>
                                    <td class="text-center py-1">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input delete-permission" type="checkbox"
                                                name="permissions[{{ strtolower($resource['name']) }}][delete]">
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="module">
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle All Permissions functionality
            document.getElementById('permissions-toggle').addEventListener('change', function() {
                const checkboxes = document.querySelectorAll(
                    'input[type="checkbox"]:not(#permissions-toggle)');
                checkboxes.forEach(checkbox => checkbox.checked = this.checked);
            });

            // Column toggle functionality
            document.getElementById('toggle-view').addEventListener('change', function() {
                const viewCheckboxes = document.querySelectorAll('.view-permission');
                viewCheckboxes.forEach(checkbox => checkbox.checked = this.checked);
            });

            document.getElementById('toggle-create').addEventListener('change', function() {
                const createCheckboxes = document.querySelectorAll('.create-permission');
                createCheckboxes.forEach(checkbox => checkbox.checked = this.checked);
            });

            document.getElementById('toggle-edit').addEventListener('change', function() {
                const editCheckboxes = document.querySelectorAll('.edit-permission');
                editCheckboxes.forEach(checkbox => checkbox.checked = this.checked);
            });

            document.getElementById('toggle-delete').addEventListener('change', function() {
                const deleteCheckboxes = document.querySelectorAll('.delete-permission');
                deleteCheckboxes.forEach(checkbox => checkbox.checked = this.checked);
            });
        });
    </script>
@endpush
