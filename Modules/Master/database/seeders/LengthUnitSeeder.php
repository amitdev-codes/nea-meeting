<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Master\Models\LengthUnit;

class LengthUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lengthUnits = [
            ['code' => 'RS', 'name' => 'RUPEE', 'name_np' => 'रुपैया','status'=>false],
            ['code' => 'TH', 'name' => 'UNIT', 'name_np' => 'थान','status'=>false],
            ['code' => 'PC', 'name' => 'PIECE', 'name_np' => 'पिस','status'=>false],
            ['code' => 'KL', 'name' => 'KILO', 'name_np' => 'किलो','status'=>false],
            ['code' => 'LT', 'name' => 'LITRE', 'name_np' => 'लिटर','status'=>false],
            ['code' => 'DZ', 'name' => 'DOZEN', 'name_np' => 'दर्जन','status'=>false],
            ['code' => 'SP', 'name' => 'STRIP', 'name_np' => 'पत्ता','status'=>false],

            ['code' => 'no', 'name' => 'No', 'name_np' => 'संख्या', 'status' => true],
            ['code' => 'sets', 'name' => 'Sets', 'name_np' => 'सेट', 'status' => true],
            ['code' => 'kg', 'name' => 'Kg', 'name_np' => 'किलोग्राम', 'status' => true],
            ['code' => 'gram', 'name' => 'Gram', 'name_np' => 'ग्राम', 'status' => true],

        ];
        foreach ($lengthUnits as $lengthUnit) {
            LengthUnit::create($lengthUnit);
        }
    }
}
