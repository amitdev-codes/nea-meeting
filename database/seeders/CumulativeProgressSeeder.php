<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\CumulativeProgress;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CumulativeProgressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'fiscal_year_id' => 7,
                'project_start_date' => Carbon::parse('2022-07-01'),
                'project_end_date' => Carbon::parse('2023-06-30'),
                'total_estimated_expenditure' => 1000000.00,
                'total_given_expenditure' => 600000.00,
                'total_budget' => 1200000.00,
                'total_disbursed' => 500000.00,
                'total_group_formed_target' => 100.00,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert the data into the cumulative_progress table
        foreach ($data as $record) {
            CumulativeProgress::create($record);
        }
    }
}
