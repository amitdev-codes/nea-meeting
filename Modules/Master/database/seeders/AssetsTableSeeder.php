<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);
        $assets = [
            [
                'code' => 'A001',
                'name' => 'Tractor',
                'name_np' => 'ट्र्याक्टर',
                'status' => true,
            ],
            [
                'code' => 'A002',
                'name' => 'Power Tiller',
                'name_np' => 'पावर टिलर',
                'status' => true,
            ],
            [
                'code' => 'A003',
                'name' => 'Threshing Machine',
                'name_np' => 'धान कुट्ने मेसिन',
                'status' => true,
            ],
            [
                'code' => 'A004',
                'name' => 'Irrigation Pump',
                'name_np' => 'सिचाई पम्प',
                'status' => true,
            ],
            [
                'code' => 'A005',
                'name' => 'Seed Storage Bin',
                'name_np' => 'बीउ भण्डारण बिन',
                'status' => true,
            ],
        ];

        DB::table('assets')->insert($assets);
    }
}
