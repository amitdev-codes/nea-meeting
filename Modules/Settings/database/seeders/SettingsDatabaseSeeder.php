<?php

namespace Modules\Settings\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Settings\Database\Seeders\SmsConfigurationTableSeeder;
use Modules\Settings\Database\Seeders\EmailConfigurationTableSeeder;

class SettingsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            SmsConfigurationTableSeeder::class,
            EmailConfigurationTableSeeder::class

        ]);
    }
}
