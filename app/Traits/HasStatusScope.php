<?php

namespace App\Traits;

use App\Models\Scopes\StatusScope;

trait HasStatusScope
{
    protected static function booted(): void
    {
        static::addGlobalScope(new StatusScope());
    }
}
