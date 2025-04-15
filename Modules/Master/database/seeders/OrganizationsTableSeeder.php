<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizations = [
            [
                'code' => 'MDS',
                'name' => 'Managing Directors Secretariat',
                'name_np' => 'प्रबन्ध निर्देशक सचिवालय',
                'status' => true,
            ],
            [
                'code' => 'IAD',
                'name' => 'Internal Audit Department',
                'name_np' => 'आन्तरिक लेखा परीक्षण विभाग',
                'status' => true,
            ],
            [
                'code' => 'PMID',
                'name' => 'Planning Monitoring And Information Technology Directorate',
                'name_np' => 'योजना, अनुगमन तथा सूचना प्रविधि निर्देशनालय',
                'status' => true,
            ],
            [
                'code' => 'BDD',
                'name' => 'Business development Directorate',
                'name_np' => 'व्यवसाय विकास निर्देशनालय',
                'status' => true,
            ],
            [
                'code' => 'ADMIN',
                'name' => 'Administration Directorate',
                'name_np' => 'प्रशासन निर्देशनालय',
                'status' => true,
            ],
            [
                'code' => 'FIN',
                'name' => 'Finance Directorate',
                'name_np' => 'लेखा निर्देशनालय',
                'status' => true,
            ],
            [
                'code' => 'GEN',
                'name' => 'Generation Directorate',
                'name_np' => 'उत्पादन निर्देशनालय',
                'status' => true,
            ],
            [
                'code' => 'TRANS',
                'name' => 'Transmission Directorate',
                'name_np' => 'प्रसारण निर्देशनालय',
                'status' => true,
            ],
            [
                'code' => 'DIST',
                'name' => 'Distribution And Consumer Service Directorate',
                'name_np' => 'वितरण तथा उपभोक्ता सेवा निर्देशनालय',
                'status' => true,
            ],
            [
                'code' => 'ENG',
                'name' => 'Engineering Services Directorate',
                'name_np' => 'इन्जिनियरिङ सेवा निर्देशनालय',
                'status' => true,
            ],
            [
                'code' => 'PMD',
                'name' => 'Project Management Directorate',
                'name_np' => 'परियोजना व्यवस्थापन निर्देशनालय',
                'status' => true,
            ],
        ];
    
        DB::table('organizations')->insert($organizations);
    }
    
}
