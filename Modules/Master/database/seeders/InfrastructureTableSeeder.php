<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InfrastructureTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $infrastructures = [
            ['code' => 'I001', 'name' => 'Irrigation Canal', 'name_np' => 'सिचाइ कुलो', 'status' => true],
            ['code' => 'I002', 'name' => 'Collection Center', 'name_np' => 'संकलन केन्द्र', 'status' => true],
            ['code' => 'I003', 'name' => 'Storage Building', 'name_np' => 'भण्डारण भवन', 'status' => true],
            ['code' => 'I004', 'name' => 'Green House', 'name_np' => 'हरित गृह', 'status' => true],
            ['code' => 'I005', 'name' => 'Drying Platform', 'name_np' => 'सुकाउने चोकठो', 'status' => true],
        ];

        DB::table('infrastructures')->insert($infrastructures);
    }
}
