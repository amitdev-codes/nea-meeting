<?php

namespace Modules\Master\Models;

use App\Traits\HasStatusScope;
use Modules\Master\Models\CropVariety;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Crop extends Model
{
    use HasFactory,HasStatusScope;

    protected $fillable = [
        'code',
        'name',
        'name_np',
        'status',
    ];
    public function varieties(): HasMany
    {
        return $this->hasMany(CropVariety::class);
    }
}