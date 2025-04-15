<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Master\Models\Province;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = [
            ['code' => 2001, 'name' => 'Koshi', 'name_np' => 'कोशी प्रदेश','status'=>true],
            ['code' => 2002, 'name' => 'Madhesh', 'name_np' => 'मधेश प्रदेश','status'=>true],
            ['code' => 2003, 'name' => 'Bagmati', 'name_np' => 'बागमती प्रदेश','status'=>true],
            ['code' => 2004, 'name' => 'Gandaki', 'name_np' => 'गण्डकी प्रदेश','status'=>true],
            ['code' => 2005, 'name' => 'Lumbini', 'name_np' => 'लुम्बिनी प्रदेश','status'=>true],
            ['code' => 2006, 'name' => 'Karnali', 'name_np' => 'कर्णाली प्रदेश','status'=>true],
            ['code' => 2007, 'name' => 'Sudurpashchim', 'name_np' => 'सुदूरपश्चिम प्रदेश','status'=>true],
        ];

        foreach ($provinces as $province) {
            Province::create($province);
        }
    }
}
