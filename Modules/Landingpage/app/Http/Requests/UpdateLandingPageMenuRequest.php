<?php

namespace Modules\Landingpage\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLandingPageMenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'parent_id' => 'nullable|integer',
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'order_id' => 'nullable|integer'
        ];
    }
}