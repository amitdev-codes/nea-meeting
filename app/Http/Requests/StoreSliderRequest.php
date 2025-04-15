<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreSliderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create sliders');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'title_size' => ['required', 'in:p,h1,h2,h3,h4,h5'],
            'title_case' => ['required', 'in:uppercase,capitalize,lowercase'],
            'title_color' => ['nullable', 'string', 'regex:/^#[a-fA-F0-9]{6}$/'],

            'subtitle' => ['nullable', 'string', 'max:255'],
            'subtitle_size' => ['nullable'],
            'subtitle_case' => ['nullable'],
            'subtitle_color' => ['nullable', 'string', 'regex:/^#[a-fA-F0-9]{6}$/'],

            'url_text' => ['nullable', 'string', 'max:50'],
            'url' => ['required_with:url_text', 'nullable', 'url'],
            'link_type' => ['required_with:url_text', 'nullable', 'in:Internal,External'],

            'content_alignment' => ['required', 'in:left,center,right'],
        ];
    }
}
