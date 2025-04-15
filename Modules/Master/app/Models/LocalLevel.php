<?php

namespace Modules\Master\Models;

use App\Traits\HasStatusScope;
use Modules\Master\Models\District;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// use Modules\Master\Database\Factories\LocalLevelFactory;

class LocalLevel extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'mst_local_levels';

    protected $fillable = [
        'name',
        'district_code',
        'code',
        'name_np',
        'status',
        'wards'

    ];

    public function district():BelongsTo
    {
        return $this->belongsTo(District::class, 'district_code', 'code');
    }
}
