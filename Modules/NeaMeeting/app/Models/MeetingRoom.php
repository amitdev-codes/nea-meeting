<?php

namespace Modules\NeaMeeting\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingRoom extends Model
{
    use HasFactory;
    
    protected $table = 'meeting_rooms';
    
    protected $fillable = [
        'name',
        'location',
        'capacity',
        'has_projector',
        'has_video_conference',
        'notes'
    ];
    
    protected $casts = [
        'capacity' => 'integer',
        'has_projector' => 'integer',
        'has_video_conference' => 'integer'
    ];
}