<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|unique:roles,name,' . $this->route('role')->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'array',
            'permissions.*.*' => 'boolean', // Changed from 'in:on' to 'boolean'
        ];
    }

    protected function prepareForValidation()
    {
        $permissions = $this->input('permissions', []);
        foreach ($permissions as $resource => $actions) {
            foreach ($actions as $action => $value) {
                $permissions[$resource][$action] = $value === 'on';
            }
        }
        $this->merge(['permissions' => $permissions]);
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        \Log::error('Validation failed for UpdateRoleRequest', $validator->errors()->all());
        parent::failedValidation($validator);
    }
}