<?php

namespace Modules\Landingpage\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingPageMenu extends Model
{
    use HasFactory;
    
    protected $table = 'landing_page_menus';
    
    protected $fillable = [
        'parent_id',
        'name',
        'icon',
        'url',
        'is_active',
        'order_id'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'parent_id' => 'integer',
        'is_active' => 'integer',
        'order_id' => 'integer'
    ];
}