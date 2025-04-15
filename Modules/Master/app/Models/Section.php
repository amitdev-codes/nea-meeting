<?php

namespace Modules\Master\Models;

use Modules\Master\Models\Sector;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Master\Database\Factories\CategoryFactory;

class Section extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'code',
        'name',
        'name_np',
        'sector_id'
    ];
    public function Sector():BelongsTo
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }


}
