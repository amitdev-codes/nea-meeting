<?php

namespace Modules\NeaMeeting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMeetingMinuteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'meeting_id' => 'nullable|integer',
            'content' => 'nullable|string',
            'recorded_by' => 'nullable|integer',
            'approved' => 'nullable|integer',
            'approved_by' => 'nullable|integer',
            'approved_at' => 'nullable'
        ];
    }
}