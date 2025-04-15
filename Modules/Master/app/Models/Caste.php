<?php

namespace Modules\Master\Models;

use App\Traits\HasStatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Master\Database\Factories\CasteFactory;

class Caste extends Model
{
    use HasFactory,HasStatusScope;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'code',
        'name',
        'name_np',
        'status',
    ];

}
