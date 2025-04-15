<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Master\Models\FiscalYear;

class FiscalYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fiscal_years = [
            ['code' => '2070/2071', 'date_from_bs' => '2070/04/01', 'date_to_bs' => '2071/03/32', 'date_from_ad' => '2013-07-16', 'date_to_ad' => '2014-07-16','status'=>true],
            ['code' => '2075/2076', 'date_from_bs' => '2075/04/01', 'date_to_bs' => '2076/03/31', 'date_from_ad' => '2018-07-17', 'date_to_ad' => '2019-07-16','status'=>true],
            ['code' => '2076/2077', 'date_from_bs' => '2076/04/01', 'date_to_bs' => '2077/03/31', 'date_from_ad' => '2019-07-17', 'date_to_ad' => '2020-07-15','status'=>true],
            ['code' => '2077/2078', 'date_from_bs' => '2077/04/01', 'date_to_bs' => '2078/03/31', 'date_from_ad' => '2020-07-16', 'date_to_ad' => '2021-07-15','status'=>true],
            ['code' => '2078/2079', 'date_from_bs' => '2078/04/01', 'date_to_bs' => '2079/03/32', 'date_from_ad' => '2021-07-16', 'date_to_ad' => '2022-07-16','status'=>true],
            ['code' => '2079/2080', 'date_from_bs' => '2079/04/01', 'date_to_bs' => '2081/03/31', 'date_from_ad' => '2022-07-17', 'date_to_ad' => '2022-07-16', 'is_previous' => true,'status'=>true],
            ['code' => '2080/2081', 'date_from_bs' => '2080/04/01', 'date_to_bs' => '2081/03/32', 'date_from_ad' => '2023-07-17', 'date_to_ad' => '2022-07-16', 'is_current' => true,'status'=>true],
            ['code' => '2081/2082', 'date_from_bs' => '2081/04/01', 'date_to_bs' => '2081/03/31', 'date_from_ad' => '2023-07-16', 'date_to_ad' => '2022-07-15', 'is_next' => true,'status'=>true],
            ['code' => '2082/2083', 'date_from_bs' => '2082/04/01', 'date_to_bs' => '2081/03/31', 'date_from_ad' => '2023-07-16', 'date_to_ad' => '2022-07-15','status'=>true],
        ];
        foreach ($fiscal_years as $fiscal_year) {
            FiscalYear::create($fiscal_year);
        }
    }
}
