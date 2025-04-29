<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'view-logs', 'create-logs', 'edit-logs', 'delete-logs',
            'view-settings', 'create-settings', 'edit-settings', 'delete-settings',
            'view-users', 'create-users', 'edit-users', 'delete-users',
            'view-roles', 'create-roles', 'edit-roles', 'delete-roles',
            'view-permissions', 'create-permissions', 'edit-permissions', 'delete-permissions',
            'view-designations', 'create-designations', 'edit-designations', 'delete-designations',
            'view-fiscal-years', 'create-fiscal-years', 'edit-fiscal-years', 'delete-fiscal-years',
            'view-calendar', 'create-calendar', 'edit-calendar', 'delete-calendar',
            'view-landingpage', 'create-landingpage', 'edit-landingpage', 'delete-landingpage',
            'view-calendar-year','create-calendar-year','edit-calendar-year','delete-calendar-year',
            'view-calendar-month','create-calendar-month','edit-calendar-month','delete-calendar-month',
            'view-calendar-day','create-calendar-day','edit-calendar-day','delete-calendar-day',
            'view-calendar-data','create-calendar-data','edit-calendar-data','delete-calendar-data',
            'view-calendar-grid','create-calendar-grid','edit-calendar-grid','delete-calendar-grid',
            'import-users','import-roles','import-permissions','import-contacts','import-logs',
            'export-users','export-roles','export-permissions','export-contacts','export-logs',
            'import-designations','import-expenditure-categories','importer-starter-categories',
            'export-designations','export-expenditure-categories','export-starter-categories',
            'view-dashboard','view-lmbis-report','view-group-report',
            
            'view-organizations','create-organizations','edit-organizations','delete-organizations',
            'view-meeting-rooms','create-meeting-rooms','edit-meeting-rooms','delete-meeting-rooms',
            'view-meeting-attendees','create-meeting-attendees','edit-meeting-attendees','delete-meeting-attendees',
            'view-meeting-minutes','create-meeting-minutes','edit-meeting-minutes','delete-meeting-minutes',
            'view-meetings','create-meetings','edit-meetings','delete-meetings',
            'view-sms-configurations','create-sms-configurations','edit-sms-configurations','delete-sms-configurations',
            'view-email-configurations','create-email-configurations','edit-email-configurations','delete-email-configurations',
            'view-google-calendar-settings','create-google-calendar-settings','edit-google-calendar-settings','delete-google-calendar-settings',

      
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
    }
}
