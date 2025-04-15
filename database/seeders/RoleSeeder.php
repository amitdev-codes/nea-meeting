<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate roles table
        DB::table('roles')->truncate();

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Define roles with their Nepali names and codes
        $roles = [
            ['name' => 'superadmin', 'name_np' => 'सुपर एडमिन', 'code' => 'SA'],
            ['name' => 'admin', 'name_np' => 'एडमिन', 'code' => 'AD'],
            ['name' => 'guest', 'name_np' => 'अतिथि', 'code' => 'GU'],
            ['name' => 'Field Level Technician', 'name_np' => 'क्षेत्रीय स्तरका प्राविधिक', 'code' => 'FLT'],
            ['name' => 'Crop Production Specialist', 'name_np' => 'बाली उत्पादन विशेषज्ञ', 'code' => 'CPS'],
            ['name' => 'Livestock Production Specialist', 'name_np' => 'पशुपालन विशेषज्ञ', 'code' => 'LPS'],
            ['name' => 'Nutrition Specialist', 'name_np' => 'पोषण विशेषज्ञ', 'code' => 'NS'],
            ['name' => 'Agribusiness & Enterprise Development Specialist', 'name_np' => 'कृषि व्यवसाय तथा उद्यम विकास विशेषज्ञ', 'code' => 'AEDS'],
            ['name' => 'Cluster M&E Specialist', 'name_np' => 'क्लस्टर अनुगमन तथा मूल्यांकन विशेषज्ञ', 'code' => 'CMES'],
            ['name' => 'Cluster Chief/Cluster Officer', 'name_np' => 'क्लस्टर प्रमुख/क्लस्टर अधिकारी', 'code' => 'CCO'],
            ['name' => 'PMU Specialist (Crop, Livestock, Nutrition, Agribusiness)', 'name_np' => 'पीएमयू विशेषज्ञ (बाली, पशु, पोषण, कृषि व्यवसाय)', 'code' => 'PMUS'],
            ['name' => 'Senior M&E Officer PMU', 'name_np' => 'वरिष्ठ अनुगमन तथा मूल्यांकन अधिकृत (PMU)', 'code' => 'SMEO'],
        ];

        // Insert roles into the database
        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
