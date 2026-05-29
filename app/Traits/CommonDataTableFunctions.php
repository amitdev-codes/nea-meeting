<?php

namespace App\Traits;

use App\Enums\MeetingStatus;
use Illuminate\Support\Collection;
use Yajra\DataTables\Html\Column;

trait CommonDataTableFunctions
{
    public function parameters($instance = null, array $styleOptions = [])
    {
        $parameters = parent::parameters($instance ?? $this);
        if (! empty($styleOptions)) {
            $parameters['initComplete'] = 'function() {
                '.$this->initDataTableStyles($styleOptions).'
                '.$this->initDeleteScript().'
                '.$this->initColumnSearch().'
            }';
        }

        return array_merge(
            $parameters,
            $this->getCommonParameters(),
            $parameters['initComplete'] ? ['initComplete' => $parameters['initComplete']] : []
        );
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

    protected function initColumnSearch(): string
    {
        $dropdownColumns = $this->dropdownColumns ?? [];
        $dropdownColumnsJson = json_encode($dropdownColumns);

        return <<<JS
        var table = this.api();
        var header = $(table.table().header());

        if (!header.find('.filter-row').length) {
            header.append('<tr class="filter-row"></tr>');
        } else {
            header.find('.filter-row').empty();
        }

        // Create filter cells
        table.columns().every(function () {
            header.find('.filter-row').append('<th></th>');
        });

        // Apply dropdown filters
        {$this->initDropdownSearch($dropdownColumns)}

        // Text inputs
        table.columns().every(function (index) {
            var column = this;
            var columnData = column.dataSrc();
            var title = $(column.header()).text();
            var th = header.find('.filter-row th:eq(' + index + ')');

            if (!th.length) return;

            // Sticky columns
            if (columnData === 'checkbox') {
                th.addClass('position-sticky start-0');
                return;
            }

            if (columnData === 'action') {
                th.addClass('position-sticky end-0');
                return;
            }

            // Skip non-searchable & dropdown columns
            var dropdownColumnsJson = {$dropdownColumnsJson};
            var dropdownColumnNames = Object.keys(dropdownColumnsJson || {});

            if (
                ['{$this->implodeSearchableColumns()}'].indexOf(columnData) === -1 ||
                dropdownColumnNames.indexOf(columnData) !== -1
            ) {
                return;
            }

            // Compact input
            var input = $('<input>', {
                type: 'text',
                class: 'form-control form-control-sm column-search',
                placeholder: 'Search ' + title
            });

            input.on('keyup change', function () {
                if (column.search() !== this.value) {
                    column.search(this.value).draw();
                }
            });

            th.html(input);
        });
    JS;
    }

    protected function initDropdownSearch(array $dropdownColumns): string
    {
        $dropdownConfigs = [];
        foreach ($dropdownColumns as $columnName => $config) {
            $dropdownConfigs[] = [
                'columnName' => $columnName,
                'options' => $config['options'] ?? [],
                'searchBy' => $config['searchBy'] ?? 'value',
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

                 var select = $('<select class="form-control form-control-sm column-search" ' +
                'style="width:100%; height:26px; padding:2px 6px; font-size:12px;">' +
                '<option value="">All</option></select>');


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

    protected function implodeSearchableColumns(): string
    {
        return implode("','", $this->searchableColumns);
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

                // Ensure sticky columns maintain their background color for current theme
                const isDarkMode = document.documentElement.getAttribute("data-style") === "dark";
                const bgColor = isDarkMode ? "#222" : "white";
                const headerBgColor = isDarkMode ? "#333" : "#f8f9fa";
                const oddRowBgColor = isDarkMode ? "#2d2d2d" : "#f9f9f9";
                const evenRowBgColor = isDarkMode ? "#333" : "white";

                $("table.dataTable th.position-sticky").css("background-color", headerBgColor);
                $("table.dataTable tbody tr.odd td.position-sticky").css("background-color", oddRowBgColor);
                $("table.dataTable tbody tr.even td.position-sticky").css("background-color", evenRowBgColor);
            }',
            'createdRow' => 'function(row, data, dataIndex) {
                // Apply custom styling to the action cell
                $("td:last", row).css("width", "100px");

                // Ensure background colors match row state and theme
                const isDarkMode = document.documentElement.getAttribute("data-style") === "dark";
                const oddRowBgColor = isDarkMode ? "#2d2d2d" : "#f9f9f9";
                const evenRowBgColor = isDarkMode ? "#333" : "white";

                if ($(row).hasClass("odd")) {
                    $("td.position-sticky", row).css("background-color", oddRowBgColor);
                } else {
                    $("td.position-sticky", row).css("background-color", evenRowBgColor);
                }
            }',
        ];
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

    public function getStatusBadge($status, $activeText = 'सक्रिय छ', $inactiveText = 'सक्रिय छैन'): string
    {
        $isActive = (int) $status === 1;

        $class = $isActive ? 'bg-primary' : 'bg-danger';
        $text = $isActive ? $activeText : $inactiveText;

        return sprintf(
            '<span class="badge %s">%s</span>',
            $class,
            $text
        );
    }

    public function getBadgeStatus(string|int|null $status): string
    {
        $map = [
            'pending' => [
                'class' => 'bg-warning',
                'text' => 'Pending',
            ],
            'running' => [
                'class' => 'bg-info',
                'text' => 'Running',
            ],
            'completed' => [
                'class' => 'bg-success',
                'text' => 'Completed',
            ],
            'failed' => [
                'class' => 'bg-danger',
                'text' => 'Failed',
            ],
        ];

        // normalize (handles enums / ints / strings safely)
        $key = is_string($status)
            ? strtolower($status)
            : (string) $status;

        $config = $map[$key] ?? [
            'class' => 'bg-secondary',
            'text' => 'Unknown',
        ];

        return sprintf(
            '<span class="badge %s">%s</span>',
            $config['class'],
            e($config['text'])
        );
    }

    public function getVerificationBadge($status, $activeText = 'स्वीकृत छ', $inactiveText = 'स्वीकृत छैन'): string
    {
        $class = $status === 1 ? 'bg-primary' : 'bg-danger';
        $text = $status === 1 ? $activeText : $inactiveText;

        return sprintf(
            '<span class="badge %s">%s</span>',
            $class,
            $text
        );
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
            ->width('1%')
            ->addClass('position-sticky start-0');
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
            ->width('80px') // 🔽 reduce width
            ->addClass('text-center action-column position-sticky end-0 compact-action');
    }

    protected function renderCheckbox($model_ids, $id): string
    {
        return '<input type="checkbox" name="'.$model_ids.'" value="'.$id.'">';
    }

    protected function getCommonDom(): string
    {
        return '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>
              <"row"<"col-sm-12"B>>
              <"table-responsive"tr>
              <"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>';
    }

    protected function generateFilename(string $modelName): string
    {
        return $modelName.'_'.date('YmdHis');
    }

    protected function initBulkDeleteScript(string $checkboxName): string
    {
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

    protected function generateBadges($items, array $options = []): string
    {
        $defaultOptions = [
            'cache_key' => 'badge_color_map',
            'cache_duration' => now()->addHours(24),
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

        // Normalize and deduplicate items
        $items = $items instanceof Collection ? $items->unique()->values()->all() : array_unique((array) $items);

        // Load color map from cache
        $colorMap = cache()->remember($options['cache_key'], $options['cache_duration'], fn () => []);
        $updatedColorMap = false;

        // Prepare badge HTML
        $badges = [];
        foreach ($items as $item) {
            $key = (string) $item;

            // Handle status (boolean-like) values
            if (in_array($key, ['status', true, false, 1, 0], true)) {
                $displayText = $item ? 'Active' : 'Inactive';
                $color = $item ? 'bg-primary' : 'bg-danger';
            } else {
                // Assign color if not in colorMap
                if (! isset($colorMap[$key])) {
                    $colorMap[$key] = $options['colors'][array_rand($options['colors'])];
                    $updatedColorMap = true;
                }
                $displayText = e($key); // Escape text to prevent XSS
                $color = $colorMap[$key];
            }

            // Generate badge HTML directly
            $badges[] = '<span class="badge '.e($color).' me-1">'.$displayText.'</span>';
        }

        // Update cache only if modified
        if ($updatedColorMap) {
            cache()->put($options['cache_key'], $colorMap, $options['cache_duration']);
        }

        // Join badges with separator
        return implode($options['separator'], $badges);
    }

    protected function addActionColumn(string $formType = 'modal', array $routes = [], array $permissions = [], ?string $resourcename = null)
    {
        return fn ($data) => $this->renderActionButtons($formType, $routes, $permissions, $data, $resourcename);
    }

    protected function renderActionButtons(string $formType, array $routes, array $permissions, $data, ?string $resourcename): string
    {
        $html = '<div class="action-btn-container dropdown">';

        // VIEW button (modal)
        if (! empty($permissions['view']) && ! empty($routes['view'])) {
            $viewUrl = route($routes['view'], $data->id);
            $html .= '<a href="'.$viewUrl.'" class="btn btn-sm btn-outline-secondary btn-icon-xs" title="View">
                        <i class="bx bx-show"></i>
                      </a>';
        }

        // EDIT button (modal)
        if (! empty($permissions['edit']) && ! empty($routes['edit'])) {
            $editUrl = route($routes['edit'], $data->id);
            if ($formType === 'modal') {
                $html .= '<button type="button" class="btn btn-xs btn-outline-dark edit-btn me-1"
                             data-id="'.$data->id.'"
                             data-url="'.$editUrl.'"
                             title="Edit">
                        <i class="bx bx-edit"></i>
                      </button>';
            } else {
                $html .= '<a href="'.$editUrl.'" class="btn btn-xs btn-outline-primary btn-icon-xs" title="Edit">
                        <i class="bx bx-edit"></i>
                      </a>';
            }
        }

        // DELETE button
        if (! empty($permissions['delete']) && ! empty($routes['delete'])) {
            $deleteUrl = route($routes['delete'], $data->id);
            $html .= '<button type="button" class="btn btn-xs btn-outline-danger delete-btn"
                         data-id="'.$data->id.'"
                         data-url="'.$deleteUrl.'"
                         title="Delete">
                    <i class="bx bx-trash"></i>
                  </button>';
        }

        $html .= '</div>';

        return $html;
    }

    protected function dtActionButtons($resources, $model, array $options = [])
    {
        $permissions = $options['permissions'] ?? $this->getPermissions($resources);
        $routes = $this->getRoutes();

        if ($permissions['create']) {
            $buttons = [
                $this->exportButtons($permissions['export'] ?? 'export-'.$resources),
                $this->addButton($routes['create'] ?? route('admin.'.$resources.'.create')),
                $this->bulkDeleteButton($model, $permissions['delete']),
            ];
        } else {
            $buttons = [
                $this->exportButtons($permissions['export'] ?? 'export-'.$resources),
                $this->bulkDeleteButton($model, $permissions['delete']),
            ];
        }

        return array_filter($buttons);
    }

    protected function getPermissions($resource): array
    {
        return [
            'view' => auth()->user()->hasPermissionTo("view-{$resource}"),
            'create' => auth()->user()->hasPermissionTo("create-{$resource}"),
            'edit' => auth()->user()->hasPermissionTo("edit-{$resource}"),
            'delete' => auth()->user()->hasPermissionTo("delete-{$resource}"),
        ];
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
    protected function addButton(string $route, ?string $permission = null): array
    {
        if ($permission && ! auth()->user()->can($permission)) {
            return [];
        }

        return [
            [
                'text' => '<i class="bx bx-plus me-1"></i>'.__('field.add_new'),
                'action' => 'function() { window.location.href = "'.route($route).'"; }',
                'className' => 'btn-primary add-btn me-1',
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

        // dd('tests');

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

    protected function dtActionModalButtons($resources, $model, array $options = [])
    {
        $permissions = $options['permissions'] ?? $this->getPermissions($resources);
        $routes = $this->getRoutes() ?? [];
        $addButton = $this->addButton(
            $routes['create'] ?? route('admin.'.$resources.'s.create', [], false), // Fallback route
            $permissions['create'] // Permission check
        );
        if (! empty($addButton)) {
            $addButton[0] = array_merge($addButton[0], [
                'action' => 'function(){ return false;}', // Modal-specific action
                'className' => 'btn-primary add-btn me-2', // Override className
            ]);
        }

        $buttons = [
            $this->exportButtons($permissions['export'] ?? 'export-'.$resources), // Export with permission
            $addButton, // Single modified add button
            $this->bulkDeleteButton($model),
        ];

        return array_filter($buttons);
    }

    protected function importButton(string $routeName, string $acceptedFileTypes = '.csv,.xlsx,.xls'): array
    {
        return [
            [
                'text' => '<i class="bx bx-import me-1"></i>'.__('field.import'),
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
                            accept: "'.$acceptedFileTypes.'",
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
                                    url: "'.$routeName.'",
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
                }',
            ],
        ];
    }

    protected function importExcel(
        string $routeName,
        string $label = 'Import',
        string $acceptedFileTypes = '.csv,.xlsx,.xls'
    ): array {
        return [
            [
                'text' => '<i class="bx bx-import me-1"></i> '.$label,
                'className' => 'btn-light import-btn me-2',
                'attr' => ['style' => 'margin-left: 0px'],
                'action' => 'function() { $("#import-file-input").click(); }',
                'init' => 'function(btn, config) {
                var importInput = $("<input>")
                    .attr({
                        type: "file",
                        id: "import-file-input",
                        name: "import_file",
                        accept: "'.$acceptedFileTypes.'",
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
                                didOpen: () => { Swal.showLoading(); }
                            });

                            $.ajax({
                                url: "'.$routeName.'",
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
                                    }).then(() => { window.location.reload(); });
                                },
                                error: function(xhr) {
                                    Swal.fire({
                                        title: "Import Failed",
                                        text: xhr.responseJSON?.message || "An error occurred during import",
                                        icon: "error"
                                    });
                                }
                            });
                        }
                    });

                $("body").append(importInput);
            }',
            ],
        ];
    }

    /**
     * Add CSS styles for sticky columns to DataTables initialization
     *
     * @return string JavaScript for initializing sticky styles
     */
    protected function initStickyColumnsStyles(): string
    {
        return <<<'JS'
        if (!document.getElementById('sticky-columns-styles')) {
            $('<style id="sticky-columns-styles">').text(`
                /* Wrapper */
                .dataTables_wrapper {
                    overflow-x: auto;
                }

                /* Compact controls */
                .dataTables_wrapper
                .dataTables_wrapper .dataTables_filter input {
                    height: 26px;
                    padding: 2px 6px;
                    font-size: 12px;
                }

                .dataTables_wrapper .dataTables_info,
                .dataTables_wrapper .dataTables_paginate .page-link {
                    font-size: 12px;
                }

                /* Ultra-compact icon buttons */
                .btn-icon-xs {
                    padding: 0.25rem 0.35rem;
                    font-size: 0.75rem;
                    line-height: 1;
                }

                .btn-icon-xs i {
                    font-size: 0.85rem;
                }

                /* Action column layout */
                .action-btn-container {
                    display: inline-flex;
                    gap: 0.25rem;
                    justify-content: center;
                    align-items: center;
                    white-space: nowrap;
                }


                /* Sticky base */
                table.dataTable th.position-sticky,
                table.dataTable td.position-sticky {
                    position: sticky !important;
                    z-index: 1;
                }

                table.dataTable thead th.position-sticky {
                    z-index: 2;
                }

                /* Light mode */
                html:not([data-style="dark"]) table.dataTable th.position-sticky.start-0,
                html:not([data-style="dark"]) table.dataTable td.position-sticky.start-0 {
                    left: 0;
                    background: #fff;
                    box-shadow: 2px 0 5px -2px rgba(0,0,0,.1);
                }

                html:not([data-style="dark"]) table.dataTable th.position-sticky.end-0,
                html:not([data-style="dark"]) table.dataTable td.position-sticky.end-0 {
                    right: 0;
                    background: #fff;
                    box-shadow: -2px 0 5px -2px rgba(0,0,0,.1);
                }

                html:not([data-style="dark"]) table.dataTable thead th.position-sticky {
                    background: #f8f9fa;
                }

                html:not([data-style="dark"]) table.dataTable tbody tr:hover td.position-sticky {
                    background: #f5f5f5;
                }

                /* Dark mode */
                html[data-style="dark"] table.dataTable th.position-sticky.start-0,
                html[data-style="dark"] table.dataTable td.position-sticky.start-0 {
                    left: 0;
                    background: #222;
                    box-shadow: 2px 0 5px -2px rgba(0,0,0,.4);
                }

                html[data-style="dark"] table.dataTable th.position-sticky.end-0,
                html[data-style="dark"] table.dataTable td.position-sticky.end-0 {
                    right: 0;
                    background: #222;
                    box-shadow: -2px 0 5px -2px rgba(0,0,0,.4);
                }

                html[data-style="dark"] table.dataTable thead th.position-sticky {
                    background: #333;
                }

                html[data-style="dark"] table.dataTable tbody tr:hover td.position-sticky {
                    background: #2a2a2a;
                }
                /* Dark mode action button visibility fix */
                html[data-style="dark"] .action-btn-container .btn-outline-primary {
                    color: #ffffff;
                    border-color: #5da9ff;
                }

                html[data-style="dark"] .action-btn-container .btn-outline-primary i {
                    color: #ffffff;
                }

                html[data-style="dark"] .action-btn-container .btn-outline-secondary {
                    color: #e0e0e0;
                    border-color: #888;
                }

                html[data-style="dark"] .action-btn-container .btn-outline-danger {
                    color: #ffb3b3;
                    border-color: #ff6b6b;
                }
                    /* ── Dark mode fixes for column search inputs & selects ── */
            html[data-style="dark"] .column-search.form-control,
            html[data-style="dark"] .column-search.form-select {
                background-color: #2d3748;       /* dark gray background */
                color: #e2e8f0;                  /* light text */
                border-color: #4a5568;           /* subtle border */
            }

            html[data-style="dark"] .column-search.form-control::placeholder {
                color: #a0aec0;                  /* lighter placeholder */
                opacity: 1;
            }

            html[data-style="dark"] .column-search.form-control:focus,
            html[data-style="dark"] .column-search.form-select:focus {
                background-color: #2d3748;
                color: #e2e8f0;
                border-color: #63b3ed;           /* blue-ish focus like bootstrap primary */
                box-shadow: 0 0 0 0.2rem rgba(99, 179, 237, 0.25);
            }

            /* Optional: darker hover/focus for select options if needed */
            html[data-style="dark"] .column-search option {
                background-color: #1a202c;
                color: #e2e8f0;
            }

            /* Make sure dropdown arrow is visible in dark mode */
            html[data-style="dark"] .column-search.form-select {
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23e2e8f0' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            }

            /* Optional: fix text contrast on sticky header cells if they contain filters */
            html[data-style="dark"] table.dataTable thead th.position-sticky {
                color: #e2e8f0;
            }

            `).appendTo('head');
        }
    JS;
    }

    protected function applyGlobalSearch($query, $searchValue)
    {
        if (! $searchValue) {
            return $query;
        }

        return $query->where(function ($q) use ($searchValue) {
            foreach ($this->searchableColumns as $column) {
                $this->applyColumnSearch($q, $column, $searchValue, true);
            }
        });
    }

    protected function applyColumnSearch($query, $column, $value, $isOr = false)
    {
        if (empty($value)) {
            return $query;
        }

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
}
