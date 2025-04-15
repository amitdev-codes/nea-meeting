<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SubComponentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $subComponents = [
            [
                'component_id' => 1,
                'name' => 'Technology Adaptation and Testing',
                'name_np' => 'प्रविधि अनुकूलन र परीक्षण',
                'description' => 'Agricultural practices related to crop production and management',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'component_id' => 1,
                'name' => 'Technology Dissemination and Farmers Skill Development',
                'name_np' => 'प्रविधि प्रसार र कृषक सीप विकास',
                'description' => 'Agricultural practices related to crop production and management',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'component_id' => 2,
                'name' => 'Strengthening Producer Groups (PG)',
                'name_np' => 'उत्पादक समूह (PG) को सुदृढीकरण',
                'description' => 'Agricultural practices related to crop production and management',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'component_id' => 2,
                'name' => 'Building Market Linkages through Productive Alliances',
                'name_np' => 'उत्पादक सम्बन्ध मार्फत बजार जडान निर्माण',
                'description' => 'Agricultural practices related to crop production and management',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'component_id' => 3,
                'name' => 'Institutional Capacity Strengthening',
                'name_np' => 'संस्थागत क्षमता सुदृढीकरण',
                'description' => 'Agricultural practices related to crop production and management',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'component_id' => 3,
                'name' => 'Nutrition Field School (NFS) and Home Nutrition Gardens (HNGs)',
                'name_np' => 'पोषण क्षेत्र स्कूल (NFS) र घर पोषण बगैंचा (HNGs)',
                'description' => 'Agricultural practices related to crop production and management',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            //false
            [
                'component_id' => 1,
                'name' => 'Crop',
                'name_np' => 'बाली',
                'description' => 'Agricultural practices related to crop production and management',
                'status' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'component_id' => 2,
                'name' => 'Livestock',
                'name_np' => 'पशुपालन',
                'description' => 'Management and development of animal husbandry',
                'status' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'component_id' => 3,
                'name' => 'Business',
                'name_np' => 'व्यवसाय',
                'description' => 'Entrepreneurial and financial management in the agricultural sector',
                'status' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'component_id' => 4,
                'name' => 'Nutrition',
                'name_np' => 'पोषण',
                'description' => 'Ensuring proper dietary intake and food security',
                'status' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('sub_components')->insert($subComponents);
    }
}
