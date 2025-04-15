<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Master\Models\Cluster;

class ClustersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clusters = [
            [
                'code' => 'CLUSTER-001',
                'cluster_type_id' => 1,
                'provinces'=> json_encode([3, 4]),  // Pass an array here
                'districts' => json_encode([31, 45]),  // Pass an array here
                'local_levels' => json_encode([342, 348, 388, 392]),  // Pass an array here
                'name' => 'Gorkha',
                'name_np' => 'गोर्खा',
                'status'=>true
            ],
            [
                'code' => 'CLUSTER-002',
                'cluster_type_id' => 1,
                'provinces'=> json_encode([3]),  // Pass an array here
                'districts' =>json_encode([25, 28]),  // Pass an array here
                'local_levels' => json_encode([270, 271, 310, 312]),  // Pass an array here
                'name' => 'Sindupalchowk',
                'name_np' => 'सिन्धुपाल्चोक',
                'status'=>true
            ],
            [
                'code' => 'CLUSTER-003',
                'cluster_type_id' => 2, 
                'provinces'=> json_encode([2]),  // Pass an array here
                'districts' => json_encode([21, 22]),  // Pass an array here
                'local_levels' => json_encode([143, 146, 160, 169]),  // Pass an array here
                'name' => 'Dhanusa',
                'name_np' => 'धनुषा',
                'status'=>true
            ],
            [
                'code' => 'CLUSTER-004',
                'cluster_type_id' => 2, 
                'provinces'=> json_encode([2]),  // Pass an array here
                'districts' =>json_encode([19, 20]),  // Pass an array here
                'local_levels' =>json_encode([185, 188, 191, 200]),  // Pass an array here
                'name' => 'Saptari',
                'name_np' => 'सप्तरी',
                'status'=>true
            ],
            //central level consultant
            [
                'code' => 'CLUSTER-005',
                'cluster_type_id' => 2, 
                'provinces'=> json_encode([3]),  // Pass an array here
                'districts' =>json_encode([24]),  // Pass an array here
                'local_levels' =>json_encode([386]),  // Pass an array here
                'name' => 'Central Level Consultant',
                'name_np' => 'केन्द्रीय स्तरको परामर्शदाता',
                'status'=>true
            ],

        
        
        




        //     [
        //         'code' => 'CLUSTER-003',
        //         'cluster_type_id' => 1,
        //         'district_id' => 2,
        //         'name' => 'Assets/Infrastructure Receiving Beneficiaries',
        //         'name_np' => 'सम्पत्ति/पूर्वाधार प्राप्त गर्ने लाभग्राहीहरू',
        //     ],
        //     [
        //         'code' => 'CLUSTER-004',
        //         'cluster_type_id' => 2,
        //         'district_id' => 1,
        //         'name' => 'CSA Technology Validation in FFS/Producer Groups',
        //         'name_np' => 'एफएफएस/उत्पादक समूहमा सीएसए प्रविधि प्रमाणीकरण',
        //     ],
        //     [
        //         'code' => 'CLUSTER-005',
        //         'cluster_type_id' => 2,
        //         'district_id' => 3,
        //         'name' => 'Crop Productivity and Seed Replacement Rate',
        //         'name_np' => 'फसल उत्पादकत्व र बीउ प्रतिस्थापन दर',
        //     ],
        //     [
        //         'code' => 'CLUSTER-006',
        //         'cluster_type_id' => 1,
        //         'district_id' => 2,
        //         'name' => 'FFS Data Recording Adoption and Dissemination of Improved CSA Technology for Crop',
        //         'name_np' => 'फसलका लागि सुधारिएको सीएसए प्रविधिको एफएफएस डाटा रेकर्डिङ, अपनाउने र प्रसार',
        //     ],
        //     [
        //         'code' => 'CLUSTER-007',
        //         'cluster_type_id' => 3,
        //         'district_id' => 1,
        //         'name' => 'CSA Technology Demonstration',
        //         'name_np' => 'सीएसए प्रविधि प्रदर्शन',
        //     ],
        //     [
        //         'code' => 'CLUSTER-008',
        //         'cluster_type_id' => 2,
        //         'district_id' => 2,
        //         'name' => 'Farmers participating in field days',
        //         'name_np' => 'खेत दिवसमा सहभागी किसानहरू',
        //     ],
        //     [
        //         'code' => 'CLUSTER-009',
        //         'cluster_type_id' => 1,
        //         'district_id' => 3,
        //         'name' => 'Training/workshop/exposure visit/street drama for group beneficiaries',
        //         'name_np' => 'समूह लाभग्राहीहरूका लागि तालिम/कार्यशाला/अवलोकन भ्रमण/सडक नाटक',
        //     ],
        //     [
        //         'code' => 'CLUSTER-010',
        //         'cluster_type_id' => 3,
        //         'district_id' => 1,
        //         'name' => 'Training/Workshop/Street Drama/Exposure Visit Participants Record (Government or other stockholders)',
        //         'name_np' => 'तालिम/कार्यशाला/सडक नाटक/अवलोकन भ्रमण सहभागी रेकर्ड (सरकार वा अन्य सरोकारवालाहरू)',
        //     ],
        //     [
        //         'code' => 'CLUSTER-021',
        //         'cluster_type_id' => 2,
        //         'district_id' => 2,
        //         'name' => 'Producer Group Details',
        //         'name_np' => 'उत्पादक समूह विवरण',
        //     ],
        //     [
        //         'code' => 'CLUSTER-163',
        //         'cluster_type_id' => 1,
        //         'district_id' => 3,
        //         'name' => 'Record of Small and Matching Grant',
        //         'name_np' => 'सानो र मिलान अनुदानको रेकर्ड',
        //     ],
        // ];
        ];

        foreach ($clusters as $CLUSTER) {
            DB::table('mst_clusters')->insert([
                'code' => $CLUSTER['code'],
                'cluster_type_id' => $CLUSTER['cluster_type_id'],
                'provinces' => $CLUSTER['provinces'],
                'districts' => $CLUSTER['districts'],
                'local_levels' => $CLUSTER['local_levels'],
                'name' => $CLUSTER['name'],
                'name_np' => $CLUSTER['name_np'],
                'description' => null,
                'status' => true, // Default as per schema
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
