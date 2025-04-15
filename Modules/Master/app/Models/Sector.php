<?php

namespace Modules\Master\Models;

use App\Traits\HasStatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Master\Database\Factories\SectorFactory;

class Sector extends Model
{
    use HasFactory, HasStatusScope;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'code',
        'name',
        'name_np',
        'status',
    ];

    // protected static function newFactory(): SectorFactory
    // {
    //     // return SectorFactory::new();
    // }
}
