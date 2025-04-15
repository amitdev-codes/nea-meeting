<?php

namespace Modules\Master\Models;

use App\Traits\HasStatusScope;
use Modules\Master\Models\Component;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubComponent extends Model
{
    use HasFactory, HasStatusScope;
    
    protected $table='sub_components';

    protected $fillable = [
        'component_id',
        'name',
        'name_np',
        'description',
        'status',
    ];

    /**
     * Get the crop that this variety belongs to.
     */
    public function component(): BelongsTo
    {
        return $this->belongsTo(Component::class);
    }  
}