<?php

namespace Modules\Master\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClusterRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:clusters,name',
            'name_np' => 'nullable|string|max:255|unique:clusters,name_np',
            'description' => 'nullable|string',
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