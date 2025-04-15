<?php

namespace Modules\Master\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CropSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $crops = [
            ['name' => 'Rice', 'name_np' => 'धान'],
            ['name' => 'Early rice', 'name_np' => 'अगुवा धान'],
            ['name' => 'Maize', 'name_np' => 'मकै'],
            ['name' => 'Wheat', 'name_np' => 'गहुँ'],
            ['name' => 'Potato', 'name_np' => 'आलु'],
            ['name' => 'Buckwheat', 'name_np' => 'फापर'],
            ['name' => 'Finger millet', 'name_np' => 'कोदो'],
            ['name' => 'Lentil', 'name_np' => 'मसुरो'],
            ['name' => 'Bean', 'name_np' => 'गेडागुडी'],
            ['name' => 'Mungbean', 'name_np' => 'मास'],
            ['name' => 'Black Gram', 'name_np' => 'कालो मास'],
            ['name' => 'Forage', 'name_np' => 'चारा'],
            ['name' => 'Brinjal', 'name_np' => 'भण्टा'],
            ['name' => 'Vegetables', 'name_np' => 'तरकारी'],
            ['name' => 'Cauliflower', 'name_np' => 'काउली'],
            ['name' => 'Cabbage', 'name_np' => 'बन्दाकोपी'],
            ['name' => 'Tomato', 'name_np' => 'गोलभेंडा'],
            ['name' => 'Broad Leaf Mustard', 'name_np' => 'रायो'],
            ['name' => 'Onion', 'name_np' => 'प्याज'],
            ['name' => 'Radish', 'name_np' => 'मूला'],
            ['name' => 'Turnip', 'name_np' => 'सलगम'],
            ['name' => 'Capsicum', 'name_np' => 'भोपिरो'],
            ['name' => 'Carrot', 'name_np' => 'गाजर'],
            ['name' => 'Spinach', 'name_np' => 'पालुङ्गो'],
            ['name' => 'Okra', 'name_np' => 'भिन्डी'],
            ['name' => 'Lemon', 'name_np' => 'कागती'],
            ['name' => 'Orange', 'name_np' => 'सुन्तला'],
            ['name' => 'Banana', 'name_np' => 'केरा'],
            ['name' => 'Papaya', 'name_np' => 'मेवा'],
            ['name' => 'Pineapple', 'name_np' => 'भुइँकटहर'],
            ['name' => 'Sweet Potato', 'name_np' => 'सखरखण्ड'],
            ['name' => 'Broccoli', 'name_np' => 'ब्रोकाउली'],
            ['name' => 'Garlic', 'name_np' => 'लसुन'],
            ['name' => 'Pumpkin', 'name_np' => 'फर्सी'],
            ['name' => 'Guava', 'name_np' => 'अमरुद'],
            ['name' => 'Mango', 'name_np' => 'आँप'],
            ['name' => 'Jackfruit', 'name_np' => 'कटहर'],
            ['name' => 'Pomegranate', 'name_np' => 'अनार'],
        ];

        foreach ($crops as $crop) {
            DB::table('crops')->insert([
                'name' => $crop['name'],
                'name_np' => $crop['name_np'],
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
