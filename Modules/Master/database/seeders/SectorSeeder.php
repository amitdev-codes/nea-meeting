<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Master\Models\Sector;

class SectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sectors = [
            ['code' => 'SEC001', 'name' => 'crop', 'name_np' => 'बाली', 'status' => true],
            ['code' => 'SEC002', 'name' => 'livestock', 'name_np' => 'पशुपालन', 'status' => true], // Livestock = पशुपालन
            ['code' => 'SEC003', 'name' => 'nutrition', 'name_np' => 'पोषण', 'status' => true], // Nutrition = पोषण
            ['code' => 'SEC004', 'name' => 'not specified', 'name_np' => 'निर्दिष्ट छैन', 'status' => true], // Not specified = निर्दिष्ट छैन
        ];
        
        foreach ($sectors as $sector) {
            Sector::create($sector);
        }
        
    }
}
