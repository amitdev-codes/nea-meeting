<?php

namespace Modules\NeaMeeting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\NeaMeeting\Database\Factories\ExternalContactFactory;

class ExternalContact extends Model
{
    use HasFactory,Notifiable, SoftDeletes;

    protected $table = 'external_contacts';
    protected $fillable = [
        'contactable_id',
        'contactable_type',
        'name',
        'email',
        'mobile',
        'phone',
        'office_name',
    ];
    public function routeNotificationForMail($notification)
    {
        return $this->email;
    }
    public function contactable(): MorphTo
    {
        return $this->morphTo();
    }
}
