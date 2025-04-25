<?php

namespace Modules\Settings\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmailConfigurationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('email_configurations')->insert([
            // Mailtrap configuration (for testing)
            [
                'mail_mailer' => 'smtp',
                'mail_host' => 'sandbox.smtp.mailtrap.io',
                'mail_port' => 2525,
                'mail_username' => 'c2a0503a79a13e',
                'mail_password' => '82236c437712de',
                'mail_encryption' => 'tls',
                'mail_from_address' => 'test@nea.com',
                'mail_from_name' => 'Test NEA',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Production configuration (e.g., using a service like SendGrid)
            [
                'mail_mailer' => 'smtp',
                'mail_host' => '192.168.8.100',
                'mail_port' => 587,
                'mail_username' => 'dms.ptd@nea.org.np',
                'mail_password' => 'Ptd@1234',
                'mail_encryption' => 'tls',
                'mail_from_address' => 'dms.ptd@nea.org.np',
                'mail_from_name' => 'PTD DMS Alert',
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
