<?php

namespace Modules\Master\Models;

use App\Traits\HasStatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Master\Database\Factories\StarterCategoryFactory;

class StarterCategory extends Model
{
    use HasFactory, HasStatusScope;

    protected $table='starter_categories';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'code',
        'name',
        'name_np',
       'status',
    ];

    // protected static function newFactory(): StarterCategoryFactory
    // {
    //     // return StarterCategoryFactory::new();
    // }
}
