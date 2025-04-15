<?php

namespace Modules\Master\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Master\Database\Factories\StatusFactory;

class Status extends Model
{
    use HasFactory;
    protected $table='mst_statuses';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'name_np',
        'code',
       'status',
    ];
}
