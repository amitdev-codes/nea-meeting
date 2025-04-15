<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class MakeAll extends Command
{
    protected $signature = 'make:all {name} {module?} {--table=} {--connection=mysql}';
    protected $description = 'Create Controller, Model, Requests, DataTable and Views for a component with automated field detection';

    protected $excludedFillableColumns = ['id', 'created_at', 'updated_at', 'deleted_at'];
    protected $booleanColumns = ['status', 'is_active', 'active', 'enabled', 'published', 'featured', 'verified'];
    protected $tableColumns = [];
    protected $tableColumnTypes = [];

    public function handle()
    {
        $name = $this->argument('name');
        $module = $this->argument('module');
        $tableName = $this->option('table') ?: Str::plural(Str::snake($name));
        $connection = $this->option('connection');

        $isModular = !empty($module);

        // Process the name to ensure proper formatting
        $name = ucfirst($name);
        $namePlural = Str::plural(Str::snake($name, '-'));
        $nameSnake = Str::snake($name);
        $nameCamel = Str::camel($name);
        $nameLower = strtolower($name);
        $namePluralLower = Str::plural($nameLower);

        // Try to fetch table columns if the table exists
        try {
            if (Schema::connection($connection)->hasTable($tableName)) {
                $this->fetchTableColumns($tableName, $connection);
                $this->info("Found table '{$tableName}' with " . count($this->tableColumns) . " columns");
            } else {
                $this->warn("Table '{$tableName}' does not exist. Using default templates.");
            }
        } catch (\Exception $e) {
            $this->warn("Error checking table structure: " . $e->getMessage());
        }

        // Define base paths
        if ($isModular) {
            $basePath = base_path("Modules/{$module}");
            $namespace = "Modules\\{$module}";
            $viewPath = "Modules/{$module}/resources/views/pages/{$namePluralLower}";
        } else {
            $basePath = app_path();
            $namespace = "App";
            $viewPath = resource_path("views/pages/{$namePluralLower}");
        }

        // Create all components
        $this->createController($name, $namespace, $basePath, $isModular);
        $this->createModel($name, $namespace, $basePath, $isModular, $tableName);
        $this->createRequests($name, $namespace, $basePath, $isModular);
        $this->createDataTable($name, $namespace, $basePath, $isModular);
        $this->createViews($name, $viewPath, $nameLower);

        $this->info("All components for {$name} have been created successfully!");

        return Command::SUCCESS;
    }

    protected function fetchTableColumns($tableName, $connection = 'mysql')
    {
        // Get all columns
        $columns = Schema::connection($connection)->getColumnListing($tableName);
        
        // Get column type information using DB facade
        $columnsInfo = DB::connection($connection)
            ->select("SHOW COLUMNS FROM {$tableName}");
        
        // Convert column info to associative array
        foreach ($columnsInfo as $column) {
            $this->tableColumnTypes[$column->Field] = $column->Type;
        }
        
        // Filter out excluded columns
        $this->tableColumns = array_values(array_diff($columns, $this->excludedFillableColumns));
    }

    protected function createController($name, $namespace, $basePath, $isModular)
    {
        $controllerNamespace = $isModular ? "{$namespace}\\Http\\Controllers" : "{$namespace}\\Http\\Controllers";
        $nameLower = strtolower($name);
        $moduleLower = $this->argument('module') ? strtolower($this->argument('module')) : '';
        $modelNamespace = $isModular ? "Modules\\{$this->argument('module')}\\Models\\" : "App\\Models\\";
        $controllerPath = $isModular
             ? "{$basePath}/app/Http/Controllers/{$name}Controller.php"
            : "{$basePath}/Http/Controllers/{$name}Controller.php";

        if (File::exists($controllerPath)) {
            $this->info("Controller already exists: {$controllerPath}");
            return;
        }

        $controllerStub = $this->getControllerStub();
 
        $controllerStub = str_replace(
            [
                '{{namespace}}', 
                '{{name}}', 
                '{{nameLower}}', 
                '{{moduleLower}}', 
                '{{modelNamespace}}', 
                '{{dataTableNamespace}}', 
                '{{requestNamespace}}'
            ],
            [
                $controllerNamespace, 
                $name, 
                $nameLower, 
                $moduleLower, 
                $modelNamespace, 
                $isModular ? "{$namespace}\\DataTables" : "App\\DataTables",
                $isModular ? "{$namespace}\\Http\\Requests" : "App\\Http\\Requests"
            ],
            $controllerStub
        );

        $this->createDirectory(dirname($controllerPath));
        File::put($controllerPath, $controllerStub);

        $this->info("Controller created: {$controllerPath}");
    }

    protected function createModel($name, $namespace, $basePath, $isModular, $tableName)
    {
        $modelNamespace = $isModular ? "{$namespace}\\Models" : "{$namespace}\\Models";
        $modelPath = $isModular
             ? "{$basePath}/app/Models/{$name}.php"
            : "{$basePath}/Models/{$name}.php";

        if (File::exists($modelPath)) {
            $this->info("Model already exists: {$modelPath}");
            return;
        }

        // Prepare fillable fields
        $fillableFields = empty($this->tableColumns) 
            ? "['name', 'code', 'description', 'status']" 
            : $this->formatArrayForCode($this->tableColumns);

        // Prepare casts
        $casts = $this->generateCasts();

        $modelStub = $this->getModelStub();
        $modelStub = str_replace(
            ['{{namespace}}', '{{name}}', '{{fillable}}', '{{casts}}', '{{table}}'],
            [$modelNamespace, $name, $fillableFields, $casts, $tableName],
            $modelStub
        );

        $this->createDirectory(dirname($modelPath));
        File::put($modelPath, $modelStub);

        $this->info("Model created: {$modelPath}");
    }

    protected function generateCasts()
    {
        $casts = [];
        
        // Always cast status as boolean if it exists
        if (in_array('status', $this->tableColumns)) {
            $casts[] = "'status' => 'boolean'";
        }
        
        // Add other boolean fields
        foreach ($this->tableColumns as $column) {
            if (in_array($column, $this->booleanColumns) && $column !== 'status') {
                $casts[] = "'{$column}' => 'boolean'";
            }
        }
        
        // Add type-based casts based on column types
        foreach ($this->tableColumnTypes as $column => $type) {
            if (in_array($column, $this->excludedFillableColumns)) {
                continue;
            }
            
            if (strpos($type, 'datetime') !== false || strpos($type, 'timestamp') !== false) {
                $casts[] = "'{$column}' => 'datetime'";
            } else if (strpos($type, 'json') !== false) {
                $casts[] = "'{$column}' => 'array'";
            } else if (strpos($type, 'decimal') !== false || strpos($type, 'double') !== false || strpos($type, 'float') !== false) {
                $casts[] = "'{$column}' => 'float'";
            } else if (strpos($type, 'int') !== false) {
                $casts[] = "'{$column}' => 'integer'";
            }
        }
        
        return empty($casts) ? "[]" : "[\n        " . implode(",\n        ", $casts) . "\n    ]";
    }

    protected function createRequests($name, $namespace, $basePath, $isModular)
    {
        $requestNamespace = $isModular ? "{$namespace}\\Http\\Requests" : "{$namespace}\\Http\\Requests";
        $storePath = $isModular
             ? "{$basePath}/app/Http/Requests/Store{$name}Request.php"
            : "{$basePath}/Http/Requests/Store{$name}Request.php";
        $updatePath = $isModular
             ? "{$basePath}/app/Http/Requests/Update{$name}Request.php"
            : "{$basePath}/Http/Requests/Update{$name}Request.php";

        $requestDir = dirname($storePath);
        $this->createDirectory($requestDir);

        // Generate validation rules based on table columns
        $validationRules = $this->generateValidationRules();

        if (!File::exists($storePath)) {
            $storeStub = $this->getStoreRequestStub();
            $storeStub = str_replace(
                ['{{namespace}}', '{{name}}', '{{validationRules}}'],
                [$requestNamespace, $name, $validationRules],
                $storeStub
            );
            File::put($storePath, $storeStub);
            $this->info("Store Request created: {$storePath}");
        } else {
            $this->info("Store Request already exists: {$storePath}");
        }

        if (!File::exists($updatePath)) {
            $updateStub = $this->getUpdateRequestStub();
            $updateStub = str_replace(
                ['{{namespace}}', '{{name}}', '{{validationRules}}'],
                [$requestNamespace, $name, $validationRules],
                $updateStub
            );
            File::put($updatePath, $updateStub);
            $this->info("Update Request created: {$updatePath}");
        } else {
            $this->info("Update Request already exists: {$updatePath}");
        }
    }

    protected function generateValidationRules()
    {
        if (empty($this->tableColumns)) {
            return "[\n            'name' => 'required|string|max:255',\n            'status' => 'boolean',\n        ]";
        }
        
        $rules = [];
        
        foreach ($this->tableColumns as $column) {
            $type = $this->tableColumnTypes[$column] ?? '';
            
            if ($column === 'name' || $column === 'title') {
                $rules[] = "'{$column}' => 'required|string|max:255'";
            } else if ($column === 'email') {
                $rules[] = "'{$column}' => 'required|email|max:255'";
            } else if ($column === 'code') {
                $rules[] = "'{$column}' => 'required|string|max:50|unique:{{table}},code'";
            } else if (in_array($column, $this->booleanColumns)) {
                $rules[] = "'{$column}' => 'boolean'";
            } else if (strpos($type, 'varchar') !== false) {
                // Extract max length from varchar type
                preg_match('/varchar\((\d+)\)/', $type, $matches);
                $maxLength = isset($matches[1]) ? $matches[1] : 255;
                $rules[] = "'{$column}' => 'nullable|string|max:{$maxLength}'";
            } else if (strpos($type, 'text') !== false) {
                $rules[] = "'{$column}' => 'nullable|string'";
            } else if (strpos($type, 'int') !== false) {
                $rules[] = "'{$column}' => 'nullable|integer'";
            } else if (strpos($type, 'decimal') !== false || strpos($type, 'double') !== false || strpos($type, 'float') !== false) {
                $rules[] = "'{$column}' => 'nullable|numeric'";
            } else if (strpos($type, 'date') !== false) {
                $rules[] = "'{$column}' => 'nullable|date'";
            } else {
                $rules[] = "'{$column}' => 'nullable'";
            }
        }
        
        return "[\n            " . implode(",\n            ", $rules) . "\n        ]";
    }

    protected function createDataTable($name, $namespace, $basePath, $isModular)
    {
        $datatableNamespace = $isModular ? "{$namespace}\\DataTables" : "{$namespace}\\DataTables";
        $nameLower = strtolower($name);
        $datatablePath = $isModular
             ? "{$basePath}/app/DataTables/{$name}DataTable.php"
            : "{$basePath}/app/DataTables/{$name}DataTable.php";

        if (File::exists($datatablePath)) {
            $this->info("DataTable already exists: {$datatablePath}");
            return;
        }

        $modelNamespace = $isModular ? "{$namespace}\\Models\\{$name}" : "{$namespace}\\Models\\{$name}";

        // Generate searchable columns and datatable columns based on table structure
        $searchableColumns = $this->generateSearchableColumns();
        $datatableColumns = $this->generateDatatableColumns();

        $datatableStub = $this->getDataTableStub();
        $datatableStub = str_replace(
            [
                '{{namespace}}', 
                '{{name}}', 
                '{{nameLower}}', 
                '{{modelNamespace}}',
                '{{searchableColumns}}',
                '{{datatableColumns}}'
            ],
            [
                $datatableNamespace, 
                $name, 
                $nameLower, 
                $modelNamespace,
                $searchableColumns,
                $datatableColumns
            ],
            $datatableStub
        );

        $this->createDirectory(dirname($datatablePath));
        File::put($datatablePath, $datatableStub);

        $this->info("DataTable created: {$datatablePath}");
    }

    protected function generateSearchableColumns()
    {
        $searchColumns = [];
        
        // Common searchable columns
        $commonSearchableFields = ['id', 'name', 'title', 'code', 'email', 'description'];
        
        if (!empty($this->tableColumns)) {
            foreach ($commonSearchableFields as $field) {
                if (in_array($field, $this->tableColumns)) {
                    $searchColumns[] = $field;
                }
            }
        } else {
            $searchColumns = ['name', 'code'];
        }
        
        // Make sure we have at least name and code as searchable
        if (empty($searchColumns)) {
            $searchColumns = ['name', 'code'];
        }
        
        return $this->formatArrayForCode($searchColumns);
    }

    protected function generateDatatableColumns()
    {
        $columns = [];
        
        // Always add checkbox column
        $columns[] = '$this->checkboxColumn()';
        
        if (!empty($this->tableColumns)) {
            // Prioritize these common columns if they exist
            $priorityColumns = ['id', 'code', 'name', 'title', 'email'];
            
            foreach ($priorityColumns as $column) {
                if (in_array($column, $this->tableColumns) && count($columns) < 5) {
                    $columns[] = "Column::make('{$column}')->title(__('{$column}'))";
                }
            }
            
            // If we have status, add it
            if (in_array('status', $this->tableColumns)) {
                $columns[] = "Column::make('status')->title(__('field.status'))";
            }
            
            // Add a few more columns if we don't have enough yet
            foreach ($this->tableColumns as $column) {
                if (!in_array($column, $priorityColumns) && count($columns) < 5 && 
                    !in_array($column, $this->booleanColumns) && 
                    !in_array("Column::make('{$column}')->title(__('{$column}'))", $columns)) {
                    $columns[] = "Column::make('{$column}')->title(__('{$column}'))";
                }
            }
        } else {
            // Default columns if table doesn't exist
            $columns[] = "Column::make('code')->title(__('field.code'))";
            $columns[] = "Column::make('name')->title(__('field.name'))";
            $columns[] = "Column::make('status')->title(__('field.status'))";
        }
        
        // Always add action column at the end
        $columns[] = '$this->actionColumn()';
        
        return implode(",\n            ", $columns);
    }

    protected function createViews($name, $viewPath, $nameLower)
    {
        $formFields = $this->generateFormFields();
        $detailItems = $this->generateDetailItems();
        
        $formViewPath = "{$viewPath}/{$nameLower}Form.blade.php";
        if (!File::exists($formViewPath)) {
            $formViewStub = $this->getFormViewStub();
            $formViewStub = str_replace(
                ['{{name}}', '{{formFields}}'],
                [$nameLower, $formFields],
                $formViewStub
            );
            $this->createDirectory(dirname($formViewPath));
            File::put($formViewPath, $formViewStub);
            $this->info("Form View created: {$formViewPath}");
        } else {
            $this->info("Form View already exists: {$formViewPath}");
        }

        $showViewPath = "{$viewPath}/show.blade.php";
        if (!File::exists($showViewPath)) {
            $showViewStub = $this->getShowViewStub();
            $showViewStub = str_replace(
                ['{{name}}', '{{detailItems}}'],
                [$nameLower, $detailItems],
                $showViewStub
            );
            File::put($showViewPath, $showViewStub);
            $this->info("Show View created: {$showViewPath}");
        } else {
            $this->info("Show View already exists: {$showViewPath}");
        }
    }

    protected function generateFormFields()
    {
        if (empty($this->tableColumns)) {
            return <<<HTML
    <div class="mb-3 col-md-4">
        <x-forms.input name="name" :label="__('field.name')" :value="old('name', \$model->name ?? '')" />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input name="code" :label="__('field.code')" :value="old('code', \$model->code ?? '')" />
    </div>
    <div class="col-md-4 mt-6">
        <x-forms.input-switch name="status" label="status" :value="old('status', \$model->status ?? 1)" />
    </div>
HTML;
        }
        
        $html = [];
        $columnCount = 0;
        
        // Process priority fields first
        $priorityFields = ['name', 'title', 'code', 'email'];
        foreach ($priorityFields as $field) {
            if (in_array($field, $this->tableColumns)) {
                $html[] = $this->getFormFieldByType($field);
                $columnCount++;
            }
        }
        
        // Process boolean fields
        foreach ($this->tableColumns as $column) {
            if (in_array($column, $this->booleanColumns)) {
                $html[] = $this->getFormFieldByType($column, 'boolean');
                $columnCount++;
            }
        }
        
        // Process remaining fields
        foreach ($this->tableColumns as $column) {
            if (!in_array($column, $priorityFields) && !in_array($column, $this->booleanColumns) && $columnCount < 8) {
                $type = $this->tableColumnTypes[$column] ?? '';
                $html[] = $this->getFormFieldByType($column, $type);
                $columnCount++;
            }
        }
        
        return implode("\n    ", $html);
    }

    protected function getFormFieldByType($column, $type = '')
    {
        if (in_array($column, $this->booleanColumns) || $type === 'boolean') {
            return <<<HTML
<div class="col-md-4 mt-6">
        <x-forms.input-switch name="{$column}" label="{$column}" :value="old('{$column}', \$model->{$column} ?? 1)" />
    </div>
HTML;
        } else if (strpos($type, 'text') !== false) {
            return <<<HTML
<div class="mb-3 col-md-12">
        <x-forms.textarea name="{$column}" :label="__('field.{$column}')" :value="old('{$column}', \$model->{$column} ?? '')" />
    </div>
HTML;
        } else if (strpos($type, 'date') !== false) {
            return <<<HTML
<div class="mb-3 col-md-4">
        <x-forms.date-picker name="{$column}" :label="__('field.{$column}')" :value="old('{$column}', \$model->{$column} ?? '')" />
    </div>
HTML;
        } else {
            return <<<HTML
<div class="mb-3 col-md-4">
        <x-forms.input name="{$column}" :label="__('field.{$column}')" :value="old('{$column}', \$model->{$column} ?? '')" />
    </div>
HTML;
        }
    }

    protected function generateDetailItems()
    {
        if (empty($this->tableColumns)) {
            return '<x-resource.detail-item label="{{ __(\'field.name\') }}" :value="$resource->name" />';
        }
        
        $items = [];
        
        // Add the first 8 columns to the detail view
        $count = 0;
        foreach ($this->tableColumns as $column) {
            if ($count < 8) {
                if (in_array($column, $this->booleanColumns)) {
                    $items[] = '<x-resource.detail-item label="{{ __(\'field.' . $column . '\') }}" :value="$resource->' . $column . ' ? \'Yes\' : \'No\'" />';
                } else {
                    $items[] = '<x-resource.detail-item label="{{ __(\'field.' . $column . '\') }}" :value="$resource->' . $column . '" />';
                }
                $count++;
            }
        }
        
        return implode("\n    ", $items);
    }

    protected function createDirectory($path)
    {
        if (!File::isDirectory($path)) {
            if (!File::makeDirectory($path, 0755, true, true)) {
                $this->error("Failed to create directory: {$path}");
                return false;
            }
            $this->info("Directory created: {$path}");
        }
        return true;
    }

    protected function formatArrayForCode($array)
    {
        return "[\n        '" . implode("',\n        '", $array) . "'\n    ]";
    }

    protected function getControllerStub()
    {
        return <<<'STUB'
<?php

namespace {{namespace}};

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use {{modelNamespace}}{{name}};
use {{dataTableNamespace}}\{{name}}DataTable;
use {{requestNamespace}}\Store{{name}}Request;
use {{requestNamespace}}\Update{{name}}Request;
use App\Http\Controllers\BaseAdminController;

class {{name}}Controller extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;
    
    protected $model = {{name}}::class;
    protected string $resourcePermission = '{{nameLower}}s';
    protected string $resourceName = '{{nameLower}}s';
    protected string $formView = '{{moduleLower}}::pages.{{nameLower}}s.{{nameLower}}Form';
    
    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }
    
    public function index({{name}}DataTable $dataTable)
    {
        return $this->renderDataTable($dataTable);
    }
    
    public function create(Request $request)
    {
        return $this->renderModalForm($this->formView);
    }
    
    public function store(Store{{name}}Request $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            {{name}}::create($request->validated());
            if ($request->has('save_and_add_more')) {
                return redirect()->route('admin.{{nameLower}}s.create')
                ->with('success', 'Group Member created successfully. Add another one.');
            }
        }, 'admin.{{nameLower}}s.index', '{{name}} created successfully.', 'Failed to create the {{name}}.');
    }
    
    public function show({{name}} ${{nameLower}})
    {
        return view('{{moduleLower}}::pages.{{nameLower}}s.show', ['resource' => ${{nameLower}}]);
    }
    
    public function edit({{name}} ${{nameLower}})
    {
        return $this->renderModalForm($this->formView, ${{nameLower}});
    }
    
    public function update(Update{{name}}Request $request, {{name}} ${{nameLower}})
    {
        return $this->handleRequest($request, function () use ($request, ${{nameLower}}) {
            ${{nameLower}}->update($request->validated());
        }, 'admin.{{nameLower}}s.index', '{{name}} updated successfully.', 'Failed to update the {{name}}.');
    }
    
    public function destroy(Request $request, {{name}} ${{nameLower}})
    {
        return $this->handleRequest($request, function () use (${{nameLower}}) {
            ${{nameLower}}->delete();
        }, 'admin.{{nameLower}}s.index', '{{name}} deleted successfully.', 'Failed to delete the {{name}}.');
    }
}
STUB;
    }

    protected function getModelStub()
    {
        return <<<'STUB'
<?php

namespace {{namespace}};

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class {{name}} extends Model
{
    use HasFactory;
    
    protected $table = '{{table}}';
    
    protected $fillable = {{fillable}};
    
    protected $casts = {{casts}};
}
STUB;
    }

    protected function getStoreRequestStub()
    {
        return <<<'STUB'
<?php

namespace {{namespace}};

use Illuminate\Foundation\Http\FormRequest;

class Store{{name}}Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return {{validationRules}};
    }
}
STUB;
    }

    protected function getUpdateRequestStub()
    {
        return <<<'STUB'
<?php

namespace {{namespace}};

use Illuminate\Foundation\Http\FormRequest;

class Update{{name}}Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return {{validationRules}};
    }
}
STUB;
    }

    protected function getDataTableStub()
    {
        return <<<'STUB'
<?php

namespace {{namespace}};

use {{modelNamespace}};
use App\Traits\CommonDataTableFunctions;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class {{name}}DataTable extends DataTable
{
    use CommonDataTableFunctions;
    
    protected array $searchableColumns = {{searchableColumns}};
    
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('checkbox', fn ($row) => $this->renderCheckbox('{{nameLower}}s[]', $row->id))
            ->addColumn('status', fn ($row) => $this->getStatusBadge($row->status))
            ->addColumn('action', $this->addActionColumn(
                'modal',
                $this->getRoutes(),
                $this->getPermissions('{{nameLower}}s')
            ))
            ->rawColumns(['checkbox', 'action','status']);
    }
    
    public function query({{name}} $model): QueryBuilder
    {
        $query = $model->newQuery();
        
        if (request()->has('search') && request('search')['value']) {
            $search = request('search')['value'];
            $query->where(function ($q) use ($search) {
                foreach ($this->searchableColumns as $column) {
                    $q->orWhere($column, 'like', "%{$search}%");
                }
            });
        }
        
        if (request()->has('columns')) {
            foreach (request('columns') as $i => $column) {
                if (isset($column['search']['value']) && $column['search']['value'] !== '') {
                    $value = $column['search']['value'];
                    if (in_array($column['data'], $this->searchableColumns)) {
                        $query->where($column['data'], 'like', "%{$value}%");
                    }
                }
            }
        }
        
        return $query;
    }
    
    public function html(): HtmlBuilder
    {
        $routeName = route("admin.{{nameLower}}s.import");
        return $this->builder()
            ->setTableId('{{nameLower}}s-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom($this->getCommonDom())
            ->orderBy(0)
            ->buttons(
                array_merge(
                    $this->dtActionModalButtons('{{nameLower}}s', '{{name}}'),
                    $this->importButton($routeName)
                )
            )
            ->parameters([
                'initComplete' => 'function() {
                    ' . $this->initBulkDeleteScript('{{nameLower}}s[]') . '
                    ' . $this->initDeleteScript() . '
                    ' . $this->initColumnSearch() . '
                    ' . $this->initStickyColumnsStyles() . ' 
                }',
            ]);
    }
    
    public function getColumns(): array
    {
        return [
            {{datatableColumns}}
        ];
    }
    
    protected function filename(): string
    {
        return '{{name}}_' . date('YmdHis');
    }
    
    protected function getRoutes(): array
    {
        return [
            'create' => 'admin.{{nameLower}}s.create',
            'view' => 'admin.{{nameLower}}s.show',
            'edit' => 'admin.{{nameLower}}s.edit',
            'delete' => 'admin.{{nameLower}}s.destroy',
        ];
    }
}
STUB;
    }

    protected function getFormViewStub()
    {
        return <<<'STUB'
@extends('pages.modal.modalForm')
@section('form-fields')
    {{formFields}}
@endsection
STUB;
    }

    protected function getShowViewStub()
    {
        return <<<'STUB'
<x-resource.detail-page :resource="$resource">
    {{detailItems}}
</x-resource.detail-page>
STUB;
    }
}