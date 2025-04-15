<?php

namespace Modules\NeaMeeting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMeetingAttendeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'meeting_id' => 'nullable|integer',
            'user_id' => 'nullable|integer',
            'is_required' => 'nullable|integer',
            'attendance_status' => 'nullable|string|max:20',
            'invitation_sent_at' => 'nullable',
            'response_at' => 'nullable',
            'notes' => 'nullable|string'
        ];
    }
}