<?php

namespace Modules\Master\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLiveStockBreedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'livestock_id' => 'nullable|integer',
            'name' => 'required|string|max:255',
            'name_np' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'boolean'
        ];
    }
}