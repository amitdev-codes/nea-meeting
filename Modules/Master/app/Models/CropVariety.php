<?php

namespace Modules\Master\Models;

use App\Traits\HasStatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CropVariety extends Model
{
    use HasFactory,HasStatusScope;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'crop_id',
        'name',
        'description',
        'maturity_days',
        'yield_potential',
        'status',
    ];

    /**
     * Get the crop that this variety belongs to.
     */
    public function crop(): BelongsTo
    {
        return $this->belongsTo(Crop::class);
    }  // Add your attribute casts here

}