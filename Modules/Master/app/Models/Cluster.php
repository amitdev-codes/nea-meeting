<?php

namespace Modules\Master\Models;

use App\Traits\HasStatusScope;
use Modules\Master\Models\District;
use Modules\Master\Models\Province;
use Modules\Master\Models\ClusterType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Master\Database\Factories\ClusterFactory;

class Cluster extends Model
{
    use HasFactory,HasStatusScope;

    protected $table='mst_clusters';

    protected $fillable = [
        'cluster_type_id',
        'districts',
        'code',
        'name',
        'name_np',
        'description',
        'status',
    ];
    protected $casts = [
        'provinces' => 'array', 
        'districts' => 'array', 
        'local_levels' => 'array', 
        'status' => 'boolean',
    ];

    public function clusterType()
    {
        return $this->belongsTo(ClusterType::class, 'cluster_type_id');
    }
    

    // public function province()
    // {
    //     return $this->belongsTo(Province::class, 'provinces');
    // }
    // public function district()
    // {
    //     return $this->belongsTo(District::class, 'district_id');
    // }
    // public function localLevel()
    // {
    //     return $this->belongsTo(District::class, 'district_id');
    // }

}
