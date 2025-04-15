<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Master\Models\Designation;

class DesignationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $designations = [
            ['code' => 'MGR', 'name' => 'Program Director', 'name_np' => 'कार्यक्रम निर्देशक', 'status' => false],
            ['code' => 'ADM', 'name' => 'Admin', 'name_np' => 'प्रशासन', 'status' => false],
            ['code' => 'COO', 'name' => 'Coordinator', 'name_np' => 'समन्वयक', 'status' => false],
            ['code' => 'PO', 'name' => 'Program Officer', 'name_np' => 'कार्यक्रम अधिकृत', 'status' => false],
            ['code' => 'RO', 'name' => 'Regional Officer', 'name_np' => 'क्षेत्रीय अधिकृत', 'status' => false],
            ['code' => 'DO', 'name' => 'District Officer', 'name_np' => 'जिल्ला अधिकृत', 'status' => false],
            ['code' => 'MNE', 'name' => 'MNE Expert', 'name_np' => 'अनुगमन तथा मूल्याङ्कन विशेषज्ञ', 'status' => false],
            ['code' => 'GUE', 'name' => 'Guest', 'name_np' => 'अतिथि', 'status' => false],
            ['code' => 'TO', 'name' => 'Technical Officer', 'name_np' => 'प्राविधिक अधिकृत', 'status' => false],
            ['code' => 'CO', 'name' => 'Cluster Officer', 'name_np' => 'क्लस्टर अधिकृत', 'status' => false],
            ['code' => 'CLK', 'name' => 'Clerk', 'name_np' => 'क्लर्क', 'status' => false],
            ['code' => 'AST', 'name' => 'Assistant', 'name_np' => 'सहायक', 'status' => false],
            ['code' => 'TECH', 'name' => 'Technician', 'name_np' => 'प्राविधिक', 'status' => false],
            ['code' => 'CP', 'name' => 'Chairman','name_np' => 'सभापति','status' => true],
            ['code'=>'VCP','name'=>'Vice Chairman','name_np'=>'उप सभापती','status' => true],
            ['code'=>'sct','name'=>'Secretary','name_np'=>'सचिव','status' => true],
            ['code'=>'TR','name'=>'Treasurer','name_np'=>'कोषागार','status' => true],
            ['code'=>'mmbr','name'=>'Member','name_np'=>'सदस्य','status' => true],
        ];

        foreach ($designations as $designation) {
            Designation::create($designation);
        }
    }
}
