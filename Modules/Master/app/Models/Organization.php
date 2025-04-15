<?php

namespace Modules\Master\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;
    
    protected $table = 'organizations';
    
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