<?php

namespace Modules\NeaMeeting\Models;

use App\Models\User;
use Modules\NeaMeeting\Models\Meeting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MeetingAttendee extends Model
{
    use HasFactory;
    
    protected $table = 'meeting_attendees';
    
    protected $fillable = [
        'meeting_id',
        'user_id',
        'is_required',
        'attendance_status',
        'invitation_sent_at',
        'response_at',
        'notes'
    ];
    
    protected $casts = [
        'meeting_id' => 'integer',
        'user_id' => 'integer',
        'is_required' => 'integer',
        'invitation_sent_at' => 'datetime',
        'response_at' => 'datetime'
    ];
    public function meeting():BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}