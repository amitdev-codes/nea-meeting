<?php

namespace App\Models;

use Modules\Master\Models\District;
use Modules\Master\Models\Province;
use Modules\Master\Models\LocalLevel;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'addressable_id',
        'addressable_type',
        'province_id',
        'district_id',
        'localLevel_id',
        'ward_no',
        'street_name',
        'remarks',
    ];

    public function addressable()
    {
        return $this->morphTo();
    }

    public function province()
    {
        return $this->belongsTo(Province::class,'province_id','id');
    }
    public function district()
    {
        return $this->belongsTo(District::class,'district_id','id');
    }
    public function localLevel()
    {
        return $this->belongsTo(LocalLevel::class,'localLevel_id','id');
    }
}
