<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ClusterTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.z
     */
    public function run(): void
    {
        DB::table('mst_cluster_types')->insert([
            ['name' => 'Hill', 'name_np' => 'पहाड', 'code' => 'A1', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Terai', 'name_np' => 'तराई', 'code' => 'B1', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Himalayas', 'name_np' => 'हिमाल', 'code' => 'B1', 'status' => false, 'created_at' => now(), 'updated_at' => now()],
            // ['name' => 'Type C', 'name_np' => 'प्रकार C', 'code' => 'C1', 'status' => false, 'created_at' => now(), 'updated_at' => now()],
            // ['name' => 'Type D', 'name_np' => 'प्रकार D', 'code' => 'D1', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            // ['name' => 'Type E', 'name_np' => 'प्रकार E', 'code' => 'E1', 'status' => false, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
