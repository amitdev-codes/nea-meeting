<?php

namespace Modules\Master\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDistrictRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'code' => 'required|unique:mst_districts,code,'.$this->district->id,
            'name' => 'required|string|max:255',
            'name_np' => 'required|string|max:255',
            'province_code' => 'nullable|string|exists:mst_provinces,code',
            'status' => 'nullable|boolean',
        ];
    }



    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated();
        $validated['status'] = $this->has('status') ? 1 : 0;

        return $key ? data_get($validated, $key, $default) : $validated;
    }
}
