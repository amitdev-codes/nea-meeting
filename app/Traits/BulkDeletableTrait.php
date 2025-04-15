<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait BulkDeletableTrait
{
    public function bulkDestroy(Request $request, $model): JsonResponse
    {
        try {
            $ids = $request->input('ids');
            $modelClass = $this->getModelClass($model);
            // dd($modelClass, $ids);

            if (! class_exists($modelClass)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Model not found.',
                ], 404);
            }
            // Start a database transaction
            DB::beginTransaction();
            // Check if he model has associated models to delete
            if (method_exists($modelClass, 'getAssociatedModels')) {
                $associations = $modelClass::getAssociatedModels();

                foreach ($associations as $associatedModel => $foreignKey) {
                    $associatedModel::whereIn($foreignKey, $ids)->delete();
                }
            }

            $deleted = $modelClass::whereIn('id', $ids)->delete();
            // Commit the transaction
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $deleted.' item(s) have been deleted successfully.',
            ]);
        } catch (\Exception $e) {
            // Rollback transaction in case of error
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error deleting items: '.$e->getMessage(),
            ], 500);
        }
    }

    protected function getModelClass($model)
    {
        if (is_object($model)) {
            return get_class($model);
        }

        // Convert the model name to the fully qualified class name
        $model = ucfirst($model);
        $moduleModelClass = $this->getModuleModelClass($model);
        if (class_exists($moduleModelClass)) {
            return $moduleModelClass;
        }

        // Fallback to the App\Models directory
        $appModelClass = "App\\Models\\$model";
        if (class_exists($appModelClass)) {
            return $appModelClass;
        }

        // If the model is not found in either location, return the default App\Models path
        return $appModelClass;
    }

    protected function getModuleModelClass($model)
    {
        // Get all modules
        $modules = glob(base_path('Modules/*'), GLOB_ONLYDIR);

        foreach ($modules as $module) {
            $moduleName = basename($module);
            $moduleModelClass = "Modules\\$moduleName\\Models\\$model";

            if (class_exists($moduleModelClass)) {
                return $moduleModelClass;
            }
        }

        return null;
    }
}
