<?php

namespace Modules\Master\Models;

use App\Traits\HasStatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Master\Database\Factories\DesignationFactory;

class Designation extends Model
{
    use HasFactory,HasStatusScope;

    protected $table='mst_designations';

    protected $fillable = [
        'code',
        'name',
        'name_np',
        'description',
        'status',
    ];

}
