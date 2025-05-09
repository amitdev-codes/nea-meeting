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
        $roleNames = ['superadmin' => 'superadmin','centraladmin' => 'centraladmin','admin' => 'admin','guest' => 'guest','user' => 'user','md'=>'md'];
        
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
                "view-dashboard",
                "view-calendar",
            ];
            $roles['admin']->syncPermissions($adminPermissions);
        }
        if (isset($roles['centraladmin'])) {
            $centraladminPermissions = [
                'view-users', 'create-users', 'edit-users', 'delete-users',
                'view-meetings', 'create-meetings', 'edit-meetings', 'delete-meetings',
                'view-meeting-minutes', 'create-meeting-minutes', 'edit-meeting-minutes', 'delete-meeting-minutes',
                'view-meeting-rooms', 'create-meeting-rooms', 'edit-meeting-rooms', 'delete-meeting-rooms',
                'view-roles', 'edit-roles','create-roles',
                'view-permissions','create-permissions','edit-permissions','delete-permissions',
                'view-organizations','create-organizations','edit-organizations','delete-organizations',
                'view-logs',
                "view-dashboard",
                "view-calendar",
            ];
            $roles['centraladmin']->syncPermissions($centraladminPermissions);
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
                'name' => 'superadmin',
                'email' => 'superadmin@nea.com',
                'phone' => '01253645785',
                'mobile_no' => '9800000010',
                'password'=>Hash::make('superadmin@#nea2025'),
                'role' => 'superadmin',
                'organization_id'=>3,
            ],
            [
                'name' => 'md',
                'organization_id' => '1',
                'password'=>Hash::make('md@#Nea2026'),
                'role' => 'md',
                'phone' => '0014153007',
                'mobile_no' => '9825361471',
                'email' => 'neamd1@nea.org.np',
            ],
            [
                'name' => 'ramila',
                'organization_id' => '1',
                'role' => 'admin',
                'phone' => '0014153145',
                'mobile_no' => '9825361478',
                'email' => 'ramila1@nea.org.np',
            ],
            [
                'name' => 'itd',
                'organization_id' => '12',
                'role' => 'centraladmin',
                'phone' => '0014153012',
                'mobile_no' => '9825361472',
                'email' => 'itd@nea.org.np',
            ],
            [
                'name' => 'engineering',
                'organization_id' => '10',
                'role' => 'user',
                'phone' => '0014153027',
                'mobile_no' => '9825361473',
                'email' => 'engineering1@nea.org.np',
            ],
            [
                'name' => 'finance',
                'organization_id' => '6',
                'role' => 'user',
                'phone' => '0014153116',
                'mobile_no' => '9825361474',
                'email' => 'finance1@nea.org.np',
            ],
            [
                'name' => 'pmit',
                'organization_id' => '3',
                'role' => 'user',
                'phone' => '0014153066',
                'mobile_no' => '9825361475',
                'email' => 'pmit1@nea.org.np',
            ],
            [
                'name' => 'transmission',
                'organization_id' => '8',
                'role' => 'user',
                'phone' => '0014153077',
                'mobile_no' => '9825361476',
                'email' => 'transmission1@nea.org.np',
            ],
            [
                'name' => 'administration',
                'organization_id' => '5',
                'role' => 'user',
                'phone' => '9886543212',
                'mobile_no' => '9886543212',
                'email' => 'user1@admin.com',
            ],
            [
                'name' => 'pmd',
                'organization_id' => '11',
                'role' => 'user',
                'phone' => '14153067',
                'mobile_no' => '982536333',
                'email' => 'pmd1@nea.org.np',
            ],
            [
                'name' => 'generation',
                'organization_id' => '7',
                'role' => 'user',
                'phone' => '14153068',
                'mobile_no' => '9825361479',
                'email' => 'generation1@nea.org.np',
            ],
            [
                'name' => 'dcs',
                'organization_id' => '9',
                'role' => 'user',
                'phone' => '14153064',
                'mobile_no' => '9825361485',
                'email' => 'dcs1@nea.org.np',
            ],
            [
                'name' => 'bdd',
                'organization_id' => '4',
                'role' => 'user',
                'phone' => '0014153206',
                'mobile_no' => '9825361486',
                'email' => 'bdd1@nea.org.np',
            ],
        ];

        foreach ($adminUsers as $adminUser) {
            $user = User::create([
                'username' => $adminUser['name'],
                'email' => $adminUser['email'],
                'phone' => $adminUser['phone'],
                'mobile_no' => $adminUser['mobile_no'],
                'office_email' => $adminUser['email'],
                'office_mobile_no' => $adminUser['mobile_no'],
                'organization_id' => $adminUser['organization_id'],
                'password' => $adminUser['password'] ?? Hash::make('password'),
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