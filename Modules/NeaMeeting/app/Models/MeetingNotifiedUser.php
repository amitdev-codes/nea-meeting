<?php

namespace Modules\NeaMeeting\Models;

use App\Models\User;
use Modules\NeaMeeting\Models\Meeting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MeetingNotifiedUser extends Model
{
    use HasFactory;
    
    protected $table = 'meeting_notified_users';
    
    protected $fillable = [
        'meeting_id',
        'user_id',
        'notified_at',
        'notification_type',
        'notification_status'
    ];
    
    protected $casts = [
        'meeting_id' => 'integer',
        'user_id' => 'integer',
        'notified_at' => 'datetime'
    ];
    public function meeting():BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}