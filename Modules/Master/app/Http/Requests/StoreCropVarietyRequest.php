<?php

namespace Modules\Master\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCropVarietyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'crop_id' => 'required|exists:crops,id',
            'name' => 'required|string|max:255|unique:crop_varieties,name,NULL,id,crop_id,' . $this->crop_id,
            'name_np' => 'nullable|string|max:255|unique:crop_varieties,name_np,NULL,id,crop_id,' . $this->crop_id,
            'description' => 'nullable|string',
            'maturity_days' => 'nullable|string|max:255',
            'yield_potential' => 'nullable|string|max:255',
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