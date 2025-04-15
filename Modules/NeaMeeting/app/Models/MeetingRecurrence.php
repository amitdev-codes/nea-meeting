<?php

namespace Modules\NeaMeeting\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingRecurrence extends Model
{
    use HasFactory;
    
    protected $table = 'meeting_recurrences';
    
    protected $fillable = ['name', 'code', 'description', 'status'];
    
    protected $casts = [];
}