<?php

namespace App\Traits;

use App\Enums\MeetingStatus;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;

trait CommonDataTableFunctions
{
   

    /**
     * Add a checkbox column to the DataTable.
     */
    protected function checkboxColumn(): Column
    {
        return Column::make('checkbox')
            ->title('<input type="checkbox" id="select-all">')
            ->orderable(false)
            ->searchable(false)
            ->exportable(false)
            ->printable(false)
            ->width('1%');
    }

    /**
     * Add action columns (view, edit, delete) to the DataTable.
     *
     * @param  string  $routePrefix  The route prefix (e.g., 'admin.users')
     */
    protected function actionColumn(): Column
    {
        return Column::computed('action')
            ->title(__('field.action'))
            ->exportable(false)
            ->searchable(false)
            ->printable(false)
            ->width('10%');
    }


    /**
     * Make a column inline editable
     * 
     * @param string $field The field/column name
     * @param mixed $value The field value
     * @param mixed $id The row ID
     * @return string HTML for the editable cell
     */
    protected function renderEditableCell(string $field, $value, $id, string $type = 'text', array $options = []): string
    {
        $optionsAttr = $type === 'select' ? 'data-options="' . htmlspecialchars(json_encode($options)) . '"' : '';
        return '<div class="editable-cell w-100 h-100" data-field="' . $field . '" data-id="' . $id . '" data-type="' . $type . '" ' . $optionsAttr . '>' . $value . '</div>';
    }
    protected function renderCheckbox($model_ids, $id): string
    {
        return view('components.datatables.checkbox', ['name' => $model_ids, 'id' => $id])->render();
    }

    protected function getCommonDom(): string
    {
        return "<'row align-items-center'<'col-md-3'l><'col-md-6 text-center'B><'col-md-3'f>>".
               "<'row'<'col-md-12'tr>>".
               "<'row'<'col-md-6'i><'col-md-6'p>>";
    }

    protected function generateFilename(string $modelName): string
    {
        return $modelName.'_'.date('YmdHis');
    }
        /**
     * Get common parameters for all DataTables
     * This includes text wrapping and action button styling
     * 
     * @return array Common parameters
     */
    protected function getCommonParameters(): array
    {
        return [
            'drawCallback' => 'function() {
                // Apply text wrapping to columns
                $(".wrap-text").css({
                    "white-space": "normal",
                    "word-break": "break-word"
                });
                    
                // Make action buttons display inline
                $(".action-btn-container").css({
                    "display": "flex",
                    "flex-wrap": "wrap",
                    "gap": "2px"
                });
                
                const isDarkMode = document.documentElement.getAttribute("data-style") === "dark";
                const bgColor = isDarkMode ? "#222" : "white";
                const headerBgColor = isDarkMode ? "#333" : "#f8f9fa";
                const oddRowBgColor = isDarkMode ? "#2d2d2d" : "#f9f9f9";
                const evenRowBgColor = isDarkMode ? "#333" : "white";

            }',
            'createdRow' => 'function(row, data, dataIndex) {
                // Apply custom styling to the action cell
                $("td:last", row).css("width", "100px");
                
                // Ensure background colors match row state and theme
                const isDarkMode = document.documentElement.getAttribute("data-style") === "dark";
                const oddRowBgColor = isDarkMode ? "#2d2d2d" : "#f9f9f9";
                const evenRowBgColor = isDarkMode ? "#333" : "white";
            
            }'  
        ];
    }
    public function parameters($instance = null, array $styleOptions = [])
    {
        $parameters = parent::parameters($instance ?? $this);
        if (!empty($styleOptions)) {
            $parameters['initComplete'] = 'function() {
                ' . $this->initDataTableStyles($styleOptions) . '
                ' . $this->initDeleteScript() . '
                ' . $this->initColumnSearch() . '
            }';
        }
        
        return array_merge(
            $parameters,
            $this->getCommonParameters(),
            $parameters['initComplete'] ? ['initComplete' => $parameters['initComplete']] : []
        );
    }

    /**
     * Add export buttons to the DataTable.
     */
    protected function exportButtons(): array
    {
        return [
            [
                'extend' => 'collection',
                'className' => 'btn-light export-btn me-2',
                'text' => '<i class="bx bx-export me-1"></i> '.__('field.export'),
                'buttons' => [
                    ['extend' => 'excel', 'className' => 'btn-sm', 'text' => '<i class="bx bx-file me-1"></i> '.__('field.buttons.excel')],
                    ['extend' => 'pdf', 'className' => 'btn-sm', 'text' => '<i class="bx bxs-file-pdf me-1"></i> '.__('field.buttons.pdf')],
                    ['extend' => 'print', 'className' => 'btn-sm', 'text' => '<i class="bx bx-printer me-1"></i> '.__('field.buttons.print')],
                    ['extend' => 'csv', 'className' => 'btn-sm', 'text' => '<i class="bx bx-file me-1"></i> '.__('field.buttons.csv')],
                    ['extend' => 'copy', 'className' => 'btn-sm', 'text' => '<i class="bx bx-copy me-1"></i> '.__('field.buttons.copy')],
                ],
            ],
        ];
    }

    /**
     * Add a "Add New" button to the DataTable.
     *
     * @param  string  $route  The route for creating a new item
     */
    protected function addButton(string $route, string $permission = null): array
    {
        if ($permission && !auth()->user()->can($permission)) {
            return [];
        }
    
        return [
            [
                'text' => '<i class="bx bx-plus me-1"></i>'.__('field.add_new'),
                'action' => 'function() { window.location.href = "'.route($route).'"; }',
                'className' => 'btn-primary add-btn me-2', 
            ],
        ];
    }

    /**
     * Add a bulk delete button to the DataTable.
     *
     * @param  string  $model  The model name (e.g., 'User')
     */
    protected function bulkDeleteButton(string $model): array
    {
        
        $checkboxName = strtolower($model.'_ids[]');

 

        return [
            [
                'text' => '<div class="d-flex align-items-center bulk-delete-content"><i class="bx bx-trash me-2"></i><span class="bulk-delete-label">Delete</span><span class="bulk-delete-count ms-2"></span></div>',
                'className' => 'btn-danger btn-sm d-none',
                'attr' => ['id' => 'bulk-delete-btn'],
                'action' => 'function() {
                    var selectedIds = [];
                     $("input[name=\''.$checkboxName.'\']:checked").each(function() {
                        selectedIds.push($(this).val());
                    });

                    if (selectedIds.length > 0) {
                        Swal.fire({
                            title: "Confirm Deletion",
                            text: "Are you sure you want to delete " + selectedIds.length + " selected items?",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#dc3545",
                            cancelButtonColor: "#6c757d",
                            confirmButtonText: "Yes, delete them!"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: "'.route('admin.bulkDelete', ['model' => $model]).'",
                                    method: "POST",
                                    data: {
                                        ids: selectedIds,
                                        _token: "'.csrf_token().'"
                                    },
                                    success: function(response) {
                                        Swal.fire(
                                            "Deleted!",
                                            "Selected items have been deleted.",
                                            "success"
                                        ).then(() => {
                                            window.location.reload();
                                        });
                                    },
                                    error: function(response) {
                                        Swal.fire(
                                            "Error!",
                                            "An error occurred while deleting the items.",
                                            "error"
                                        );
                                    }
                                });
                            }
                        });
                    }
                }',
            ],
        ];
    }

    /**
     * Generate the JavaScript for handling single item deletion
     */
    protected function initDeleteScript(): string
    {
        return '
        $(document).on("click", ".delete-item", function() {
            var id = $(this).data("id");
            var table = $("#" + window.LaravelDataTables[Object.keys(window.LaravelDataTables)[0]].id);

            Swal.fire({
                title: "Are you sure?",
                text: "You won\'t be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: window.location.pathname + "/" + id,
                        type: "DELETE",
                        data: {
                            "_token": $("meta[name=\'csrf-token\']").attr("content")
                        },
                        success: function(response) {
                            Swal.fire(
                                    "Deleted!",
                                    "Record has been deleted.",
                                    "success"
                                    ).then(() => {
                                        window.location.reload();
                                    });
                        },
                        error: function(xhr) {
                            Swal.fire(
                                "Error!",
                                "Something went wrong.",
                                "error"
                            );
                        }
                    });
                }
            });
        });
    ';
    }

    /**
     * Initialize the inline editing functionality for DataTables
     * 
     * @param string $routePrefix The route prefix (e.g., 'admin.provinces')
     * @param string $model The model name in lowercase (e.g., 'province')
     * @return string JavaScript for inline editing
     */

    protected function initInlineEditingScript(string $resource): string
    {
        return '
        // Add styles for editable cells
        if (!document.getElementById("inline-editing-styles")) {
            $("<style id=\"inline-editing-styles\">")
                .text(`
                    .editable-cell { cursor: pointer; }
                    .editable-cell:hover { background-color: #f8f9fa; }
                    .editing-cell { padding: 0 !important; }
                    .form-control-sm { width: 100%; }
                `)
                .appendTo("head");
        }

        // Click handler for editable cells
        $(document).off("click", ".editable-cell").on("click", ".editable-cell", function(e) {
            if ($(this).hasClass("being-edited")) return;

            const cell = $(this);
            const originalValue = cell.text().trim(); // Default for text/number
            const field = cell.data("field");
            const id = cell.data("id");
            const type = cell.data("type");
            const options = cell.data("options") || []; // For select type

            // Create input container
            const inputContainer = $("<div class=\"p-1\"></div>");
            let input;

            // Generate input based on type
            if (type === "select") {
                input = $("<select class=\"form-control form-control-sm\"></select>");
                $.each(options, function(value, label) {
                    input.append($("<option></option>").attr("value", value).text(label));
                });
                input.val(cell.data("options")[originalValue] ? originalValue : Object.keys(options)[0]); // Set default or original value
            } else if (type === "number") {
                input = $("<input type=\"number\" class=\"form-control form-control-sm\" />").val(originalValue);
            } else {
                input = $("<input type=\"text\" class=\"form-control form-control-sm\" />").val(originalValue);
            }

            // Replace cell content with input
            cell.html(inputContainer.append(input));
            cell.addClass("being-edited");
            cell.parent().addClass("editing-cell");
            input.focus();

            // Function to save changes
            function saveChanges(newValue) {
                cell.html("<i class=\"fas fa-spinner fa-spin\"></i>"); // Loading indicator

                const baseUrl = window.location.pathname.split("/").slice(0, -1).join("/");
                const inlineEditUrl = `${baseUrl}/'.$resource.'/${id}/inline-edit`;

                $.ajax({
                    url: inlineEditUrl,
                    method: "PATCH",
                    headers: {
                        "X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr("content")
                    },
                    data: {
                        field: field,
                        value: newValue
                    },
                    success: function(response) {
                        cell.html(type === "select" ? options[newValue] : newValue); // Display label for select
                        cell.removeClass("being-edited");
                        cell.parent().removeClass("editing-cell");
                        toastr.success(response.message || "Updated successfully");
                    },
                    error: function(xhr) {
                        cell.html(originalValue);
                        cell.removeClass("being-edited");
                        cell.parent().removeClass("editing-cell");
                        toastr.error(xhr.responseJSON?.message || "Update failed");
                    }
                });
            }

            // Save on blur (all types)
            input.on("blur", function() {
                const newValue = input.val();
                if (newValue !== originalValue) {
                    saveChanges(newValue);
                } else {
                    cell.html(originalValue);
                    cell.removeClass("being-edited");
                    cell.parent().removeClass("editing-cell");
                }
            });

            // Save on Enter key (text and number)
            if (type !== "select") {
                input.on("keypress", function(e) {
                    if (e.which === 13) { // Enter key
                        const newValue = input.val();
                        saveChanges(newValue);
                    }
                });
            }

            // Save on change (select)
            if (type === "select") {
                input.on("change", function() {
                    const newValue = input.val();
                    saveChanges(newValue);
                });
            }

            // Cancel on Escape key (all types)
            input.on("keydown", function(e) {
                if (e.which === 27) { // Escape key
                    cell.html(originalValue);
                    cell.removeClass("being-edited");
                    cell.parent().removeClass("editing-cell");
                }
            });

            e.stopPropagation();
        });
        ';
    }
    protected function initBulkDeleteScript(string $checkboxName): string
    {
        if(auth()->user()->hasRole(['admin', 'superadmin'])) {
            return '
                $("#select-all").on("click", function() {
                    var isChecked = this.checked;
                    $("input[name=\''.$checkboxName.'\']").prop("checked", isChecked);
                    updateBulkDeleteButton();
                });

                $(document).on("change", "input[name=\''.$checkboxName.'\']", function() {
                    updateBulkDeleteButton();
                    if (!$(this).prop("checked")) {
                        $("#select-all").prop("checked", false);
                    } else {
                        // Check if all checkboxes are checked
                        if ($("input[name=\''.$checkboxName.'\']").length === $("input[name=\''.$checkboxName.'\']:checked").length) {
                            $("#select-all").prop("checked", true);
                        }
                    }
                });

                function updateBulkDeleteButton() {
                    var checkedCount = $("input[name=\''.$checkboxName.'\']:checked").length;
                    var bulkDeleteBtn = $("#bulk-delete-btn");

                    if (checkedCount > 0) {
                        bulkDeleteBtn.removeClass("d-none");
                        $(".bulk-delete-count").html("(" + checkedCount + " selected)");
                    } else {
                        bulkDeleteBtn.addClass("d-none");
                        $(".bulk-delete-count").html("");
                    }
                }
            ';
        }
        return '';
    }

    protected function generateBadges($items, array $options = []): string
    {
        $defaultOptions = [
            'cache_duration' => now()->addHours(24),
            'cache_key' => 'badge_color_map',
            'separator' => ' ',
            'colors' => [
                'bg-primary',
                'bg-secondary',
                'bg-success',
                'bg-danger',
                'bg-warning',
                'bg-info',
                'bg-warning text-dark',
                'bg-info text-dark',
                'bg-light text-dark',
                'bg-dark',
            ],
        ];
    
        $options = array_merge($defaultOptions, $options);
    
        if ($items instanceof \Illuminate\Support\Collection) {
            $items = $items->toArray();
        }
    
        if (!is_array($items)) {
            $items = [$items];
        }
    
        $colorMap = cache()->remember($options['cache_key'], $options['cache_duration'], function () {
            return [];
        });
    
        return collect($items)
            ->map(function ($item) use ($options, &$colorMap) {
                $key = (string) $item;


                // Check if the item represents a status (boolean-like value)
                if ($key === 'status' || in_array($item, [true, false, 1, 0], true)) {
                    // Determine the display text and color based on boolean value
                    $displayText = $item ? 'Active' : 'Inactive'; // Customize as needed
                    $color = $item ? 'bg-primary' : 'bg-danger';
                } else {
                    // dd('text',$key);
                    // For non-status items, use the existing random color logic
                    if (!isset($colorMap[$key])) {
                        $colorMap[$key] = $options['colors'][array_rand($options['colors'])];
                        cache()->put($options['cache_key'], $colorMap, $options['cache_duration']);
                    }
                    $displayText = $item;
                    $color = $colorMap[$key];
                }
    
                return view('components.datatables.badge', [
                    'class' => $color,
                    'text' => $displayText,
                ])->render();
            })
            ->implode($options['separator']);
    }
    public function getStatusBadge($status): string
    {
        $class = $status == 1 ? 'bg-primary' : 'bg-danger';
        $text = $status == 1 ? __('field.active') : __('field.inactive');
        // $text = $status == 1 ? $activeText : $inactiveText;

        return sprintf(
            '<span class="badge %s">%s</span>',
            $class,
            $text
        );
    }
    protected function addActionColumn(string $formType = 'modal', array $routes = [], array $permissions = [])
    {
        return fn ($data) => view('components.action-buttons', [
            'formType' => $formType, // Dynamically set formType
            'data' => $data,
            'id' => $data->id,
            'viewRoute' => $routes['view'] ? route($routes['view'], $data->id) : null,
            'editRoute' => $routes['edit'] ? route($routes['edit'], $data->id) : null,
            'deleteRoute' => $routes['delete'] ? route($routes['delete'], $data->id) : null, 
            'viewPermission' => $permissions['view'] ?? null, 
            'editPermission' => $permissions['edit'] ?? null, 
            'deletePermission' => $permissions['delete'] ?? null, 
        ]);
    }

    protected function dtActionButtons($resources,$model,array $options = [])
    {
        $permissions = $options['permissions'] ?? $this->getPermissions($resources);
        $routes = $this->getRoutes();
        $buttons = [
            $this->exportButtons($permissions['export'] ?? 'export-' .$resources), // Default export permission
            $this->addButton(
                $routes['create'] ?? route("admin." .$resources . ".create"), // Fallback route
                $permissions['create']
            ),
            $this->bulkDeleteButton($model),
        ];
    
        return array_filter($buttons);
    }


    protected function dtActionModalButtons($resources, $model, array $options = [])
    {
        $permissions = $options['permissions'] ?? $this->getPermissions($resources);
        $routes = $this->getRoutes() ?? []; 
        $addButton = $this->addButton(
            $routes['create'] ?? route("admin." . $resources . "s.create", [], false), // Fallback route
            $permissions['create'] // Permission check
        );
        if (!empty($addButton)) {
            $addButton[0] = array_merge($addButton[0], [
                'action' => 'function(){ return false;}', // Modal-specific action
                'className' => 'btn-primary add-btn me-2', // Override className
            ]);
        }

        $buttons = [
            $this->exportButtons($permissions['export'] ?? 'export-' . $resources), // Export with permission
            $addButton, // Single modified add button
            $this->bulkDeleteButton($model),
        ];

        return array_filter($buttons);
    }

    protected function initDropdownSearch(array $dropdownColumns): string
    {
        $dropdownConfigs = [];
        foreach ($dropdownColumns as $columnName => $config) {
            $dropdownConfigs[] = [
                'columnName' => $columnName,
                'options' => $config['options'] ?? [],
                'searchBy' => $config['searchBy'] ?? 'value'
            ];
        }
    
        $jsConfigs = json_encode($dropdownConfigs);
    
        return <<<JS
            var table = this.api();
            var dropdownConfigs = {$jsConfigs};
    
            //console.log('Dropdown Configs:', dropdownConfigs);
    
            if (!Array.isArray(dropdownConfigs)) {
                console.warn('dropdownConfigs is not an array:', dropdownConfigs);
                dropdownConfigs = [];
            }
    
            // Get all column definitions for debugging
            var columns = table.columns().dataSrc();
            //console.log('Table Columns:', columns);
    
            $.each(dropdownConfigs, function(index, config) {
                // Try to find the column by name
                var column = table.column(config.columnName + ':name'); // Add ':name' to match Yajra's convention
                if (!column) {
                    //console.warn('Column not found by name:', config.columnName + ':name');
                    // Fallback: Try without ':name'
                    column = table.column(config.columnName);
                    if (!column) {
                        //console.warn('Column still not found by raw name:', config.columnName);
                        return;
                    }
                }
    
                var columnIndex = column.index();
               // console.log('Applying dropdown to', config.columnName, 'at index:', columnIndex);
    
                var select = $('<select class="form-control form-control-sm column-search" style="width: 100%;"><option value="">All</option></select>');
    
                var options = config.options || {};
                //console.log('Options for', config.columnName, ':', options);
                $.each(options, function(value, text) {
                    select.append($('<option></option>').attr('value', value).text(text));
                });
    
                var header = $(table.table().header());
                var filterCell = header.find('.filter-row th:eq(' + columnIndex + ')');
                if (filterCell.length) {
                    filterCell.html(select);
                    //console.log('Dropdown added to', config.columnName, 'at index:', columnIndex);
                } else {
                   // console.warn('Filter cell not found for index:', columnIndex);
                }
    
                select.on('change', function() {
                    var val = $.fn.dataTable.util.escapeRegex($(this).val());
                    column.search(val ? val : '', true, false).draw();
                });
    
                var searchValue = column.search();
                //console.log('Search value for', config.columnName, ':', searchValue);
                if (searchValue && typeof searchValue === 'string') {
                    select.val(searchValue.replace(/^|$/g, ''));
                }
            });
    JS;
    }
    protected function initColumnSearch(): string
    {
        $dropdownColumns = $this->dropdownColumns??[];
        $dropdownColumnsJson = json_encode($dropdownColumns);
    
        return <<<JS
            var table = this.api();
    
            var header = $(table.table().header());
            if (!header.find('.filter-row').length) {
                header.append('<tr class="filter-row"></tr>');
            } else {
                header.find('.filter-row').empty();
            }
    
            // Add a <th> for every column
            table.columns().every(function(index) {
                var th = $('<th></th>');
                $('.filter-row').append(th);
            });

            // Apply dropdowns
            {$this->initDropdownSearch($dropdownColumns)}

            // Add text search for remaining searchable columns
            table.columns().every(function(index) {
                var column = this;
                var columnData = column.dataSrc();
                var title = $(column.header()).text();

    
                var th = header.find('.filter-row th:eq(' + index + ')');
                if (!th.length) {
                    return;
                }
    
                const isDarkMode = document.documentElement.getAttribute('data-style') === 'dark';
                console.log(isDarkMode);
                // console.log(columnData);
   
                if (columnData === 'checkbox') {
                    th.addClass('position-sticky start-0 sorting_disabled sorting_desc"').attr('style', 'left: 0; z-index: 1');


                } else if (columnData === 'action') {
                    th.addClass('position-sticky end-0')
                      .attr('style', 'right: 0; z-index: 1; min-width: 120px;' + (isDarkMode ? 'background-color: #333 !important;' : ''));
                }
    
                var dropdownColumnsJson = {$dropdownColumnsJson};
                var dropdownColumnNames = (dropdownColumnsJson && typeof dropdownColumnsJson === 'object') ? Object.keys(dropdownColumnsJson) : [];
                if (['{$this->implodeSearchableColumns()}'].indexOf(columnData) === -1 || 
                    dropdownColumnNames.indexOf(columnData) !== -1) {
                    //console.log('Skipping text input for:', columnData);
                    return;
                }
    
                var input = $('<input type="text" class="form-control form-control-sm column-search" placeholder="Search ' + title + '"/>')
                    .on('keyup change', function() {
                        if (column.search() !== this.value) {
                            column.search(this.value).draw();
                        }
                    });
    
                th.html(input);
                //console.log('Text input added to:', columnData);
            });
    
            this.api().draw();
        JS;
    }
    protected function implodeSearchableColumns(): string
    {
        return implode("','", $this->searchableColumns);
    }
    public function routes(): array
    {
        $routes = [];
        foreach ($this->getRoutes() as $key => $routeName) {
            if (in_array($key, ['edit', 'delete', 'view'])) {
                $routes[$key] = route($routeName, [':id']);
            } else {
                $routes[$key] = route($routeName);
            }
        }
        return $routes;
    }
    protected function importButton(string $routeName, string $acceptedFileTypes = '.csv,.xlsx,.xls'): array
    {
        return [
            [
                'text' => '<i class="bx bx-import me-1"></i>' . __('field.import'),
                'className' => 'btn-light import-btn me-2',
                'attr' => ['style' => 'margin-left: 0px'],
                'action' => 'function() {
                    $("#import-file-input").click();
                }',
                'init' => 'function(btn, config) {
                    var importInput = $("<input>")
                        .attr({
                            type: "file",
                            id: "import-file-input",
                            name: "import_file",
                            accept: "' . $acceptedFileTypes . '",
                            style: "display: none;"
                        })
                        .on("change", function(e) {
                            var file = e.target.files[0];
                            if (file) {
                                var formData = new FormData();
                                formData.append("import_file", file);
    
                                Swal.fire({
                                    title: "Importing Data",
                                    text: "Please wait while the file is being processed...",
                                    icon: "info",
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });
    
                                $.ajax({
                                    url: "' . $routeName . '",
                                    type: "POST",
                                    data: formData,
                                    processData: false,
                                    contentType: false,
                                    headers: {
                                        "X-CSRF-TOKEN": $("meta[name=\'csrf-token\']").attr("content")
                                    },
                                    success: function(response) {
                                        Swal.fire({
                                            title: "Import Successful",
                                            text: response.message || "Data imported successfully",
                                            icon: "success"
                                        }).then(() => {
                                            window.location.reload();
                                        });
                                    },
                                    error: function(xhr) {
                                        Swal.fire({
                                            title: "Import Failed",
                                            text: xhr.responseJSON.message || "An error occurred during import",
                                            icon: "error"
                                        });
                                    }
                                });
                            }
                        });
    
                    $("body").append(importInput);
                }'
            ]
        ];
    }
        /**
     * Add CSS styles for sticky columns to DataTables initialization
     * 
     * @return string JavaScript for initializing sticky styles
     */

    protected function getPermissions($resource): array
    {
        return [
            'view' => "view-{$resource}",
            'create' => "create-{$resource}",
            'edit' => "edit-{$resource}",
            'delete' => "delete-{$resource}",
            'export' => "export-{$resource}",
        ];
    }
    // starts advanced filter and column searching
    protected function applyGlobalSearch($query, $searchValue)
    {
        if (!$searchValue) return $query;
        
        return $query->where(function ($q) use ($searchValue) {
            foreach ($this->searchableColumns as $column) {
                $this->applyColumnSearch($q, $column, $searchValue, true);
            }
        });
    }
    
    protected function applyColumnSearch($query, $column, $value, $isOr = false)
    {
        if (empty($value)) return $query;
        
        $method = $isOr ? 'orWhere' : 'where';
        
        // Handle dropdown fields first (exact matches)
        if (isset($this->dropdownFields[$column])) {
            $fieldName = $this->dropdownFields[$column];
            return $query->$method($fieldName, $value);
        }
        
        // Handle relationships
        if (isset($this->relationshipColumns[$column])) {
            $relation = $this->relationshipColumns[$column];
            return $query->$method(function ($q) use ($relation, $value) {
                foreach ($relation['fields'] as $field) {
                    $q->orWhere("{$relation['table']}.{$field}", 'like', "%{$value}%");
                }
            });
        }
        
        // Handle multi-field columns (like name and name_np)
        if (isset($this->multiFieldColumns[$column])) {
            return $query->$method(function ($q) use ($column, $value) {
                foreach ($this->multiFieldColumns[$column] as $field) {
                    $q->orWhere($field, 'like', "%{$value}%");
                }
            });
        }
        
        // Handle exact match columns
        if (in_array($column, $this->exactMatchColumns)) {
            return $query->$method("{$this->tableName}.{$column}", $value);
        }
        
        // Regular column search
        return $query->$method("{$this->tableName}.{$column}", 'like', "%{$value}%");
    }
    
    protected function applyColumnSpecificSearch($query)
    {
        if (!request()->has('columns')) return $query;
        
        foreach (request('columns') as $column) {
            $value = $column['search']['value'] ?? '';
            if ($value === '') continue;
            
            $columnData = $column['data'];
            if (!in_array($columnData, $this->searchableColumns)) continue;
            
            $this->applyColumnSearch($query, $columnData, $value);
        }
        
        return $query;
    }
    public function getMeetingStatusBadge($status): string
    {
        // Convert string to enum if necessary
        $status = $status instanceof MeetingStatus ? $status : MeetingStatus::from($status);
        // Map enum cases to Bootstrap badge classes
        $statusStyles = [
            MeetingStatus::Scheduled->value => 'bg-info',
            MeetingStatus::Ongoing->value => 'bg-warning',
            MeetingStatus::Completed->value => 'bg-success',
            MeetingStatus::Cancelled->value => 'bg-danger',
        ];

        $class = $statusStyles[$status->value] ?? 'bg-secondary'; // Fallback class
        $text = ucfirst(strtolower($status->value)); // Format text (e.g., "Scheduled")

        return sprintf(
            '<span class="badge %s">%s</span>',
            $class,
            $text
        );
    }
    protected function initStickyColumnsStyles(): string
    {
        return <<<JS
            if (!document.getElementById("sticky-columns-styles")) {
                $("<style id=\"sticky-columns-styles\">")
                    .text(`
                        .dataTables_wrapper {
                            overflow-x: auto;
                        }
                        table.dataTable th.position-sticky,
                        table.dataTable td.position-sticky {
                            position: sticky !important;
                            z-index: 1;
                        }
                        /* Light mode styles */
                        html:not([data-style="dark"]) table.dataTable th.position-sticky.start-0,
                        html:not([data-style="dark"]) table.dataTable td.position-sticky.start-0 {
                            left: 0;
                            box-shadow: 2px 0 5px -2px rgba(0,0,0,0.1);
                            background-color: white;
                        }
                        html:not([data-style="dark"]) table.dataTable th.position-sticky.end-0,
                        html:not([data-style="dark"]) table.dataTable td.position-sticky.end-0 {
                            right: 0;
                            box-shadow: -2px 0 5px -2px rgba(0,0,0,0.1);
                            background-color: white;
                        }
                        html:not([data-style="dark"]) table.dataTable thead th.position-sticky {
                            background-color: #f8f9fa;
                            z-index: 2;
                        }
                        html:not([data-style="dark"]) table.dataTable tbody tr:hover td.position-sticky {
                            background-color: #f5f5f5 !important;
                        }
                        html:not([data-style="dark"]) table.dataTable.stripe tbody tr.odd td.position-sticky {
                            background-color: #f9f9f9;
                        }
                        
                        /* Dark mode styles */
                        html[data-style="dark"] table.dataTable th.position-sticky.start-0,
                        html[data-style="dark"] table.dataTable td.position-sticky.start-0 {
                            left: 0;
                            box-shadow: 2px 0 5px -2px rgba(0,0,0,0.3);
                            background-color: #222;
                        }
                        html[data-style="dark"] table.dataTable th.position-sticky.end-0,
                        html[data-style="dark"] table.dataTable td.position-sticky.end-0 {
                            right: 0;
                            box-shadow: -2px 0 5px -2px rgba(0,0,0,0.3);
                            background-color: #222;
                        }
                        html[data-style="dark"] table.dataTable thead th.position-sticky {
                            background-color: #333;
                            z-index: 2;
                        }
                        html[data-style="dark"] table.dataTable tbody tr:hover td.position-sticky {
                            background-color: #2a2a2a !important;
                        }
                        html[data-style="dark"] table.dataTable.stripe tbody tr.odd td.position-sticky {
                            background-color: #2d2d2d;
                        }
                        html[data-style="dark"] table.dataTable.stripe tbody tr.even td.position-sticky {
                            background-color: #333;
                        }
                        
                        /* Make sure checkbox and action buttons are visible in dark mode */
                        html[data-style="dark"] table.dataTable .position-sticky .form-check-input,
                        html[data-style="dark"] table.dataTable .position-sticky .action-btn {
                            background-color: #444;
                            border-color: #666;
                        }
                        html[data-style="dark"] table.dataTable .position-sticky .form-check-input:checked {
                            background-color: #0d6efd;
                        }
                    `)
                    .appendTo("head");
            }
            
            // Add a class to DataTable wrapper for styling
            $(this.api().table().container()).addClass('with-sticky-columns');
            
            // Apply appropriate styles based on current theme
            const applyThemeStyles = function() {
                const isDarkMode = document.documentElement.getAttribute('data-style') === 'dark';
                const table = $(this.api().table().node());
                const bgColor = isDarkMode ? '#222' : 'white';
                const headerBgColor = isDarkMode ? '#333' : '#f8f9fa';
                
                table.find('td.position-sticky').css('background-color', bgColor);
                table.find('th.position-sticky').css('background-color', headerBgColor);
                
                // Handle striped rows
                if (isDarkMode) {
                    table.find('tr.odd td.position-sticky').css('background-color', '#2d2d2d');
                    table.find('tr.even td.position-sticky').css('background-color', '#333');
                } else {
                    table.find('tr.odd td.position-sticky').css('background-color', '#f9f9f9');
                    table.find('tr.even td.position-sticky').css('background-color', 'white');
                }
            }.bind(this);
            
            // Apply theme styles on init
            applyThemeStyles();
            
            // Reapply styles when theme changes
            document.addEventListener('themeChanged', applyThemeStyles);
    JS;
    }

}