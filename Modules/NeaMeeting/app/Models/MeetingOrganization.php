<?php

namespace Modules\NeaMeeting\Models;

use Modules\NeaMeeting\Models\Meeting;
use Illuminate\Database\Eloquent\Model;
use Modules\Master\Models\Organization;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MeetingOrganization extends Model
{
    use HasFactory;
    
    protected $table = 'meeting_organizations';
    
    protected $fillable = [
        'meeting_id',
        'organization_id',
        'status'
    ];
    
    protected $casts = [
        'status' => 'boolean',
        'meeting_id' => 'integer',
        'organization_id' => 'integer',
        'status' => 'integer'
    ];
    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}