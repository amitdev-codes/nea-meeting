<?php

namespace Modules\Master\Models;

use App\Traits\HasStatusScope;
use Modules\Master\Models\LiveStock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Master\Database\Factories\BreedCategoryFactory;

class LiveStockBreed extends Model
{
    use HasFactory,HasStatusScope;
    protected $table = 'livestock_breeds';

    protected $fillable = [
        'livestock_id',
        'name',
        'name_np',
        'description',
        'status',
    ];

    /**
     * Get the crop that this variety belongs to.
     */
    public function breed(): BelongsTo
    {
        return $this->belongsTo(LiveStock::class,'livestock_id','id');
    }  
}
