<?php

namespace Modules\Master\Models;

use App\Traits\HasStatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClusterType extends Model
{
    use HasFactory,HasStatusScope;


    protected $table='mst_cluster_types';

    protected $fillable = [
        'code',
        'name',
        'name_np',
        'description',
        'status',
    ];
}