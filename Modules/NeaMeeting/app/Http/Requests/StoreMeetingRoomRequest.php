<?php

namespace Modules\NeaMeeting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMeetingRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:200',
            'capacity' => 'nullable|integer',
            'has_projector' => 'nullable|integer',
            'has_video_conference' => 'nullable|integer',
            'notes' => 'nullable|string'
        ];
    }
}