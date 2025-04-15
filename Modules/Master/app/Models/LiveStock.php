<?php

namespace Modules\Master\Models;

use App\Traits\HasStatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Master\Database\Factories\BreedFactory;

class LiveStock extends Model
{
    use HasFactory,HasStatusScope;
    protected $table = 'livestocks';

    protected $fillable = [
        'code',
        'name',
        'name_np',
        'status',
    ];
    // public function varieties(): HasMany
    // {
    //     return $this->hasMany(BreedVariety::class);
    // }
}
