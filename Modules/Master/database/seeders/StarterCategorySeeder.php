<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Master\Models\StarterCategory;

class StarterCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $starterCategories = [
            ['code' => 'STC001', 'name' => 'Early', 'name_np' => 'प्रारम्भिक', 'status' => true],
            ['code' => 'STC002', 'name' => 'Late', 'name_np' => 'ढिलो', 'status' => true],
        ];

        foreach ($starterCategories as $category) {
            StarterCategory::create($category);
        }
    }
}
