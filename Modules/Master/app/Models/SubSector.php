<?php

namespace Modules\Master\Models;

use App\Traits\HasStatusScope;
use Modules\Master\Models\Sector;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Master\Database\Factories\SubSectorFactory;

class SubSector extends Model
{
    use HasFactory, HasStatusScope;
    protected $table='sub_sectors';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'code',
        'name',
        'name_np',
        'sector_id',
        'status',
    ];

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

}
