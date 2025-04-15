<?php

namespace Modules\Landingpage\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Landingpage\Database\Seeders\FaqSeeder;
use Modules\Landingpage\Database\Seeders\LandingPageMenuSeeder;

class LandingpageDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            LandingPageMenuSeeder::class
        ]);
    }
}
