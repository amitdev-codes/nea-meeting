<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Master\Models\SubSector;

class SubSectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subSectors = [
            ['code' => 'SUB001', 'sector_id' => 2, 'name' => 'dairy', 'name_np' => 'दुग्ध उत्पादन', 'status' => true], // Dairy = दुग्ध उत्पादन
            ['code' => 'SUB002', 'sector_id' => 2, 'name' => 'poultry', 'name_np' => 'कुखुरा पालन', 'status' => true], // Poultry = कुखुरा पालन
            ['code' => 'SUB003', 'sector_id' => 2, 'name' => 'goat', 'name_np' => 'बाख्रा पालन', 'status' => true], // Goat = बाख्रा पालन
            ['code' => 'SUB004', 'sector_id' => 2, 'name' => 'not specified', 'name_np' => 'निर्दिष्ट छैन', 'status' => true], // Not specified = निर्दिष्ट छैन
        ];
        foreach ($subSectors as $subSector) {
            SubSector::create($subSector);
        }
    }
}
