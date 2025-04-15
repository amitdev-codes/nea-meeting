@extends('pages.resources.form')

@section('form-fields')
    <div class="row">
        <div class="mb-3 col-md-6">
            <x-forms.input name="name" :label="__('field.role.name')" :value="old('name', $model->name ?? '')" required />
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

        <div class="table-responsive" style="max-height: 460px; overflow-y: auto; position: relative;">
            <table class="table table-bordered table-sm align-middle mb-0">
                <thead class="sticky-top" style="background-color: #f8f9fa; z-index: 1; top: 0;">
                    <tr class="bg-body-secondary">
                        <th rowspan="2" class="align-middle" style="min-width: 150px;">Resource Name</th>
                        <th colspan="6" class="text-center py-1">Permissions</th>
                    </tr>
                    <tr class="align-middle bg-body-secondary">
                        <th class="text-center py-1" style="width: 80px;"><label class="form-check-label small" for="toggle-view">View</label></th>
                        <th class="text-center py-1" style="width: 80px;"><label class="form-check-label small" for="toggle-create">Create</label></th>
                        <th class="text-center py-1" style="width: 80px;"><label class="form-check-label small" for="toggle-edit">Edit</label></th>
                        <th class="text-center py-1" style="width: 80px;"><label class="form-check-label small" for="toggle-delete">Delete</label></th>
                        <th class="text-center py-1" style="width: 80px;"><label class="form-check-label small" for="toggle-import">Import</label></th>
                        <th class="text-center py-1" style="width: 80px;"><label class="form-check-label small" for="toggle-export">Export</label></th>
                    </tr>
                    <tr class="align-middle bg-body-secondary">
                        <th></th>
                        <th class="text-center py-1"><input class="form-check-input" type="checkbox" id="toggle-view"></th>
                        <th class="text-center py-1"><input class="form-check-input" type="checkbox" id="toggle-create"></th>
                        <th class="text-center py-1"><input class="form-check-input" type="checkbox" id="toggle-edit"></th>
                        <th class="text-center py-1"><input class="form-check-input" type="checkbox" id="toggle-delete"></th>
                        <th class="text-center py-1"><input class="form-check-input" type="checkbox" id="toggle-import"></th>
                        <th class="text-center py-1"><input class="form-check-input" type="checkbox" id="toggle-export"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($resources as $resource)
                        @php
                            $key = $resource->type === 'resource'
                                ? strtolower($resource->name)
                                : ($resource->type === 'route'
                                    ? ($resource->route_name && (str_contains($resource->route_name, '.import') || str_contains($resource->route_name, '.export'))
                                        ? str_replace(['import-', 'export-'], '', strtolower($resource->name))
                                        : $resource->route_name)
                                    : strtolower($resource->name));
                            $displayName = str_replace('-', ' ', $resource->name);
                            $displayName = ucwords($displayName);
                            $isRouteImport = $resource->type === 'route' && str_contains($resource->route_name, '.import');
                            $isRouteExport = $resource->type === 'route' && str_contains($resource->route_name, '.export');
                            $isUrlType = $resource->type === 'url';
                            $isRouteViewOnly = $resource->type === 'route' && !$isRouteImport && !$isRouteExport;
                            $modelPermissions = $model ? $model->getAllPermissions()->pluck('name')->toArray() : [];
                        @endphp
                        <tr>
                            <td class="py-1">
                                <i class="bx {{ $resource->icon ?? 'bx-box' }} text-primary me-1"></i>
                                {{ $displayName }}
                                <small class="text-muted">({{ $resource->type }})</small>
                            </td>
                            <!-- View Permission -->
                            <td class="text-center py-1">
                                @if ($resource->type === 'resource' || $isUrlType || $isRouteViewOnly)
                                    <div class="form-check mb-0">
                                        <input class="form-check-input view-permission" type="checkbox"
                                            name="permissions[{{ $key }}][view]"
                                            {{ $model && in_array("view-{$key}", $modelPermissions) ? 'checked' : '' }}>
                                    </div>
                                @else
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="checkbox" disabled>
                                    </div>
                                @endif
                            </td>
                            <!-- Create Permission -->
                            <td class="text-center py-1">
                                @if ($resource->type === 'resource')
                                    <div class="form-check mb-0">
                                        <input class="form-check-input create-permission" type="checkbox"
                                            name="permissions[{{ $key }}][create]"
                                            {{ $model && in_array("create-{$key}", $modelPermissions) ? 'checked' : '' }}>
                                    </div>
                                @else
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="checkbox" disabled>
                                    </div>
                                @endif
                            </td>
                            <!-- Edit Permission -->
                            <td class="text-center py-1">
                                @if ($resource->type === 'resource')
                                    <div class="form-check mb-0">
                                        <input class="form-check-input edit-permission" type="checkbox"
                                            name="permissions[{{ $key }}][edit]"
                                            {{ $model && in_array("edit-{$key}", $modelPermissions) ? 'checked' : '' }}>
                                    </div>
                                @else
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="checkbox" disabled>
                                    </div>
                                @endif
                            </td>
                            <!-- Delete Permission -->
                            <td class="text-center py-1">
                                @if ($resource->type === 'resource')
                                    <div class="form-check mb-0">
                                        <input class="form-check-input delete-permission" type="checkbox"
                                            name="permissions[{{ $key }}][delete]"
                                            {{ $model && in_array("delete-{$key}", $modelPermissions) ? 'checked' : '' }}>
                                    </div>
                                @else
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="checkbox" disabled>
                                    </div>
                                @endif
                            </td>
                            <!-- Import Permission -->
                            <td class="text-center py-1">
                                @if ($resource->type === 'resource' || $isRouteImport)
                                    <div class="form-check mb-0">
                                        <input class="form-check-input import-permission" type="checkbox"
                                            name="permissions[{{ $key }}][import]"
                                            {{ $model && in_array("import-{$key}", $modelPermissions) ? 'checked' : '' }}>
                                    </div>
                                @else
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="checkbox" disabled>
                                    </div>
                                @endif
                            </td>
                            <!-- Export Permission -->
                            <td class="text-center py-1">
                                @if ($resource->type === 'resource' || $isRouteExport)
                                    <div class="form-check mb-0">
                                        <input class="form-check-input export-permission" type="checkbox"
                                            name="permissions[{{ $key }}][export]"
                                            {{ $model && in_array("export-{$key}", $modelPermissions) ? 'checked' : '' }}>
                                    </div>
                                @else
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="checkbox" disabled>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="module">
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('permissions-toggle').addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('input[type="checkbox"]:not(#permissions-toggle):not([disabled])');
                checkboxes.forEach(checkbox => checkbox.checked = this.checked);
            });

            document.getElementById('toggle-view').addEventListener('change', function() {
                document.querySelectorAll('.view-permission:not([disabled])').forEach(checkbox => checkbox.checked = this.checked);
            });
            document.getElementById('toggle-create').addEventListener('change', function() {
                document.querySelectorAll('.create-permission:not([disabled])').forEach(checkbox => checkbox.checked = this.checked);
            });
            document.getElementById('toggle-edit').addEventListener('change', function() {
                document.querySelectorAll('.edit-permission:not([disabled])').forEach(checkbox => checkbox.checked = this.checked);
            });
            document.getElementById('toggle-delete').addEventListener('change', function() {
                document.querySelectorAll('.delete-permission:not([disabled])').forEach(checkbox => checkbox.checked = this.checked);
            });
            document.getElementById('toggle-import').addEventListener('change', function() {
                document.querySelectorAll('.import-permission:not([disabled])').forEach(checkbox => checkbox.checked = this.checked);
            });
            document.getElementById('toggle-export').addEventListener('change', function() {
                document.querySelectorAll('.export-permission:not([disabled])').forEach(checkbox => checkbox.checked = this.checked);
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .table-responsive { position: relative; }
        .sticky-top { position: sticky; top: 0; z-index: 1; background-color: #f8f9fa; }
        th, td { vertical-align: middle !important; }
        .list-unstyled li:hover { color: #000; background-color: #f8f9fa; padding-left: 0.5rem; transition: all 0.2s; }
    </style>
@endpush