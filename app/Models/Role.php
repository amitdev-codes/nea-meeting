<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends SpatieRole
{
    protected $fillable = ['name', 'guard_name'];

    protected $hidden = ['pivot'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($role) {
            if (empty($role->guard_name)) {
                $role->guard_name = config('auth.defaults.guard');
            }
        });
        static::addGlobalScope('hideSuperadminRole', function ($query) {
            // Check if the current user is NOT a superadmin
            if (!Auth::user() || !Auth::user()->hasRole('superadmin')) {
                $query->where('name', '!=', 'superadmin');
            }
        });
    }

    /**
     * A role belongs to some users of the model associated with its guard.
     */
    public function users(): BelongsToMany
    {
        return $this->morphedByMany(
            getModelForGuard($this->attributes['guard_name'] ?? config('auth.defaults.guard')),
            'model',
            config('permission.table_names.model_has_roles'),
            'role_id',
            config('permission.column_names.model_morph_key')
        );
    }
}
