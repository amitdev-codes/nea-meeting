<?php
namespace App\Helpers;

use Illuminate\Support\Str;

class MenuHelper
{
    public static function canAccessMenu($menu): bool
    {
        if (!auth()->check()) {
            //\Log::info("No authenticated user for menu: " . ($menu->name ?? 'unknown'));
            return false;
        }

        if (isset($menu->url) && $menu->url === 'dashboard') {
            //\Log::info("Dashboard access granted");
            return true;
        }

        if (isset($menu->submenu)) {
            $accessible = false;
            foreach ($menu->submenu as $submenu) {
                if (static::hasPermissionForMenuItem($submenu)) {
                    //\Log::info("Submenu accessible: " . ($submenu->name ?? 'unknown') . " with permission: " . ($submenu->permission ?? 'none'));
                    $accessible = true;
                }
            }
            //\Log::info("Menu: " . ($menu->name ?? 'unknown') . " - Any submenu accessible: " . ($accessible ? 'true' : 'false'));
            return $accessible;
        }

        $access = static::hasPermissionForMenuItem($menu);
        //\Log::info("Single menu: " . ($menu->name ?? 'unknown') . " - Access: " . ($access ? 'true' : 'false'));
        return $access;
    }

    private static function hasPermissionForMenuItem($menu): bool
    {
        if (isset($menu->permission)) {
            $can = auth()->user()->can($menu->permission);
            //\Log::info("Checking explicit permission: {$menu->permission} - User can: " . ($can ? 'true' : 'false'));
            return $can;
        }

        $slug = $menu->slug ?? $menu->url ?? '';
        if (is_array($slug)) {
            foreach ($slug as $pattern) {
                if (static::hasPermissionForSlug($pattern)) {
                    //\Log::info("Array slug pattern accessible: {$pattern}");
                    return true;
                }
            }
            //\Log::info("No access for array slug: " . json_encode($slug));
            return false;
        }

        $access = static::hasPermissionForSlug($slug);
        //\Log::info("Slug: {$slug} - Access: " . ($access ? 'true' : 'false'));
        return $access;
    }

    private static function hasPermissionForSlug(string $slug): bool
    {
        if (empty($slug)) {
            //\Log::info("Empty slug, denying access");
            return false;
        }

        if (Str::endsWith($slug, '.*')) {
            $resource = Str::replaceLast('.*', '', $slug);
            $resource = explode('.', $resource)[1] ?? $resource;
            $permission = "view-{$resource}";
            $can = auth()->user()->can($permission);
            //\Log::info("Wildcard slug: {$slug} - Checking permission: {$permission} - User can: " . ($can ? 'true' : 'false'));
            return $can;
        }

        $segments = explode('.', $slug);
        if (count($segments) >= 2) {
            $resource = $segments[1];
            $permission = "view-{$resource}";
            $can = auth()->user()->can($permission);
            //\Log::info("Route slug: {$slug} - Checking permission: {$permission} - User can: " . ($can ? 'true' : 'false'));
            return $can;
        }

        $can = auth()->user()->can($slug);
        //\Log::info("Fallback slug: {$slug} - User can: " . ($can ? 'true' : 'false'));
        return $can;
    }
}