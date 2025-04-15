<?php

namespace App\Models;

use App\Models\MenuItem;
use App\Models\MenuSection;
use App\Models\MenuItemSlug;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_section_id',
        'parent_id',
        'name',
        'url',
        'icon',
        'permission',
        'order',
        'is_active',
    ];

    /**
     * Get the section that owns the menu item.
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(MenuSection::class, 'menu_section_id');
    }

    /**
     * Get the parent menu item.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    /**
     * Get the submenu items for the menu item.
     */
    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }

    /**
     * Get the slugs for the menu item.
     */
    public function slugs(): HasMany
    {
        return $this->hasMany(MenuItemSlug::class);
    }

    /**
     * Get the roles that have access to this menu item.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'menu_item_role');
    }
}
