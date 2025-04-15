<?php

namespace App\Models\Scopes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class StatusScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $user = Auth::user();
        if ($user && !$this->isAdminOrSuperAdmin($user)) {
            // dd($model->getTable() . '.status');
            $builder->where($model->getTable() . '.status', true);
        }
    }
    protected function isAdminOrSuperAdmin($user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('superadmin');
    }
}
