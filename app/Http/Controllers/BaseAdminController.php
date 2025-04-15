<?php

namespace App\Http\Controllers;

use Log;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Imports\DynamicImport;
use App\Services\ResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class BaseAdminController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    protected $model;

    protected string $resourcePermission = ''; // Default value
    protected string $resourceName = '';       // Default value
    protected string $routeName = '';          // Default value

    protected $responseService;

    public function __construct(ResponseService $responseService)
    {
        $this->responseService = $responseService;
        $title = __('field.list') . ' ' . __('field.' . ucfirst($this->resourceName));

        view()->share(['modelClass' => $this->model,
            'title' => $title,
            'resourcePermission' => $this->resourcePermission,
            'resourceName' => $this->resourceName]);
    }

    protected function authorizeResource(string $action): void
    {
        abort_if(
            Gate::denies("$action $this->resourcePermission"),
            403,
            'You do not have access to this page.'
        );
    }

    protected function successResponse(string $action, ?string $redirect = null): mixed
    {

        return $this->responseService->success($action, $this->resourceName, $redirect);
    }

    protected function jsonSuccessResponse(string $action): JsonResponse
    {
        return $this->responseService->jsonSuccess($action, $this->resourceName);
    }

    protected function handleRequest(Request $request, callable $callback, ?string $redirectRoute = null, ?string $successMessage = null, ?string $errorMessage = null)
    {
        try {
            DB::beginTransaction();
            $result = $callback();
            $formattedResourceName = Str::headline($this->resourceName);
            DB::commit();
            // Use the provided success message or fall back to the default message
            $successMessage = $successMessage ?? "$formattedResourceName processed successfully.";
    
            if ($request->ajax() || $request->wantsJson()) {
                return $result instanceof JsonResponse ? $result : $this->responseService->sendJsonResponse('success', $successMessage);
            } else {
                return $result ?: redirect()->route($redirectRoute ?? 'dashboard')->with('success', $successMessage);
            }
        } catch (\Throwable $e) {
            dd($e->getMessage(), $e->getcode(), $e->getFile(), $e->getLine());
            DB::rollBack();
            // Use the provided error message or fall back to the default message
            $errorMessage = $errorMessage ?? "An error occurred: {$e->getMessage()}";
    
            if ($request->ajax() || $request->wantsJson()) {
                return $this->responseService->errorGenericResponse($this->resourceName, true, $errorMessage, $e);
            }
    
            return redirect()->route($redirectRoute ?? 'dashboard')->with('error', $errorMessage);
        }
    }
    protected function renderDataTable($dataTable, $view = 'pages.modal.index')
    {
        // $this->authorizeResource('view');
        $routes = $dataTable->routes();
        return $dataTable->render($view, [
            'routes' => $routes,
        ]);
    }

    /**
     * Render a form view and return JSON response
     */
    protected function renderModalForm($view, $model = null, $additionalData = [])
    {
        $data = array_merge([
            'resourceName' => $this->resourceName,
            'model' => $model
        ], $additionalData);

        $html = view($view, $data)->render();
        return response()->json(['html' => $html]);
    }
    protected function renderForm($view, $model = null, $additionalData = [])
    {
        return view($view, array_merge([
            'resourceName' => $this->resourceName,
            'model' => $model
        ], $additionalData));
    }


    protected function handleImport(
        Request $request,
        string $uniqueKey,
        array $rules,
        array $mappingConfig = [],
        array $defaultValues = [],
        ?string $redirectRoute = null
    ) {
        return $this->handleRequest($request, function () use ($request, $uniqueKey, $rules, $mappingConfig, $defaultValues) {
            $request->validate([
                'import_file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
            ]);

            // dd($request, $uniqueKey, $rules, $mappingConfig, $defaultValues);
    
            try {
                $importInstance = new DynamicImport(
                    $this->model,
                    $uniqueKey,
                    $rules,
                    $mappingConfig,
                    $defaultValues
                );
                // dd($importInstance);
                Excel::import($importInstance, $request->file('import_file'));
            } catch (Exception $e) {
                Log::error("Error processing {$this->resourceName}: " . $e->getMessage());
                throw new Exception("Import failed: {$e->getMessage()}");
            }
        }, $redirectRoute ?? "admin.{$this->resourceName}.index",
        "Data imported successfully.",
        "Failed to import data.");
    }
    
}
