<?php

namespace Modules\GoogleCalendar\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Master\Models\Organization;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GoogleCalendarSetting extends Model
{
    use HasFactory;
    
    protected $table = 'google_calendar_settings';
    
    protected $fillable = [
        'is_enabled',
        'google_calendar_id',
        'client_id',
        'client_secret',
        'redirect_uri',
        'service_account_json',
        'auth_method',
        'organization_id'
    ];
    
    protected $casts = [
        'is_enabled' => 'boolean',
        'organization_id' => 'integer'
    ];


    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}