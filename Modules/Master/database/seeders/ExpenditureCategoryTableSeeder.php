<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ExpenditureCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mst_expenditure_categories')->insert([
            ['name' => 'Current Expenditure', 'name_np' => '1 (चालु)', 'code' => 'CE', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Capital Expenditure', 'name_np' => '1 (पुँजीगत)', 'code' => 'CPE', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
