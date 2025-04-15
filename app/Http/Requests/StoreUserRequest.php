<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create users');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'username' => 'required|string|max:255|unique:users,username',
            'rolename' => 'required|string|exists:roles,name',
            'password' => 'required|string|min:8',
            'email' => 'required|email|unique:users,email|max:255',
            'mobile_no' => 'required|digits:10|numeric|unique:users,mobile_no',
            'phone' => 'nullable|digits:10|numeric',
            // Fansep Information
            'designation_id' => 'required|exists:mst_designations,id',
            'category_id' => 'required|exists:sections,id',
            'remarks' => 'nullable|string|max:1000',
            'clusters' => 'nullable|array', // Accept clusters as an array
            'clusters.*' => 'exists:mst_clusters,id',
        ];
    }
    public function messages()
    {
        return [
            'username.unique' => 'This username is already taken.',
            'email.unique' => 'This email is already registered.',
            'mobile_no.unique' => 'This mobile number is already in use.',
            'rolename.exists' => 'The selected role does not exist.',
            // 'password.confirmed' => 'The password confirmation does not match.',
        ];
    }
}
