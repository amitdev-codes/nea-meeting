<?php

namespace Modules\NeaMeeting\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingReminder extends Model
{
    use HasFactory;
    
    protected $table = 'meeting_reminders';
    
    protected $fillable = [
        'meeting_id',
        'user_id',
        'reminder_time',
        'sent',
        'sent_at'
    ];
    
    protected $casts = [
        'meeting_id' => 'integer',
        'user_id' => 'integer',
        'reminder_time' => 'datetime',
        'sent' => 'integer',
        'sent_at' => 'datetime'
    ];
}