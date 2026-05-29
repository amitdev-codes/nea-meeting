<?php

namespace App\Providers;

use App\Helpers\NepaliDateConverter;
use App\Models\SiteSetting;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Modules\Landingpage\Models\LandingPageMenu;
use Modules\Master\Models\Designation;
use Modules\Master\Models\Organization;
use Modules\NeaMeeting\Models\MeetingRoom;
use Modules\Settings\Models\SmsProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user) {
            return $user->hasRole('superadmin') ? true : null;
        });
        $permissions = Permission::pluck('name');
        foreach ($permissions as $permissionName) {
            Gate::define($permissionName, fn ($user) => $user->hasPermissionTo($permissionName));
        }
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
        $this->cacheMasterData();
        $this->shareMasterData();
        Paginator::defaultView('components.pagination');
        // for controller checking via codes
        $this->cacheLayoutComponents();

        // Share cached components globally
        $this->shareLayoutComponents();

        // Cache common assets
        $this->cacheCommonAssets();

        View::addNamespace('mail', resource_path('views/vendor/mail'));
    }

    /**
     * Cache all master data
     */
    private function cacheMasterData(): void
    {
        $verticalMenuJson = file_get_contents(base_path('resources/menu/verticalMenu.json'));
        $verticalMenuData = json_decode($verticalMenuJson);

        Cache::remember('verticalMenuData', 3600, function () use ($verticalMenuData) {
            return $verticalMenuData;
        });

        $siteSettings = Cache::remember('site_settings', 3600, function () {
            $settings = SiteSetting::first();

            return $settings ? $settings->settings : [];
        });

        Cache::remember('landingPageMenus', 3600, function () {
            return LandingPageMenu::where('is_active', true)->orderBy('order_id', 'asc')->get();
        });

        $today = Carbon::now();
        $nepaliDate = NepaliDateConverter::toNepaliDate($today);

        Cache::remember('currentNepaliYear', 3600, function () use ($nepaliDate) {
            return $nepaliDate['year'];
        });
        Cache::remember('currentNepaliMonth', 3600, function () use ($nepaliDate) {
            return $nepaliDate['month'];
        });

        $sessionIdleTimeout = $siteSettings['session_lifetime'] ?? config('session.lifetime');
        config(['session.lifetime' => $sessionIdleTimeout]);
        // organizations
        Cache::remember('organizations', 3600, function () {
            return Organization::get(['id', 'code', 'name', 'name_np']);
        });
        Cache::remember('designations', 3600, function () {
            return Designation::get(['id', 'code', 'name', 'name_np']);
        });

        // roles
        Cache::remember('roles', 3600, function () {
            return Role::get(['id', 'code', 'name', 'name_np']);
        });

        //
        Cache::remember('meeting_rooms', 3600, function () {
            return MeetingRoom::get(['id', 'name']);
        });

        Cache::remember('smsProviders', 3600, function () {
            return SmsProvider::get(['id', 'name']);
        });

    }
    private function cacheLayoutComponents(): void
    {
        // Cache sidebar for 2 hours
        if (Auth::check()) {
            View::share('cachedSidebar', Cache::remember('layout.sidebar', 60 * 120, function () {
                return view('components.sidebar')->render();
            }));
        }

        // Cache navbar for 2 hours
        if (Auth::check()) {
            View::share('cachedNavbar', Cache::remember('layout.navbar', 60 * 120, function () {
                return view('components.navbar')->render();
            }));
        }

        // Cache footer for 2 hours
        if (Auth::check()) {
            View::share('cachedFooter', Cache::remember('layout.footer', 60 * 120, function () {
                return view('components.footer')->render();
            }));
        }
    }
    /**
     * Share layout components globally to all views
     */
    private function shareLayoutComponents(): void
    {
        // Share common layout variables
        View::share([
            'commonVendorStyles' => $this->getCommonVendorStyles(),
            'commonVendorScripts' => $this->getCommonVendorScripts(),
            'commonPageScripts' => $this->getCommonPageScripts(),
        ]);
    }

    private function getCommonVendorStyles(): array
    {
        return [
            'resources/assets/vendor/libs/toastr/toastr.css',
            'resources/assets/vendor/libs/animate-css/animate.css',
            'resources/assets/vendor/libs/sweetalert2/sweetalert2.css',
        ];
    }

    private function getCommonVendorScripts(): array
    {
        return [
            'resources/assets/vendor/libs/toastr/toastr.js',
            'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
        ];
    }

    /**
     * Get common page scripts
     */
    private function getCommonPageScripts(): array
    {
        return [
            'resources/js/app.js',
            'resources/assets/js/forms-selects.js',
        ];
    }

    /**
     * Cache common assets that are used across pages
     */
    private function cacheCommonAssets(): void
    {
        // Cache common vendor styles
        Cache::remember('assets.vendor_styles', 60 * 240, function () {
            return $this->getCommonVendorStyles();
        });

        // Cache common vendor scripts
        Cache::remember('assets.vendor_scripts', 60 * 240, function () {
            return $this->getCommonVendorScripts();
        });
    }
    private function shareMasterData(): void
    {
        $cacheKeys = [
            'verticalMenuData',
            'starterCategories',
            'site_settings',
            'landingPageMenus',
            'resources',
            'current_fiscal_year',
            'currentNepaliYear',
            'currentNepaliMonth',
            'roles',
            'meeting_rooms',
            'organizations',
            'designations',
            'smsProviders'
        ];

        $sharedData = [];

        foreach ($cacheKeys as $key => $value) {
            if (is_int($key)) {
                // Key and variable name are the same
                $sharedData[$value] = Cache::get($value);
            } else {
                // Key is cache key, value is variable name
                $sharedData[$value] = Cache::get($key);
            }
        }
        // Add non-cache data
        $sharedData['languages'] = config('app.available_locales');
        $sharedData['nepaliDateTime'] = NepaliDateConverter::getTodayNepaliDateTime();
        View::share($sharedData);
    }
}
