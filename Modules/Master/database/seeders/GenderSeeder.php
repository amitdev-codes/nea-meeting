<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Master\Models\Gender;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genders = [
            ['code' => 'M', 'name' => 'Male', 'name_np' => 'पुरुष','status'=>true],
            ['code' => 'F', 'name' => 'Female', 'name_np' => 'महिला','status'=>true],
            ['code' => 'O', 'name' => 'Other', 'name_np' => 'अन्य','status'=>true],
            ['code' => 'N', 'name' => 'Not Specified', 'name_np' => 'निर्दिष्ट गरिएको छैन','status'=>false],
        ];

        foreach ($genders as $gender) {
            Gender::create($gender);
        }
    }
}
