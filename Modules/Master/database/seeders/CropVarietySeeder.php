<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class CropVarietySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cropVarieties = [
            // Rice varieties (crop_id: 1)
            [
                'crop_id' => 1,
                'name' => 'Mansuli',
                'name_np' => 'मन्सुली',
                'description' => 'Popular medium-duration rice variety in Nepal',
                'maturity_days' => '130-135',
                'yield_potential' => '4-5 tons/ha',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'crop_id' => 1,
                'name' => 'Hardinath-1',
                'name_np' => 'हार्दिनाथ-१',
                'description' => 'Early maturing variety suitable for terai and inner terai',
                'maturity_days' => '100-105',
                'yield_potential' => '4-4.5 tons/ha',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'crop_id' => 1,
                'name' => 'Sabitri',
                'name_np' => 'सावित्री',
                'description' => 'Medium-duration, disease-resistant variety',
                'maturity_days' => '140-145',
                'yield_potential' => '4.5-5.5 tons/ha',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'crop_id' => 1,
                'name' => 'Sunaulo Sugandha',
                'name_np' => 'सुनौलो सुगन्ध',
                'description' => 'Aromatic fine rice variety with high market value',
                'maturity_days' => '135-140',
                'yield_potential' => '3.5-4 tons/ha',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // Maize varieties (crop_id: 2)
            [
                'crop_id' => 2,
                'name' => 'Rampur Composite',
                'name_np' => 'रामपुर कम्पोजिट',
                'description' => 'Open-pollinated variety suitable for terai and mid-hills',
                'maturity_days' => '110-120',
                'yield_potential' => '4-5 tons/ha',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'crop_id' => 2,
                'name' => 'Arun-2',
                'name_np' => 'अरुण-२',
                'description' => 'Early maturing variety for hills and terai',
                'maturity_days' => '85-90',
                'yield_potential' => '3-4 tons/ha',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'crop_id' => 2,
                'name' => 'Manakamana-3',
                'name_np' => 'मनकामना-३',
                'description' => 'Improved open-pollinated variety for mid-hills',
                'maturity_days' => '130-140',
                'yield_potential' => '4.5-5.5 tons/ha',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // Wheat varieties (crop_id: 3)
            [
                'crop_id' => 3,
                'name' => 'Bhrikuti',
                'name_np' => 'भृकुटी',
                'description' => 'Popular variety resistant to leaf and yellow rust',
                'maturity_days' => '110-120',
                'yield_potential' => '4-5 tons/ha',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'crop_id' => 3,
                'name' => 'Gautam',
                'name_np' => 'गौतम',
                'description' => 'High-yielding variety suitable for timely sown conditions',
                'maturity_days' => '110-115',
                'yield_potential' => '4.5-5.5 tons/ha',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'crop_id' => 3,
                'name' => 'WK-1204',
                'name_np' => 'WK-१२४',
                'description' => 'Suitable for late sown conditions',
                'maturity_days' => '105-115',
                'yield_potential' => '3.5-4.5 tons/ha',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // Potato varieties (crop_id: 4)
            [
                'crop_id' => 4,
                'name' => 'Kufri Jyoti',
                'name_np' => 'कुफरी ज्योति',
                'description' => 'White-skinned variety with good storage quality',
                'maturity_days' => '90-100',
                'yield_potential' => '20-25 tons/ha',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'crop_id' => 4,
                'name' => 'Cardinal',
                'name_np' => 'कार्डिनल',
                'description' => 'Red-skinned, high-yielding variety',
                'maturity_days' => '80-90',
                'yield_potential' => '25-30 tons/ha',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'crop_id' => 4,
                'name' => 'Janakdev',
                'name_np' => 'जनकदेव',
                'description' => 'Suitable for processing and table purpose',
                'maturity_days' => '100-110',
                'yield_potential' => '22-28 tons/ha',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // Mustard varieties (crop_id: 5)
            [
                'crop_id' => 5,
                'name' => 'Unnati',
                'name_np' => 'उन्नति',
                'description' => 'Early maturing, high oil content variety',
                'maturity_days' => '90-100',
                'yield_potential' => '1.2-1.5 tons/ha',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'crop_id' => 5,
                'name' => 'Pragati',
                'name_np' => 'प्रगति',
                'description' => 'Bold-seeded variety with high oil content',
                'maturity_days' => '100-110',
                'yield_potential' => '1.3-1.6 tons/ha',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // Tomato varieties (crop_id: 6)
            [
                'crop_id' => 6,
                'name' => 'Srijana',
                'name_np' => 'सृजन',
                'description' => 'F1 hybrid with bacterial wilt resistance',
                'maturity_days' => '65-70',
                'yield_potential' => '80-100 tons/ha',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'crop_id' => 6,
                'name' => 'Roma',
                'name_np' => 'रोमा',
                'description' => 'Determinate, processing type variety',
                'maturity_days' => '70-75',
                'yield_potential' => '70-80 tons/ha',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'crop_id' => 6,
                'name' => 'Manisha',
                'name_np' => 'मनीषा',
                'description' => 'Open-pollinated, bacterial wilt resistant variety',
                'maturity_days' => '75-80',
                'yield_potential' => '60-70 tons/ha',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // Cauliflower varieties (crop_id: 7)
            [
                'crop_id' => 7,
                'name' => 'Kathmandu Local',
                'name_np' => 'काठमाडौं लोकल',
                'description' => 'Traditional variety for winter season',
                'maturity_days' => '90-100',
                'yield_potential' => '25-30 tons/ha',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'crop_id' => 7,
                'name' => 'Snow Mystique',
                'name_np' => 'स्नो मिस्टिक',
                'description' => 'Early maturing hybrid for autumn season',
                'maturity_days' => '55-60',
                'yield_potential' => '30-35 tons/ha',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // Lentil varieties (crop_id: 8)
            [
                'crop_id' => 8,
                'name' => 'Khajura-1',
                'name_np' => 'खजुरा-१',
                'description' => 'Bold-seeded, high-yielding variety',
                'maturity_days' => '115-125',
                'yield_potential' => '1.2-1.5 tons/ha',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'crop_id' => 8,
                'name' => 'Shital',
                'name_np' => 'शीतल',
                'description' => 'Medium-sized, high-yielding variety',
                'maturity_days' => '120-130',
                'yield_potential' => '1-1.2 tons/ha',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // Ginger varieties (crop_id: 9)
            [
                'crop_id' => 9,
                'name' => 'Nase',
                'name_np' => 'नासे',
                'description' => 'Local variety with high oil content',
                'maturity_days' => '240-270',
                'yield_potential' => '20-25 tons/ha',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'crop_id' => 9,
                'name' => 'Kapurkot',
                'name_np' => 'कपुरकोट',
                'description' => 'High-yielding variety with disease resistance',
                'maturity_days' => '250-280',
                'yield_potential' => '22-27 tons/ha',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // Cardamom varieties (crop_id: 10)
            [
                'crop_id' => 10,
                'name' => 'Ramsey',
                'name_np' => 'रामसे',
                'description' => 'Traditional variety with high aroma',
                'maturity_days' => 'Perennial',
                'yield_potential' => '400-500 kg/ha',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'crop_id' => 10,
                'name' => 'Golsey',
                'name_np' => 'गोलसे',
                'description' => 'Improved variety with larger capsules',
                'maturity_days' => 'Perennial',
                'yield_potential' => '450-550 kg/ha',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
    
        DB::table('crop_varieties')->insert($cropVarieties);
    }
}