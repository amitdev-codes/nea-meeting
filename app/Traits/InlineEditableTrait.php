<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait InlineEditableTrait
{
    /**
     * Handle inline editing of model fields
     * 
     * @param Request $request
     * @param mixed $model The model instance to update
     * @param array $allowedFields List of fields that can be edited inline
     * @param array $validationRules Validation rules keyed by field name
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleInlineEdit(Request $request, $model, array $allowedFields, array $validationRules)
    {
        $field = $request->input('field');
        $value = $request->input('value');
        
        // Authorize the action (if needed)
        // if (method_exists($this, 'authorizeResource')) {
        //     $this->authorizeResource('edit');
        // }
        
        // Validate the field is allowed for inline editing
        if (!in_array($field, $allowedFields)) {
            return response()->json(['message' => 'Field cannot be edited'], 422);
        }
        
        // Validate the input
        $validator = validator([$field => $value], [$field => $validationRules[$field]]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }
        
        // Update the model
        $model->$field = $value;
        $model->save();
        
        $modelName = class_basename($model);
        // return redirect()->back()->with('success', "$modelName updated successfully");
        
        // Return success response with a flag to reload the DataTable
        return response()->json([
            'message' => "$modelName updated successfully",
        ]);
    }
}