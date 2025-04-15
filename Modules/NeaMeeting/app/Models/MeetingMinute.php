<?php

namespace Modules\NeaMeeting\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingMinute extends Model
{
    use HasFactory;
    
    protected $table = 'meeting_minutes';
    
    protected $fillable = [
        'meeting_id',
        'content',
        'recorded_by',
        'approved',
        'approved_by',
        'approved_at'
    ];
    
    protected $casts = [
        'meeting_id' => 'integer',
        'recorded_by' => 'integer',
        'approved' => 'integer',
        'approved_by' => 'integer',
        'approved_at' => 'datetime'
    ];
}