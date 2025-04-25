<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\MenuSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\ResourceSeeder;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\SiteSettingSeeder;
use Database\Seeders\NepaliCalendarSeeder;
use Database\Seeders\LandingPageMenuSeeder;
use Database\Seeders\PermissionsTableSeeder;
use Database\Seeders\CumulativeProgressSeeder;
use Modules\Faqs\Database\Seeders\FaqsDatabaseSeeder;
use Modules\Forms\Database\Seeders\FormsDatabaseSeeder;
use Modules\Lmbis\Database\Seeders\LmbisDatabaseSeeder;
use Modules\Groups\Database\Seeders\GroupsDatabaseSeeder;
use Modules\Master\Database\Seeders\MasterDatabaseSeeder;
use Modules\Settings\Database\Seeders\SettingsDatabaseSeeder;
use Modules\Grievances\Database\Seeders\GrievancesDatabaseSeeder;
use Modules\Indicators\Database\Seeders\IndicatorsDatabaseSeeder;
use Modules\NeaMeeting\Database\Seeders\NeaMeetingDatabaseSeeder;
use Modules\Landingpage\Database\Seeders\LandingpageDatabaseSeeder;
use Modules\SuccessStories\Database\Seeders\SuccessStoriesDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([

            // LandingPageMenuSeeder::class,
            LandingpageDatabaseSeeder::class,
            MasterDatabaseSeeder::class,
            NepaliCalendarSeeder::class,
            PermissionsTableSeeder::class,
            // RoleSeeder::class,
            UsersTableSeeder::class,
            SiteSettingSeeder::class,
            ResourceSeeder::class,
            NeaMeetingDatabaseSeeder::class,
            SettingsDatabaseSeeder::class

        ]);

    }
}
