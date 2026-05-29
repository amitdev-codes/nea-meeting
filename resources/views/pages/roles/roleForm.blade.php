@extends('pages.resources.form')

@section('form-fields')
    <div class="row">
        <div class="mb-3 col-md-6">
            <x-forms.input name="name" :label="__('field.role.name')" :value="old('name', $model->name ?? '')"
                           required/>
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

        @php
            $modelPermissions = $model ? $model->getAllPermissions()->pluck('name')->toArray() : [];
            $groupedResources = $resources->groupBy('type');
        @endphp

            <!-- Resource Type Permissions (Full CRUD) -->
        @if ($groupedResources->has('resource'))
            <div class="mb-4">
                <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="ti ti-database text-success me-1"></i>
                        Resource Permissions (Full CRUD)
                    </h6>
                    <div class="form-check form-switch">
                        <input class="form-check-input section-toggle" type="checkbox" id="toggle-resource-section">
                        <label class="form-check-label small" for="toggle-resource-section">
                            Toggle All Resources
                        </label>
                    </div>
                </div>
                <div class="table-responsive" style="max-height: 460px; overflow-y: auto;">
                    <table class="table table-bordered table-sm align-middle mb-0">
                        <thead class="sticky-top" style="background-color: #f8f9fa; z-index: 10;">
                        <tr class="bg-body-secondary">
                            <th rowspan="2" class="align-middle" style="min-width: 150px;">Resource Name</th>
                            <th colspan="6" class="text-center py-1">Permissions</th>
                        </tr>
                        <tr class="align-middle bg-body-secondary">
                            <th class="text-center py-1" style="width: 80px;">
                                <label class="form-check-label small">View</label>
                            </th>
                            <th class="text-center py-1" style="width: 80px;">
                                <label class="form-check-label small">Create</label>
                            </th>
                            <th class="text-center py-1" style="width: 80px;">
                                <label class="form-check-label small">Edit</label>
                            </th>
                            <th class="text-center py-1" style="width: 80px;">
                                <label class="form-check-label small">Delete</label>
                            </th>
                        </tr>
                        <tr class="align-middle bg-body-secondary">
                            <th></th>
                            <th class="text-center py-1">
                                <input class="form-check-input column-toggle" type="checkbox" data-action="view">
                            </th>
                            <th class="text-center py-1">
                                <input class="form-check-input column-toggle" type="checkbox" data-action="create">
                            </th>
                            <th class="text-center py-1">
                                <input class="form-check-input column-toggle" type="checkbox" data-action="edit">
                            </th>
                            <th class="text-center py-1">
                                <input class="form-check-input column-toggle" type="checkbox" data-action="delete">
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($groupedResources['resource'] as $resource)
                            @php
                                $key = strtolower($resource->name);
                                $displayName = ucwords(str_replace('-', ' ', $resource->name));
                            @endphp
                            <tr>
                                <td class="py-1">
                                    <i class="bx {{ $resource->icon ?? 'bx-box' }} text-primary me-1"></i>
                                    {{ $displayName }}
                                </td>
                                @foreach (['view', 'create', 'edit', 'delete'] as $action)
                                    <td class="text-center py-1">
                                        <div class="form-check mb-0">
                                            <input
                                                class="form-check-input permission-checkbox resource-permission {{ $action }}-permission"
                                                type="checkbox" name="permissions[]"
                                                value="{{ $action }}-{{ $key }}"
                                                {{ $model && in_array("{$action}-{$key}", $modelPermissions) ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Route Type Permissions (Single Permission) -->
        @if ($groupedResources->has('route'))
            <div class="mb-4">
                <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="ti ti-route text-info me-1"></i>
                        Route Permissions (Single Access)
                    </h6>
                    <div class="form-check form-switch">
                        <input class="form-check-input section-toggle" type="checkbox" id="toggle-route-section">
                        <label class="form-check-label small" for="toggle-route-section">
                            Toggle All Routes
                        </label>
                    </div>
                </div>
                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-bordered table-sm align-middle mb-0">
                        <thead class="sticky-top" style="background-color: #f8f9fa; z-index: 10;">
                        <tr class="bg-body-secondary">
                            <th style="min-width: 200px;">Route Name</th>
                            <th class="text-center" style="width: 100px;">Permission</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($groupedResources['route'] as $resource)
                            @php
                                $key = $resource->route_name;
                                $displayName = ucwords(str_replace(['-', '_'], ' ', $resource->name));
                                $isImport = str_contains($resource->route_name, '.import');
                                $isExport = str_contains($resource->route_name, '.export');
                                $action = $isImport ? 'import' : ($isExport ? 'export' : 'view');
                                $permKey = str_replace(['import-', 'export-'], '', strtolower($resource->name));
                            @endphp
                            <tr>
                                <td class="py-1">
                                    <i class="bx {{ $resource->icon ?? 'bx-link' }} text-info me-1"></i>
                                    {{ $displayName }}
                                    <small class="text-muted d-block">{{ $resource->route_name }}</small>
                                </td>
                                <td class="text-center py-1">
                                    <div class="form-check mb-0">
                                        <input
                                            class="form-check-input permission-checkbox route-permission {{ $action }}-permission"
                                            type="checkbox" name="permissions[]"
                                            value="{{ $action }}-{{ $permKey }}"
                                            {{ $model && in_array("{$action}-{$permKey}", $modelPermissions) ? 'checked' : '' }}>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- URL Type Permissions (Single Permission) -->
        @if ($groupedResources->has('url'))
            <div class="mb-4">
                <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="ti ti-link text-warning me-1"></i>
                        URL Permissions (Single Access)
                    </h6>
                    <div class="form-check form-switch">
                        <input class="form-check-input section-toggle" type="checkbox" id="toggle-url-section">
                        <label class="form-check-label small" for="toggle-url-section">
                            Toggle All URLs
                        </label>
                    </div>
                </div>
                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-bordered table-sm align-middle mb-0">
                        <thead class="sticky-top" style="background-color: #f8f9fa; z-index: 10;">
                        <tr class="bg-body-secondary">
                            <th style="min-width: 200px;">URL Name</th>
                            <th class="text-center" style="width: 100px;">Permission</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($groupedResources['url'] as $resource)
                            @php
                                $key = strtolower($resource->name);
                                $displayName = ucwords(str_replace(['-', '_'], ' ', $resource->name));
                            @endphp
                            <tr>
                                <td class="py-1">
                                    <i class="bx {{ $resource->icon ?? 'bx-globe' }} text-warning me-1"></i>
                                    {{ $displayName }}
                                </td>
                                <td class="text-center py-1">
                                    <div class="form-check mb-0">
                                        <input
                                            class="form-check-input permission-checkbox url-permission view-permission"
                                            type="checkbox" name="permissions[]" value="view-{{ $key }}"
                                            {{ $model && in_array("view-{$key}", $modelPermissions) ? 'checked' : '' }}>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Additional/Standalone Permissions -->
        @php
            $allPermissions = \Spatie\Permission\Models\Permission::all();

            // Get all resource-based permissions
            $resourcePermissions = [];
            foreach ($resources as $resource) {
                if ($resource->type === 'resource') {
                    $key = strtolower($resource->name);
                    foreach (['view', 'create', 'edit', 'delete', 'import', 'export'] as $action) {
                        $resourcePermissions[] = "{$action}-{$key}";
                    }
                } elseif ($resource->type === 'route') {
                    $isImport = str_contains($resource->route_name, '.import');
                    $isExport = str_contains($resource->route_name, '.export');
                    $action = $isImport ? 'import' : ($isExport ? 'export' : 'view');
                    $permKey = str_replace(['import-', 'export-'], '', strtolower($resource->name));
                    $resourcePermissions[] = "{$action}-{$permKey}";
                } elseif ($resource->type === 'url') {
                    $key = strtolower($resource->name);
                    $resourcePermissions[] = "view-{$key}";
                }
            }

            // Get standalone permissions
            $standalonePermissions = $allPermissions->whereNotIn('name', $resourcePermissions);
        @endphp

        @if ($standalonePermissions->count() > 0)
            <div class="mb-4">
                <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="ti ti-settings text-secondary me-1"></i>
                        Additional Permissions ({{ $standalonePermissions->count() }})
                    </h6>
                    <div class="form-check form-switch">
                        <input class="form-check-input section-toggle" type="checkbox" id="toggle-standalone-section">
                        <label class="form-check-label small" for="toggle-standalone-section">
                            Toggle All Additional
                        </label>
                    </div>
                </div>
                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-bordered table-sm align-middle mb-0">
                        <thead class="sticky-top bg-light" style="z-index: 10;">
                        <tr class="bg-body-secondary">
                            <th style="min-width: 200px;">Permission Name</th>
                            <th class="text-center" style="width: 100px;">Assign</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($standalonePermissions as $permission)
                            <tr>
                                <td class="py-1">
                                    <strong>{{ ucwords(str_replace(['-', '_'], ' ', $permission->name)) }}</strong>
                                </td>
                                <td class="text-center py-1">
                                    <div class="form-check mb-0">
                                        <input class="form-check-input permission-checkbox standalone-permission"
                                               type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                            {{ $model && in_array($permission->name, $modelPermissions) ? 'checked' : '' }}>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script type="module">
        document.addEventListener('DOMContentLoaded', function () {
            // Toggle all permissions (master toggle)
            document.getElementById('permissions-toggle').addEventListener('change', function () {
                const checkboxes = document.querySelectorAll('.permission-checkbox:not([disabled])');
                checkboxes.forEach(checkbox => checkbox.checked = this.checked);

                // Update all section toggles
                document.querySelectorAll('.section-toggle, .column-toggle').forEach(toggle => {
                    toggle.checked = this.checked;
                });
            });

            // Section toggles (toggle by type)
            document.getElementById('toggle-resource-section')?.addEventListener('change', function () {
                const checkboxes = document.querySelectorAll('.resource-permission:not([disabled])');
                checkboxes.forEach(checkbox => checkbox.checked = this.checked);
                updateColumnToggleStates();
                updateToggleAllState();
            });

            document.getElementById('toggle-route-section')?.addEventListener('change', function () {
                const checkboxes = document.querySelectorAll('.route-permission:not([disabled])');
                checkboxes.forEach(checkbox => checkbox.checked = this.checked);
                updateToggleAllState();
            });

            document.getElementById('toggle-url-section')?.addEventListener('change', function () {
                const checkboxes = document.querySelectorAll('.url-permission:not([disabled])');
                checkboxes.forEach(checkbox => checkbox.checked = this.checked);
                updateToggleAllState();
            });

            document.getElementById('toggle-standalone-section')?.addEventListener('change', function () {
                const checkboxes = document.querySelectorAll('.standalone-permission:not([disabled])');
                checkboxes.forEach(checkbox => checkbox.checked = this.checked);
                updateToggleAllState();
            });

            // Column toggles for resource table (by action)
            document.querySelectorAll('.column-toggle').forEach(toggle => {
                toggle.addEventListener('change', function () {
                    const action = this.dataset.action;
                    const checkboxes = document.querySelectorAll(
                        `.resource-permission.${action}-permission:not([disabled])`);
                    checkboxes.forEach(checkbox => checkbox.checked = this.checked);
                    updateSectionToggleState('resource');
                    updateToggleAllState();
                });
            });

            // Update "toggle all" state based on individual checkboxes
            function updateToggleAllState() {
                const allCheckboxes = document.querySelectorAll('.permission-checkbox:not([disabled])');
                const checkedCheckboxes = document.querySelectorAll('.permission-checkbox:not([disabled]):checked');
                const toggleAll = document.getElementById('permissions-toggle');

                if (allCheckboxes.length === checkedCheckboxes.length) {
                    toggleAll.checked = true;
                    toggleAll.indeterminate = false;
                } else if (checkedCheckboxes.length > 0) {
                    toggleAll.checked = false;
                    toggleAll.indeterminate = true;
                } else {
                    toggleAll.checked = false;
                    toggleAll.indeterminate = false;
                }
            }

            // Update section toggle state
            function updateSectionToggleState(section) {
                const sectionCheckboxes = document.querySelectorAll(`.${section}-permission:not([disabled])`);
                const checkedSectionCheckboxes = document.querySelectorAll(
                    `.${section}-permission:not([disabled]):checked`);
                const sectionToggle = document.getElementById(`toggle-${section}-section`);

                if (!sectionToggle) return;

                if (sectionCheckboxes.length === checkedSectionCheckboxes.length && sectionCheckboxes.length > 0) {
                    sectionToggle.checked = true;
                    sectionToggle.indeterminate = false;
                } else if (checkedSectionCheckboxes.length > 0) {
                    sectionToggle.checked = false;
                    sectionToggle.indeterminate = true;
                } else {
                    sectionToggle.checked = false;
                    sectionToggle.indeterminate = false;
                }
            }

            // Update column toggle states (for resource table)
            function updateColumnToggleStates() {
                document.querySelectorAll('.column-toggle').forEach(toggle => {
                    const action = toggle.dataset.action;
                    const actionCheckboxes = document.querySelectorAll(
                        `.resource-permission.${action}-permission:not([disabled])`);
                    const checkedActionCheckboxes = document.querySelectorAll(
                        `.resource-permission.${action}-permission:not([disabled]):checked`);

                    if (actionCheckboxes.length === checkedActionCheckboxes.length && actionCheckboxes
                        .length > 0) {
                        toggle.checked = true;
                        toggle.indeterminate = false;
                    } else if (checkedActionCheckboxes.length > 0) {
                        toggle.checked = false;
                        toggle.indeterminate = true;
                    } else {
                        toggle.checked = false;
                        toggle.indeterminate = false;
                    }
                });
            }

            // Listen to all permission checkbox changes
            document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    // Determine which section this checkbox belongs to
                    if (this.classList.contains('resource-permission')) {
                        updateSectionToggleState('resource');
                        updateColumnToggleStates();
                    } else if (this.classList.contains('route-permission')) {
                        updateSectionToggleState('route');
                    } else if (this.classList.contains('url-permission')) {
                        updateSectionToggleState('url');
                    } else if (this.classList.contains('standalone-permission')) {
                        updateSectionToggleState('standalone');
                    }

                    updateToggleAllState();
                });
            });

            // Initial state update
            updateToggleAllState();
            updateSectionToggleState('resource');
            updateSectionToggleState('route');
            updateSectionToggleState('url');
            updateSectionToggleState('standalone');
            updateColumnToggleStates();
        });
    </script>
@endpush

@push('vendor-style')
    <style>
        .table-responsive {
            position: relative;
        }

        .sticky-top {
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #f8f9fa;
        }

        th,
        td {
            vertical-align: middle !important;
        }

        .form-check-inline {
            margin-right: 0.5rem;
            margin-bottom: 0.25rem;
        }

        /* Indeterminate checkbox styling */
        input[type="checkbox"]:indeterminate {
            background-color: #0d6efd;
            border-color: #0d6efd;
            opacity: 0.7;
        }
    </style>
    @endpush
    </document_content>
