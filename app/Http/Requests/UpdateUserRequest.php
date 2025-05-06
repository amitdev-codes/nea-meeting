<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('user')->id; // Get the user ID from the route

        return [
            // Personal Information
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($userId)],
            'rolename' => 'required|string|exists:roles,name',
            'password' => 'nullable|string|min:8',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'mobile_no' => ['required', 'digits:10', 'numeric', Rule::unique('users')->ignore($userId)],
            'phone' => 'nullable|numeric',
            'organization_id' => 'required|exists:organizations,id',
            'remarks' => 'nullable|string|max:1000',
        ];
    }

    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated();

        if (! $this->filled('password')) {
            unset($validated['password']);
        }

        return $validated;
    }
}
