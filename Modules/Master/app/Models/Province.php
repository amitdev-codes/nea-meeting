<?php

namespace Modules\Master\Models;

use App\Traits\HasStatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// use Modules\Master\Database\Factories\ProvinceFactory;

class Province extends Model
{
    use HasFactory, HasStatusScope;

    protected $table = 'mst_provinces';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'code',
        'name',
        'name_np',
        'status'
    ];

    public function districts()
    {
        return $this->hasMany(District::class, 'province_code', 'code');
    }
}
