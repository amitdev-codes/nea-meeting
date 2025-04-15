<?php

namespace Modules\Master\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;
    
    protected $table = 'assets';
    
    protected $fillable = [
        'name',
        'name_np',
        'code',
        'status'
    ];
    
    protected $casts = [
        'status' => 'boolean',
        'status' => 'integer'
    ];
}