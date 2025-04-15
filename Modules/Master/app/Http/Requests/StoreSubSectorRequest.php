<?php

namespace Modules\Master\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubSectorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|unique:sub_sectors,code|max:50',
            'sector_id' => 'required|exists:sectors,id',
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