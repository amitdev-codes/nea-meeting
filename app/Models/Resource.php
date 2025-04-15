<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = ['name', 'icon', 'type', 'route_name', 'url', 'is_menu'];
}
