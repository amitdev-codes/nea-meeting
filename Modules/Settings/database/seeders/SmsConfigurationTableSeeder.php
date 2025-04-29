<?php

namespace Modules\Settings\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SmsConfigurationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('sms_configurations')->insert([
            'sms_provider_id' => 1, // Assumes an SMS provider with ID 1 exists
            'api_token' => 'v2_yKAzuqfPzE9BXfwGu1T3SA366eM.N8hb',
            'sender_id' => 'NEA',
            'base_url' => 'https://smsportal.nea.org.np/api/sms?',
            'is_active' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
