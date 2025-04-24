<?php

namespace Modules\NeaMeeting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\NeaMeeting\Database\Factories\MeetingNotifiedExternalContactFactory;

class MeetingNotifiedExternalContact extends Model
{
    use HasFactory;
    
    protected $table = 'meeting_notified_external_contacts';
    
    protected $fillable = [
        'meeting_id',
        'external_contacts',
        'notified_at',
        'notification_type',
        'notification_status',
    ];
    
    protected $casts = [
        'meeting_id' => 'integer',
        'external_contacts' => 'array', // Cast JSON to array
        'notified_at' => 'datetime',
    ];
    
    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }
}
