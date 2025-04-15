<?php

namespace Modules\NeaMeeting\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    
    protected $table = 'notifications';
    
    protected $fillable = [
        'user_id',
        'meeting_id',
        'notification_type',
        'message',
        'is_read'
    ];
    
    protected $casts = [
        'user_id' => 'integer',
        'meeting_id' => 'integer',
        'is_read' => 'integer'
    ];
}