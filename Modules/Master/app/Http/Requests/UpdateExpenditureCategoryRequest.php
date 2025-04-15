<?php

namespace Modules\Master\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExpenditureCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|unique:mst_expenditure_categories,code|max:50,'.$this->expenditureCategory->id,
            'name' => 'required|string|max:255',
            'name_np' => 'required|string|max:255',
            'status' => 'nullable|boolean',
        ];
    }
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated();
        $validated['status'] = $this->has('status') ? 1 : 0;

        return $key ? data_get($validated, $key, $default) : $validated;
    }
}