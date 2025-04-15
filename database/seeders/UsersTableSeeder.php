<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Address;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Modules\Master\Models\District;
use Modules\Master\Models\Province;
use Illuminate\Support\Facades\Hash;
use Modules\Master\Models\Component;
use Modules\Master\Models\LocalLevel;
use Modules\Master\Models\Designation;
use Modules\Master\Models\SubComponent;
use Spatie\Permission\Models\Permission;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate dependent tables first
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('users')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('addresses')->truncate(); // Truncate addresses table

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create roles if they do not exist
        $roleNames = [
            'superadmin' => 'superadmin',
            'admin' => 'admin',
            'guest' => 'guest',
            'Field Level Technician' => 'field_tech',
            'Crop Production Specialist (Cluster)' => 'crop_prod_cluster',
            'Livestock Production Specialist (Cluster)' => 'livestock_prod_cluster',
            'Nutrition Specialist (Cluster)' => 'nutrition_cluster',
            'Agri Business & Enterprises Development Specialist (Cluster)' => 'agribusiness_cluster',
            'M & E Specialist (Cluster)' => 'me_specialist_cluster',
            'Cluster Chief/Cluster Officer' => 'cluster_chief',
            'PMU Specialist (Crop, Livestock, Nutrition, Agribusiness)' => 'pmu_specialist',
            'Senior M&E Officer PMU' => 'senior_me_pmu',
            'Team Leader' => 'team_leader',
            'M&E Specialist' => 'me_specialist',
            'Livestock Specialist' => 'livestock_specialist',
            'Capacity Dev. Specialist' => 'capacity_dev_specialist',
            'Crop Prod. Specialist' => 'crop_prod_specialist',
            'GESS Specialist' => 'gess_specialist',
            'Agribusiness and Market Linkage Specialist' => 'agribusiness_market',
            'Nutrition cum BCC Specialist' => 'nutrition_bcc_specialist',
            'Adm & Finance Specialist' => 'adm_finance_specialist',
            'Administrative Assistant' => 'admin_assistant',
            'Office Assistant' => 'office_assistant',
            'Driver' => 'driver','employee' => 'employee',
        ];
        
        $roles = [];
        foreach ($roleNames as $name => $code) {
            $roles[$name] = Role::firstOrCreate([
                'name' => $name,
                'code' => $code,
                'guard_name' => 'web'
            ]);
        }

        // Assign permissions to roles
        // Assign permissions to roles
        $allPermissions = Permission::all()->pluck('name')->toArray();

        // Superadmin gets all permissions
        if (isset($roles['superadmin'])) {
            $roles['superadmin']->syncPermissions($allPermissions);
        }

        // Admin permissions
        if (isset($roles['admin'])) {
            $adminPermissions = [
                'view-users', 'create-users', 'edit-users', 'delete-users',
                'view-roles', 'edit-roles',
                'view-permissions',
                'view-settings', 'edit-settings',
                'view-site-settings', 'edit-site-settings',
                'view-logs',
            ];
            $roles['admin']->syncPermissions($adminPermissions);
        }

        // Guest permissions
        if (isset($roles['guest'])) {
            $guestPermissions = ['view-settings', 'view-contacts'];
            $roles['guest']->syncPermissions($guestPermissions);
        }

        // Assign permissions for other roles
        $restrictedPermissions = ['view-settings', 'view-logs'];
        foreach ($roles as $key => $role) {
            if (!in_array($key, ['superadmin', 'admin', 'guest'])) {
                $role->syncPermissions($restrictedPermissions);
            }
        }

        // Entry level permissions
        $entryLevelPermissions = ['view-form-entries', 'create-form-entries'];
        $entryLevelRoles = [
            'Livestock Production Specialist (Cluster)',
            'Crop Production Specialist (Cluster)',
            'Nutrition Specialist (Cluster)',
            'Agri Business & Enterprises Development Specialist (Cluster)',
            'M & E Specialist (Cluster)'
        ];
        foreach ($entryLevelRoles as $roleName) {
            if (isset($roles[$roleName])) {
                $roles[$roleName]->syncPermissions($entryLevelPermissions);
            }
        }

        // Verification level 1
        $verification1Permissions = ['view-form-entries', 'create-form-entries', 'edit-form-entries',
        'view-form-verifications', 'create-form-verifications', 'edit-form-verifications'];
        if (isset($roles['M & E Specialist (Cluster)'])) {
            $roles['M & E Specialist (Cluster)']->syncPermissions($verification1Permissions);
        }

        // Verification level 2
        $verification2Permissions = ['view-form-entries', 'create-form-entries', 'edit-form-entries',
        'view-form-verifications', 'create-form-verifications', 'edit-form-verifications',];
        if (isset($roles['M&E Specialist'])) {
            $roles['M&E Specialist']->syncPermissions($verification2Permissions);
        }

        // Verification level 3
        $verification3Permissions = ['view-form-entries', 'create-form-entries', 'edit-form-entries',
        'view-form-verifications', 'create-form-verifications', 'edit-form-verifications',];
        $verification3Roles = [
            'Livestock Specialist',
            'Crop Prod. Specialist',
            'Nutrition cum BCC Specialist',
            'M&E Specialist',
            'Agribusiness and Market Linkage Specialist'
        ];
        foreach ($verification3Roles as $roleName) {
            if (isset($roles[$roleName])) {
                $roles[$roleName]->syncPermissions($verification3Permissions);
            }
        }

        // Verification level 4
        $verification4Permissions = ['view-form-entries', 'create-form-entries', 'edit-form-entries',
        'view-form-verifications', 'create-form-verifications', 'edit-form-verifications',];
        $verification4Roles = [
            'PMU Specialist (Crop, Livestock, Nutrition, Agribusiness)',
            'Senior M&E Officer PMU'
        ];
        foreach ($verification4Roles as $roleName) {
            if (isset($roles[$roleName])) {
                $roles[$roleName]->syncPermissions($verification4Permissions);
            }
        }


        // Seed Admin Users
        $adminUsers = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@dryice.com',
                'mobile_no' => '9876543210',
                'role' => 'superadmin',
            ],
            [
                'name' => 'Admin User',
                'email' => 'admin@dryice.com',
                'mobile_no' => '9876543211',
                'role' => 'admin',
            ],
            [
                'name' => 'Guest User',
                'email' => 'guest@admin.com',
                'mobile_no' => '9876543212',
                'role' => 'guest',
            ],
        ];

        foreach ($adminUsers as $adminUser) {
            $user = User::create([
                'username' => $adminUser['name'],
                'email' => $adminUser['email'],
                'mobile_no' => $adminUser['mobile_no'],
                'office_email' => $adminUser['email'],
                'office_mobile_no' => $adminUser['mobile_no'],
                'password' => Hash::make('password'),
                'status' => true,
                'password_changed_at' => now(),
                'locale' => 'np',
                'designation_id' =>1, // Replace with actual designation ID
                'section_id' =>1,
                'clusters' =>  json_encode([fake()->numberBetween(1, 4)]),
                'organization_id'=>1
            ]);

            if (isset($roles[$adminUser['role']])) {
                $user->assignRole($roles[$adminUser['role']]);
            }
        }
        //create role users
        $users = [
                    // Central Level National Consultant
                [
                    'name' => 'Poudyal, Mr. Shyam Prasad',
                    'username' => 'shyampoudyal',
                    'email' => 'shyam.poudyal1261@gmail.com',
                    'mobile_no' => '984165913',
                    'office_mobile_no' => '98023-30526',
                    'office_email' => 'shyam.poudyal@fao.org',
                    'role' => 'Team Leader',
                    'clusters' => json_encode([5]),
                    'section_id' => 6,
                    'organization_id'=>1
                ],
                [
                    'name' => 'Poudel, Mr. Rudra Prasad',
                    'username' => 'rudrapoudel',
                    'email' => 'rudra124@gmail.com',
                    'mobile_no' => '9858027583',
                    'office_mobile_no' => '98023-30527',
                    'office_email' => 'rudra.prasad.poudel@fao.org',
                    'role' => 'M&E Specialist',
                    'clusters' => json_encode([5]),
                    'section_id' => 5,
                    'organization_id'=>2
                ],
                [
                    'name' => 'Paudel, Dr. Lok Nath',
                    'username' => 'lokpaudel',
                    'email' => 'paudel.lok2015@gmail.com',
                    'mobile_no' => '9851163620',
                    'office_mobile_no' => '97900-29571',
                    'office_email' => 'lok.paudel@fao.org',
                    'role' => 'Livestock Specialist',
                    'clusters' => json_encode([5]),
                    'section_id' => 2,
                    'organization_id'=>1
                ],
                [
                    'name' => 'Humagain, Mr. Sahadev Prasad',
                    'username' => 'sahadev',
                    'email' => 'sphumagain2014@gmail.com',
                    'mobile_no' => '9851010831',
                    'office_mobile_no' => '97900-29572',
                    'office_email' => 'sahadev.humagain@fao.org',
                    'role' => 'Capacity Dev. Specialist',
                    'clusters' => json_encode([5]),
                    'section_id' => 6,
                    'organization_id'=>1
                ],
                [
                    'name' => 'Neupane, Dr. Anil Chandra',
                    'username' => 'anilneupane',
                    'email' => 'anilch111@gmail.com',
                    'mobile_no' => '9841631743',
                    'office_mobile_no' => '98023-30552',
                    'office_email' => 'anil.neupane@fao.org',
                    'role' => 'Crop Prod. Specialist',
                    'clusters' => json_encode([5]),
                    'section_id' => 6,
                    'organization_id'=>1
                ],
                [
                    'name' => 'Acharya, Mr. Shiba Prakash',
                    'username' => 'shibaacharya',
                    'email' => 'acharyashiba@gmail.com',
                    'mobile_no' => '9851080926',
                    'office_mobile_no' => '98023-30566',
                    'office_email' => 'shiba.acharya@fao.org',
                    'role' => 'GESS Specialist',
                    'clusters' => json_encode([5]),
                    'section_id' => 6,
                    'organization_id'=>1
                ],
                [
                    'name' => 'Lutel, Mr. Tek Prasad',
                    'username' => 'teklutel',
                    'email' => 'lutelto@fao.org',
                    'mobile_no' => '985112-3295',
                    'office_mobile_no' => '97900-29570',
                    'office_email' => 'tek.lutel@fao.org',
                    'role' => 'Agribusiness and Market Linkage Specialist',
                    'clusters' => json_encode([5]),
                    'section_id' => 4,
                    'organization_id'=>1

                ],
                [
                    'name' => 'Thoker, Mr. Anup',
                    'username' => 'anupthoker',
                    'email' => 'anupthoker@gmail.com',
                    'mobile_no' => '984112-92774',
                    'office_mobile_no' => '97900-29573',
                    'office_email' => 'anup.thoker@fao.org',
                    'role' => 'Nutrition cum BCC Specialist',
                    'clusters' => json_encode([5]),
                    'section_id' => 3,
                    'organization_id'=>1
                ],
                [
                    'name' => 'Ghimire, Mr. Pranaya Raj',
                    'username' => 'pranaya',
                    'email' => 'ghimire.pranaya@gmail.com',
                    'mobile_no' => '9849554390',
                    'office_mobile_no' => '98023-30523',
                    'office_email' => 'pranaya.ghimire@fao.org',
                    'role' => 'Adm & Finance Specialist',
                    'clusters' => json_encode([5]),
                    'section_id' => 6,
                    'organization_id'=>1
                ],
                [
                    'name' => 'Dahal, Ms. Rashmi',
                    'username' => 'rashmidahal',
                    'email' => 'rashmidahal04@gmail.com',
                    'mobile_no' => '9869611220',
                    'office_mobile_no' => '97900-29576',
                    'office_email' => 'rashmi.dahal@fao.org',
                    'role' => 'Administrative Assistant',
                    'clusters' => json_encode([5]),
                    'section_id' => 6,
                    'organization_id'=>1
                ],
                [
                    'name' => 'Chitrakar, Mr. Mani Raj',
                    'username' => 'manichitrakar',
                    'email' => 'mani.chitrakar@gmail.com',
                    'mobile_no' => '9860731380',
                    'office_mobile_no' => '98023-30550',
                    'office_email' => 'mani.chitrakar@fao.org',
                    'role' => 'Office Assistant',
                    'clusters' => json_encode([5]),
                    'section_id' => 6,
                    'organization_id'=>1
                ],
                [
                    'name' => 'Thapa Magar, Mr. Dil',
                    'username' => 'dilthapa',
                    'email' => 'dilbahadur08@gmail.com',
                    'mobile_no' => '984-737-0901',
                    'office_mobile_no' => '98023-30785',
                    'office_email' => 'dil.thapamagar@fao.org',
                    'role' => 'Driver',
                    'clusters' => json_encode([5]),
                    'section_id' => 6,
                    'organization_id'=>1
                ],
    
                // Gorkha Cluster
                [
                    'name' => 'Bogati, Mr Ram Bahadur',
                    'username' => 'rambogati',
                    'email' => 'ryete07@gmail.com',
                    'mobile_no' => '98418-62186',
                    'office_mobile_no' => '97900-29575',
                    'office_email' => 'ram.bogati@fao.org',
                    'role' => 'Livestock Production Specialist (Cluster)',
                    'clusters' => json_encode([1]),
                    'section_id' => 2,
                    'organization_id'=>2
                ],
                [
                    'name' => 'Sigdel, Mr Adarsha',
                    'username' => 'adarshasigdel',
                    'email' => 'adarshasigdel123@gmail.com',
                    'mobile_no' => '9855083099',
                    'office_mobile_no' => '97900-29550',
                    'office_email' => 'adarsha.sigdel@fao.org',
                    'role' => 'Crop Production Specialist (Cluster)',
                    'clusters' => json_encode([1]),
                    'section_id' => 1,
                    'organization_id'=>2
                ],
                [
                    'name' => 'Shrestha, Mr Preetam',
                    'username' => 'preetamshrestha',
                    'email' => 'preetam@gmail.com',
                    'mobile_no' => '98415-60924',
                    'office_mobile_no' => '97900-29577',
                    'office_email' => 'preetam.shrestha@fao.org',
                    'role' => 'Nutrition Specialist (Cluster)',
                    'clusters' => json_encode([1]),
                    'section_id' => 3,
                    'organization_id'=>2
                ],
                [
                    'name' => 'Neupane, Ms Durga',
                    'username' => 'durganeupane',
                    'email' => 'dneupane146@gmail.com',
                    'mobile_no' => '98462-54610',
                    'office_mobile_no' => '97900-29578',
                    'office_email' => 'durga.neupane@fao.org',
                    'role' => 'Agri Business & Enterprises Development Specialist (Cluster)',
                    'clusters' => json_encode([1]),
                    'section_id' => 4,
                    'organization_id'=>2
                ],
    
                // Sindhupalchowk Cluster
                [
                    'name' => 'Sapkota, Mr Mahesh',
                    'username' => 'maheshsapkota',
                    'email' => 'msapkota@gmail.com',
                    'mobile_no' => '985114-4490',
                    'office_mobile_no' => '97900-29574',
                    'office_email' => 'mahesh.sapkota@fao.org',
                    'role' => 'M & E Specialist (Cluster)',
                    'clusters' => json_encode([2]),
                    'section_id' => 5,
                    'organization_id'=>2
                ],
                [
                    'name' => 'Nagarkoti, Mr Krishna Bahadur',
                    'username' => 'krishnanagarkoti',
                    'email' => 'krishnanagarkoti@yahoo.com',
                    'mobile_no' => '98531-76719',
                    'office_mobile_no' => '97900-29579',
                    'office_email' => 'krishna.nagarkoti@fao.org',
                    'role' => 'Livestock Production Specialist (cluster)',
                    'clusters' => json_encode([2]),
                    'section_id' => 2,
                    'organization_id'=>3
                ],
                [
                    'name' => 'Pandey, Mr Amrit',
                    'username' => 'amritpandey',
                    'email' => 'amritpandey5566@gmail.com',
                    'mobile_no' => '985114-70087',
                    'office_mobile_no' => '97900-29580',
                    'office_email' => 'amrit.pandey@fao.org',
                    'role' => 'Crop Production Specialist (Cluster)',
                    'clusters' => json_encode([2]),
                    'section_id' => 1,
                    'organization_id'=>2
                ],
                [
                    'name' => 'Pachhai, Ms Rahita',
                    'username' => 'rahitapachhai',
                    'email' => 'rahitaa@gmail.com',
                    'mobile_no' => '985116-6791',
                    'office_mobile_no' => '97900-29581',
                    'office_email' => 'rahita.pachhai@fao.org',
                    'role' => 'Nutrition Specialist (Cluster)',
                    'clusters' => json_encode([2]),
                    'section_id' => 3,
                    'organization_id'=>2
                ],
    
                // Dhankuta Cluster
                [
                    'name' => 'Dhungel, Mr Sanjib',
                    'username' => 'sanjibdhungel',
                    'email' => 'dhungel2020@gmail.com',
                    'mobile_no' => '98511-60184',
                    'office_mobile_no' => '97900-29582',
                    'office_email' => 'sanjib.dhungel@fao.org',
                    'role' => 'M & E Specialist (Cluster)',
                    'clusters' => json_encode([3]),
                    'section_id' => 5,
                    'organization_id'=>3
                ],
                [
                    'name' => 'Panjyar, Mr Mukesh',
                    'username' => 'mukeshpanjyar',
                    'email' => 'mr.panjyar@gmail.com',
                    'mobile_no' => '98560-41169',
                    'office_mobile_no' => '97900-29583',
                    'office_email' => 'mukesh.panjyar@fao.org',
                    'role' => 'Livestock Production Specialist (cluster)',
                    'clusters' => json_encode([3]),
                    'section_id' => 2,
                    'organization_id'=>2
                ],
                [
                    'name' => 'Gupta, Mr Hari Prakash',
                    'username' => 'harigupta',
                    'email' => 'hariprakashgupta.lahan@gmail.com',
                    'mobile_no' => '98428-27211',
                    'office_mobile_no' => '97900-29584',
                    'office_email' => 'hari.gupta@fao.org',
                    'role' => 'Crop Production Specialist (Cluster)',
                    'clusters' => json_encode([3]),
                    'section_id' => 1,
                    'organization_id'=>2
                ],
                [
                    'name' => 'Kam, Mr Ananda Kumar',
                    'username' => 'anandakam',
                    'email' => 'anandakam@gmail.com',
                    'mobile_no' => '98545-53111',
                    'office_mobile_no' => '97900-29585',
                    'office_email' => 'ananda.kam@fao.org',
                    'role' => 'Nutrition Specialist (Cluster)',
                    'clusters' => json_encode([3]),
                    'section_id' => 3,
                    'organization_id'=>2
                ],
    
                // Saptari Cluster
                [
                    'name' => 'Mahato, Mr Jayeswar',
                    'username' => 'jayeswarmahato',
                    'email' => 'jayeswar.mahato@gmail.com',
                    'mobile_no' => '98528-20992',
                    'office_mobile_no' => '97900-29587',
                    'office_email' => 'jayeswar.mahato@fao.org',
                    'role' => 'Crop Production Specialist (Cluster)',
                    'clusters' => json_encode([4]),
                    'section_id' => 1,
                    'organization_id'=>2
                ],
                [
                    'name' => 'Jha, Mr Sundar',
                    'username' => 'sundarjha',
                    'email' => 'sundarjha22@gmail.com',
                    'mobile_no' => '98528-20982',
                    'office_mobile_no' => '97900-29588',
                    'office_email' => 'sundar.jha@fao.org',
                    'role' => 'Livestock Production Specialist (cluster)',
                    'clusters' => json_encode([4]),
                    'section_id' => 2,
                    'organization_id'=>2
                ],
                [
                    'name' => 'Das, Ms Lalita Kumari',
                    'username' => 'lalitadas',
                    'email' => 'daslalita115@gmail.com',
                    'mobile_no' => '98429-76974',
                    'office_mobile_no' => '97900-29589',
                    'office_email' => 'lalita.das@fao.org',
                    'role' => 'Nutrition Specialist (Cluster)',
                    'clusters' => json_encode([4]),
                    'section_id' => 3,
                    'organization_id'=>2
                ],
                [
                    'name' => 'Sah, Ms Priyanka',
                    'username' => 'priyankasah',
                    'email' => 'sahpriyanka53326@gmail.com',
                    'mobile_no' => '98455-53111',
                    'office_mobile_no' => '97900-29586',
                    'office_email' => 'priyanka.sah@fao.org',
                    'role' => 'Agri Business & Enterprises Development Specialist (Cluster)',
                    'clusters' => json_encode([4]),
                    'section_id' => 4,
                    'organization_id'=>2
                ],
            ];
    
        // Create users and assign roles
        foreach ($users as $userData) {
            $user = User::create([
                'username' => $userData['username'],
                'email' => $userData['email'],
                'mobile_no' => $userData['mobile_no'],
                'office_mobile_no' => $userData['office_mobile_no'],
                'office_email' => $userData['office_email'],
                'section_id' => $userData['section_id'],
                'clusters' => $userData['clusters'],
                'password' => bcrypt('password'), // Default password, change as needed
                'designation_id' => 1, // Placeholder, adjust as needed
                'status' => true,
                'password_changed_at' => now(),
                'organization_id'=>$userData['organization_id']
            ]);

            // Assign role to the user
            $user->assignRole($userData['role']);
        }


        
                // 10 dummy users
                $dummyUsers = [
                    [
                        'name' => 'John Admin',
                        'email' => 'john.admin@example.com',
                        'mobile_no' => '9800000001',
                        'role' => 'admin'
                    ],
                    [
                        'name' => 'Sarah Employee',
                        'email' => 'sarah.emp@example.com',
                        'mobile_no' => '9800000002',
                        'role' => 'employee'
                    ],
                    [
                        'name' => 'Mike Admin',
                        'email' => 'mike.admin@example.com',
                        'mobile_no' => '9800000003',
                        'role' => 'admin'
                    ],
                    [
                        'name' => 'Emma Employee',
                        'email' => 'emma.emp@example.com',
                        'mobile_no' => '9800000004',
                        'role' => 'employee'
                    ],
                    [
                        'name' => 'Peter Admin',
                        'email' => 'peter.admin@example.com',
                        'mobile_no' => '9800000005',
                        'role' => 'admin'
                    ],
                    [
                        'name' => 'Lisa Employee',
                        'email' => 'lisa.emp@example.com',
                        'mobile_no' => '9800000006',
                        'role' => 'employee'
                    ],
                    [
                        'name' => 'Tom Admin',
                        'email' => 'tom.admin@example.com',
                        'mobile_no' => '9800000007',
                        'role' => 'admin'
                    ],
                    [
                        'name' => 'Anna Employee',
                        'email' => 'anna.emp@example.com',
                        'mobile_no' => '9800000008',
                        'role' => 'employee'
                    ],
                    [
                        'name' => 'David Admin',
                        'email' => 'david.admin@example.com',
                        'mobile_no' => '9800000009',
                        'role' => 'admin'
                    ],
                    [
                        'name' => 'Julia Employee',
                        'email' => 'julia.emp@example.com',
                        'mobile_no' => '9800000010',
                        'role' => 'employee'
                    ],
                ];
        
                foreach ($dummyUsers as $dummyUser) {
                    $user = User::create([
                        'username' => $dummyUser['name'],
                        'email' => $dummyUser['email'],
                        'mobile_no' => $dummyUser['mobile_no'],
                        'office_email' => $dummyUser['email'],
                        'office_mobile_no' => $dummyUser['mobile_no'],
                        'password' => Hash::make('password'),
                        'status' => true,
                        'password_changed_at' => now(),
                        'locale' => 'np',
                        'designation_id' => 1, // Replace with actual designation ID
                        'section_id' => 1,
                        'clusters' => json_encode([fake()->numberBetween(1, 4)]),
                        'organization_id' => 1
                    ]);
        
                    if (isset($roles[$dummyUser['role']])) {
                        $user->assignRole($roles[$dummyUser['role']]);
                    }
                }


    }
}