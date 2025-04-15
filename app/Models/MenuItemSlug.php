<?php

namespace App\Models;

use App\Models\MenuItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MenuItemSlug extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_item_id',
        'slug',
    ];

    /**
     * Get the menu item that owns the slug.
     */
    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }
}