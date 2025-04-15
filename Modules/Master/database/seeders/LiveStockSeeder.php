<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LiveStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('livestocks')->insert([
            [
                'code' => 'B001',
                'name' => 'Cattle',
                'name_np' => 'जर्सी',
                'description' => 'A breed of dairy cattle known for high milk production and docile temperament.',
                'status' => true
            ],
            [
                'code' => 'B002',
                'name' => 'Sahiwal',
                'name_np' => 'साहीवाल',
                'description' => 'A breed of zebu cattle from the Indian subcontinent, known for its heat tolerance and good milk production.',
                'status' => true
            ],
            [
                'code' => 'B003',
                'name' => 'Holstein',
                'name_np' => 'होल्सटिन',
                'description' => 'A popular breed of dairy cattle, known for its high milk yield, originating from the Netherlands.',
                'status' => false,
            ],
            [
                'code' => 'B004',
                'name' => 'Red Sindhi',
                'name_np' => 'रेड सिंधी',
                'description' => 'A breed of zebu cattle native to Pakistan, known for its excellent milk production in hot climates.',
                'status' => true
            ]
        ]);
    }
}
