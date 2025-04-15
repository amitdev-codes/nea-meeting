<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Master\Models\Caste;

class CasteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $castes = [
            ['code' => 'CAS001', 'name' => 'Dalit', 'name_np' => 'दलित','status'=>true],
            ['code' => 'CAS002', 'name' => 'Janajati', 'name_np' => 'आदिवासी/जनजाति','status'=>true],
            ['code' => 'CAS003', 'name' => 'Brahmin/Chhetri', 'name_np' => 'ब्राह्मण/क्षेत्री','status'=>true],
            ['code' => 'CAS004', 'name' => 'Muslim', 'name_np' => 'मुस्लिम','status'=>true],
            ['code' => 'CAS005', 'name' => 'Others', 'name_np' => 'अन्य','status'=>true],
        ];

        foreach ($castes as $caste) {
            Caste::create($caste);
        }
    }
}
