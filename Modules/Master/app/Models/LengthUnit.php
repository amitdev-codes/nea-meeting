<?php

namespace Modules\Master\Models;

use App\Traits\HasStatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// use Modules\Master\Database\Factories\LengthUnitFactory;

class LengthUnit extends Model
{
    use HasFactory, HasStatusScope;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'mst_length_units';

    protected $fillable = [
        'name',
        'name_np',
        'code',
        'status'
    ];

    // protected static function newFactory(): LengthUnitFactory
    // {
    //     // return LengthUnitFactory::new();
    // }
}
