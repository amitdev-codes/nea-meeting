<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MenuHelper
{
    /**
     * Determine if the user can access the menu item.
     */
    public static function canAccessMenu($menu, array $userPermissions = []): bool
    {
        $user = Auth::user();
        if (! $user) {
            return false;
        }

        $url = $menu->url ?? null;

        // Always allow dashboard
        if ($url === 'dashboard') {
            return true;
        }

        // If menu has submenus, allow if any submenu is accessible
        if (isset($menu->submenu) && is_iterable($menu->submenu)) {
            foreach ($menu->submenu as $submenu) {
                if (static::hasPermissionForMenuItem($submenu, $userPermissions)) {
                    return true;
                }
            }

            return false;
        }

        return static::hasPermissionForMenuItem($menu, $userPermissions);
    }

    /**
     * Check if the menu item has permission or slug access.
     */
    public static function hasPermissionForMenuItem($menu, array $userPermissions): bool
    {
        // 1️⃣ Direct permission
        $permissionName = $menu->permission ?? null;
        if ($permissionName) {
            return in_array($permissionName, $userPermissions);
        }

        // 2️⃣ Check slug if permission not defined
        $slugs = $menu->slug ?? $menu->url ?? '';
        if (! is_array($slugs)) {
            $slugs = [$slugs];
        }

        foreach ($slugs as $slug) {
            if (static::hasPermissionForSlug($slug, $userPermissions)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check slug against preloaded permissions.
     */
    private static function hasPermissionForSlug(string $slug, array $userPermissions): bool
    {
        if ($slug === '') {
            return false;
        }

        // Wildcard slug: admin.form-entries.* -> view-form-entries
        if (Str::endsWith($slug, '.*')) {
            $resource = Str::beforeLast($slug, '.*'); // admin.form-entries
            $segments = explode('.', $resource);
            $permission = "view-{$segments[1]}"; // take second segment as resource

            return in_array($permission, $userPermissions);
        }

        // Standard slug: admin.users.index -> view-users
        $segments = explode('.', $slug);
        if (count($segments) >= 2) {
            $permission = "view-{$segments[1]}";

            return in_array($permission, $userPermissions);
        }

        // Fallback: slug as permission
        return in_array($slug, $userPermissions);
    }

    /**
     * Resolve dynamic menu labels (for roles like cluster data entry forms)
     */
    public static function resolveLabel($nameKey): ?string
    {
        $user = Auth::user();
        return __($nameKey);
    }
}
