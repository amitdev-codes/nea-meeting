<?php

namespace Modules\Master\Models;

use App\Traits\HasStatusScope;
use Illuminate\Database\Eloquent\Model;
use Modules\Master\Models\SubComponent;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Component extends Model
{
    use HasFactory,HasStatusScope;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table='components';
    protected $fillable = [
       'name',
       'name_np',
       'description',
       'status',
    ];
    
    public function subComponent(): HasMany
    {
        return $this->hasMany(SubComponent::class);
    }
}