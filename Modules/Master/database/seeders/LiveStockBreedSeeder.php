<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LiveStockBreedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('livestock_breeds')->insert([
            [
                'livestock_id' => 1,
                'name' => 'Cattle',
                'name_np' => 'गाउँ',
                'description' => 'Includes breeds of cows and bulls used for dairy and meat production.',
                'status' => true,
            ],
            [
                'livestock_id' => 1,
                'name' => 'Buffalo',
                'name_np' => 'बुफालो',
                'description' => 'Includes buffalo breeds, mainly raised for milk production.',
                'status' => true,
            ],
            [
                'livestock_id' => 2,
                'name' => 'Goats',
                'name_np' => 'गोटो',
                'description' => 'Includes various breeds of goats raised for milk and meat production.',
                'status' => true,
            ],
            [
                'livestock_id' => 2,
                'name' => 'Sheep',
                'name_np' => 'शेप',
                'description' => 'Includes breeds of sheep used for wool and meat production.',
                'status' => true,
            ],
            // Add more categories as needed
        ]);
    }
}
