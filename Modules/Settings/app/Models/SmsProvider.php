<?php

namespace Modules\Settings\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Settings\Models\SmsConfiguration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Settings\Database\Factories\SmsProviderFactory;

class SmsProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    public function configurations()
    {
        return $this->hasMany(SmsConfiguration::class);
    }
}
