<?php

namespace Modules\Settings\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Settings\Models\SmsProvider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Settings\Database\Factories\SmsConfigurationFactory;

class SmsConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'sms_provider_id',
        'api_token',
        'api_key',
        'api_secret',
        'sender_id',
        'base_url',
        'username',
        'password',
        'additional_params',
        'is_active',
    ];

    protected $casts = [
        'additional_params' => 'array',
        'is_active' => 'boolean',
    ];

    public function provider()
    {
        return $this->belongsTo(SmsProvider::class, 'sms_provider_id');
    }
}
