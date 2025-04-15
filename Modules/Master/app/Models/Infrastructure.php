<?php

namespace Modules\Master\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Infrastructure extends Model
{
    use HasFactory;
    
    protected $table = 'infrastructures';
    
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