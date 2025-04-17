<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\Resource;
use App\Models\SiteSetting;
use App\Traits\HasStatusScope;
use Modules\Forms\Models\Form;
use Modules\Master\Models\Crop;
use Modules\Groups\Models\Group;
use Modules\Master\Models\Asset;
use Modules\Master\Models\Caste;
use Modules\Master\Models\Gender;
use Modules\Master\Models\Sector;
use Modules\Master\Models\Status;
use Illuminate\Support\Facades\DB;
use Modules\Master\Models\Cluster;
use Modules\Master\Models\Section;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\URL;
use Modules\Landingpage\Models\Faq;
use Modules\Master\Models\District;
use Modules\Master\Models\Province;
use App\Helpers\NepaliDateConverter;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Modules\Master\Models\Component;
use Modules\Master\Models\LiveStock;
use Modules\Master\Models\SubSector;
use Illuminate\Support\Facades\Cache;
use Modules\Master\Models\FiscalYear;
use Modules\Master\Models\LengthUnit;
use Modules\Master\Models\LocalLevel;
use Modules\Master\Models\CropVariety;
use Modules\Master\Models\Designation;
use Illuminate\Support\ServiceProvider;
use Modules\Lmbis\Models\LmbisActivity;
use Modules\Master\Models\Organization;
use Modules\Master\Models\SubComponent;
use Modules\Indicators\Models\Indicator;
use Modules\Master\Models\Infrastructure;
use Modules\Master\Models\LiveStockBreed;
use Modules\Master\Models\StarterCategory;
use Modules\NeaMeeting\Models\MeetingRoom;
use Modules\Grievances\Models\GrievanceNature;
use Modules\Master\Models\ExpenditureCategory;
use Modules\Landingpage\Models\LandingPageMenu;
use Modules\Grievances\Models\CommunicationMedium;

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

        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
        $this->cacheMasterData();
        $this->shareMasterData();
        Paginator::defaultView('components.pagination');
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


        $siteSettings=Cache::remember('site_settings', 3600, function () {
            $settings = SiteSetting::first();
            return $settings ? $settings->settings : [];
        });

        Cache::remember('landingPageMenus', 3600, function () {
            return LandingPageMenu::where('is_active', true)->orderBy('order_id', 'asc')->get();
        });

        $today = Carbon::now();
        $nepaliDate = NepaliDateConverter::toNepaliDate($today);

        Cache::remember('currentNepaliYear', 3600, function () use($nepaliDate) {
            return $nepaliDate['year'];
        });
        Cache::remember('currentNepaliMonth', 3600, function ()use($nepaliDate) {
            return $nepaliDate['month'];
        });

        $sessionIdleTimeout=$siteSettings['session_lifetime']??config('session.lifetime');
        config(['session.lifetime' => $sessionIdleTimeout]);
        // organizations
        Cache::remember('organizations', 3600, function () {
            return Organization::get(['id', 'code','name','name_np']);
        });
        Cache::remember('designations', 3600, function () {
            return Designation::get(['id', 'code','name','name_np']);
        });



        //roles
        Cache::remember('roles', 3600, function () {
            return Role::get(['id', 'code','name','name_np']);
        });


        //
        Cache::remember('meeting_rooms', 3600, function () {
            return MeetingRoom::get(['id', 'name']);
        });



    }


    private function shareMasterData(): void
    {
        View::share([
            'menuData'=> Cache::get('verticalMenuData'),
            'starterCategories' => Cache::get('starterCategories'),
            'siteSettings' => Cache::get('site_settings'),
            'landingPageMenus' => Cache::get('landingPageMenus'),
            'languages' => config('app.available_locales'),
            'resources' => Cache::get('resources'),
            'nepaliDateTime'=>NepaliDateConverter::getTodayNepaliDateTime(),
            'currentFiscalYear'=>Cache::get('current_fiscal_year'),
            'currentNepaliYear'=>Cache::get('currentNepaliYear'),
            'currentNepaliMonth'=>Cache::get('currentNepaliMonth'),
            'roles' => Cache::get('roles'),
            'meeting_rooms' => Cache::get('meeting_rooms'),
            'organizations' => Cache::get('organizations'),
            'designations' => Cache::get('designations'),
        ]);
    }
}
