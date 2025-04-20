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
                'name' => 'Super Admin',
                'email' => 'superadmin@dryice.com',
                'mobile_no' => '9876543210',
                'role' => 'superadmin',
            ],
            [
                'name' => 'Admin User',
                'email' => 'admin@nea.com',
                'mobile_no' => '9876543211',
                'role' => 'admin',
            ],
            [
                'name' => 'Guest User',
                'email' => 'guest@admin.com',
                'mobile_no' => '9876543212',
                'role' => 'guest',
            ],
            [
                'name' => 'user',
                'email' => 'user@admin.com',
                'mobile_no' => '9886543212',
                'role' => 'user',
            ],
            [
                'name' => 'md',
                'email' => 'md@neamms.com',
                'mobile_no' => '9856543212',
                'role' => 'md',
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
                'designation_id' =>1,
                'organization_id'=>fake()->numberBetween(1, 9)
            ]);

            if (isset($roles[$adminUser['role']])) {
                $user->assignRole($roles[$adminUser['role']]);
            }
        }
    }
}