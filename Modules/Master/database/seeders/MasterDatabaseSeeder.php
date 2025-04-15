<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Master\Database\Seeders\CropSeeder;
use Modules\Master\Database\Seeders\CasteSeeder;
use Modules\Master\Database\Seeders\GenderSeeder;
use Modules\Master\Database\Seeders\SectorSeeder;
use Modules\Master\Database\Seeders\StatusSeeder;
use Modules\Master\Database\Seeders\DistrictSeeder;
use Modules\Master\Database\Seeders\ProvinceSeeder;
use Modules\Master\Database\Seeders\ComponentSeeder;
use Modules\Master\Database\Seeders\LiveStockSeeder;
use Modules\Master\Database\Seeders\SubSectorSeeder;
use Modules\Master\Database\Seeders\FiscalYearSeeder;
use Modules\Master\Database\Seeders\LengthUnitSeeder;
use Modules\Master\Database\Seeders\LocalLevelSeeder;
use Modules\Master\Database\Seeders\AssetsTableSeeder;
use Modules\Master\Database\Seeders\CropVarietySeeder;
use Modules\Master\Database\Seeders\SectionTableSeeder;
use Modules\Master\Database\Seeders\SubComponentSeeder;
use Modules\Master\Database\Seeders\ClustersTableSeeder;
use Modules\Master\Database\Seeders\LiveStockBreedSeeder;
use Modules\Master\Database\Seeders\StarterCategorySeeder;
use Modules\Master\Database\Seeders\ClusterTypeTableSeeder;
use Modules\Master\Database\Seeders\DesignationsTableSeeder;
use Modules\Master\Database\Seeders\OrganizationsTableSeeder;
use Modules\Master\Database\Seeders\InfrastructureTableSeeder;
use Modules\Master\Database\Seeders\ExpenditureCategoryTableSeeder;

class MasterDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            GenderSeeder::class,
            ProvinceSeeder::class,
            DistrictSeeder::class,
            LocalLevelSeeder::class,
            FiscalYearSeeder::class,
            LengthUnitSeeder::class,
            //
            ComponentSeeder::class,
            SubComponentSeeder::class,
            CropSeeder::class,
            CropVarietySeeder::class,
//
            DesignationsTableSeeder::class,
            ClusterTypeTableSeeder::class,
            ExpenditureCategoryTableSeeder::class,
            ClustersTableSeeder::class,

            SectorSeeder::class,
            SubSectorSeeder::class,
            CasteSeeder::class,
            StarterCategorySeeder::class,
            LiveStockSeeder::class,
            LiveStockBreedSeeder::class,
            SectionTableSeeder::class,
            StatusSeeder::class,
            AssetsTableSeeder::class,
            InfrastructureTableSeeder::class,
            OrganizationsTableSeeder::class,
            //
        ]);
    }
}
