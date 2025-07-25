<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OAuthState extends Model
{
    protected $table='oauth_states';
     protected $fillable = ['state_key', 'user_id', 'provider', 'expires_at'];
    
    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
