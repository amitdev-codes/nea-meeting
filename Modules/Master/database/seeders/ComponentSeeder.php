<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ComponentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $components = [
            [
                'name' => 'Component A',
                'name_np' => 'कोम्पोनेंट A',
                'description' => 'Climate and Nutrition Smart Agricultural Technology Adaptation and Dissemination',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Component B',
                'name_np' => 'कोम्पोनेंट B',
                'description' => 'Income Generation and Diversification',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Component C',
                'name_np' => 'कोम्पोनेंट C',
                'description' => 'Improving Nutrition Security',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Component D',
                'name_np' => 'कोम्पोनेंट D',
                'description' => 'Project Mnaagement, Communication and M&E ',
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Technology Transfer',
                'name_np' => 'प्रविधि स्थानान्तरण',
                'description' => 'Transfer of agricultural technologies to farmers',
                'status' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Capacity Building',
                'name_np' => 'क्षमता विकास',
                'description' => 'Building capacity of local farmers and agricultural workers',
                'status' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Research & Development',
                'name_np' => 'अनुसन्धान तथा विकास',
                'description' => 'Research and development of new agricultural techniques',
                'status' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Market Development',
                'name_np' => 'बजार विकास',
                'description' => 'Development of market linkages for agricultural products',
                'status' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
        

        DB::table('components')->insert($components);
    }
}
