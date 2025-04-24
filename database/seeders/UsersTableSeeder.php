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
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $roleNames = ['superadmin' => 'superadmin','admin' => 'admin','guest' => 'guest','user' => 'user','md'=>'md'];
        
        $roles = [];
        foreach ($roleNames as $name => $code) {
            $roles[$name] = Role::firstOrCreate(['name' => $name,'code' => $code,'guard_name' => 'web']);
        }

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
                'view-meetings', 'create-meetings', 'edit-meetings', 'delete-meetings',
                'view-meeting-minutes', 'create-meeting-minutes', 'edit-meeting-minutes', 'delete-meeting-minutes',
                'view-meeting-rooms', 'create-meeting-rooms', 'edit-meeting-rooms', 'delete-meeting-rooms',
                'view-roles', 'edit-roles',
                'view-permissions',
                'view-logs',
                "view-dashboard"
            ];
            $roles['admin']->syncPermissions($adminPermissions);
        }
        // User permissions
        if (isset($roles['user'])) {
            $userPermissions = ['view-meetings','view-meeting-minutes','view-dashboard'];
            $roles['user']->syncPermissions($userPermissions);
        }
        if (isset($roles['guest'])) {
            $userPermissions = ['view-meetings','view-meeting-minutes','view-dashboard'];
            $roles['guest']->syncPermissions($userPermissions);
        }
        if (isset($roles['md'])) {
            $userPermissions = ['view-dashboard'];
            $roles['md']->syncPermissions($userPermissions);
        }



        // Seed Admin Users
        $adminUsers = [
            [
                'name' => 'distribution',
                'email' => 'superadmin@dryice.com',
                'mobile_no' => '9876543210',
                'role' => 'superadmin',
                'organization_id'=>3,
            ],
            [
                'name' => 'user',
                'email' => 'admin@nea.com',
                'mobile_no' => '9876543211',
                'role' => 'admin',
                'organization_id'=>7,
            ],
            [
                'name' => 'engineering',
                'email' => 'ramila@nea.org.np',
                'mobile_no' => '9851219678',
                'role' => 'admin',
                'organization_id'=>1,
            ],
            [
                'name' => 'guest',
                'email' => 'guest@admin.com',
                'mobile_no' => '9876543212',
                'role' => 'guest',
                'organization_id'=>7,
            ],
            [
                'name' => 'administration',
                'email' => 'user@admin.com',
                'mobile_no' => '9886543212',
                'role' => 'user',
                'organization_id'=>3,
            ],
            [
                'name' => 'itd',
                'email' => 'md@neamms.com',
                'mobile_no' => '9856543212',
                'role' => 'md',
                'organization_id'=>3,
            ],
            [
                'name' => 'mdUser',
                'email' => 'neamd@nea.org.np',
                'mobile_no' => '0014153007',
                'role' => 'md',
                'organization_id'=>1,
            ],
            [
                'name' => 'bdd',
                'email' => 'transmission@nea.org.np',
                'mobile_no' => '0014153077',
                'role' => 'user',
                'organization_id'=>8,
            ],
            [
                'name' => 'finance',
                'email' => 'pmit@nea.org.np',
                'mobile_no' => '0014153066',
                'role' => 'user',
                'organization_id'=>3,
            ],
            [
                'name' => 'generation',
                'email' => 'pmd@nea.org.np',
                'mobile_no' => '0014164099',
                'role' => 'user',
                'organization_id'=>11,
            ],
            [
                'name' => 'md',
                'email' => 'itd@nea.org.np',
                'mobile_no' => '0014153012',
                'role' => 'user',
                'organization_id'=>3,
            ],
            [
                'name' => 'neamd',
                'email' => 'generation@nea.org.np',
                'mobile_no' => '0014153016',
                'role' => 'user',
                'organization_id'=>7,
            ],
            [
                'name' => 'pmd',
                'email' => 'finance@nea.org.np',
                'mobile_no' => '0014153116',
                'role' => 'user',
                'organization_id'=>6,
            ],
            [
                'name' => 'pmit',
                'email' => 'engineering@nea.org.np',
                'mobile_no' => '0014153027',
                'role' => 'user',
                'organization_id'=>10,
            ],
            [
                'name' => 'ramila',
                'email' => 'dcs@nea.org.np',
                'mobile_no' => '0014153145',
                'role' => 'user',
                'organization_id'=>9,
            ],
            [
                'name' => 'Super Admin',
                'email' => 'bdd@nea.org.np',
                'mobile_no' => '0014153206',
                'role' => 'user',
                'organization_id'=>4,
            ],
            [
                'name' => 'transmission',
                'email' => 'administration@nea.org.np',
                'mobile_no' => '0014153010',
                'role' => 'user',
                'organization_id'=>5,
            ],
        ];

        foreach ($adminUsers as $adminUser) {
            $user = User::create([
                'username' => $adminUser['name'],
                'email' => $adminUser['email'],
                'mobile_no' => $adminUser['mobile_no'],
                'office_email' => $adminUser['email'],
                'office_mobile_no' => $adminUser['mobile_no'],
                'organization_id' => $adminUser['organization_id'],
                'password' => Hash::make('password'),
                'status' => true,
                'password_changed_at' => now(),
                'locale' => 'np'
            ]);

            if (isset($roles[$adminUser['role']])) {
                $user->assignRole($roles[$adminUser['role']]);
            }
        }
    }
}