<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Master\Models\Section;


class SectionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            ['code' => 'C001', 'name' => 'crop', 'name_np' => 'बाली','sector_id'=>1, 'status' => true],
            ['code' => 'C002', 'name' => 'livestock', 'name_np' => 'पशुपालन','sector_id'=>2, 'status' => true], // Livestock = पशुपालन
            ['code' => 'C003', 'name' => 'nutrition', 'name_np' => 'पोषण','sector_id'=>3, 'status' => true], // Nutrition = पोषण
            ['code' => 'C004', 'name' => 'agri-busines', 'name_np' => 'कृषि व्यवसाय','sector_id'=>4, 'status' => true], // Not specified = निर्दिष्ट छैन
            ['code' => 'C005', 'name' => 'monitoring & evaluation', 'name_np' => 'अनुगमन तथा मूल्याङ्कन','sector_id'=>4, 'status' => true], 
            ['code' => 'C006', 'name' => 'others', 'name_np' => 'अन्य','sector_id'=>4, 'status' => true], 
        ];
        
        foreach ($sections as $section) {
            Section::create($section);
        }
    }
}
