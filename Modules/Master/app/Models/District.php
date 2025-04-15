<?php

namespace Modules\Master\Models;

use App\Traits\HasStatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// use Modules\Master\Database\Factories\DistrictFactory;

class District extends Model
{
    use HasFactory;

    protected $table = 'mst_districts';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'province_code',
        'code',
        'name',
        'name_np',
    ];

    public function province(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_code', 'code');
    }

    public function localLevels()
    {
        return $this->hasMany(LocalLevel::class, 'district_code', 'code');
    }
    public function cluster()
    {
        return $this->hasOne(Cluster::class, 'districts')->whereJsonContains('districts', $this->code);
    }

}
