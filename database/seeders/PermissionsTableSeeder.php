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
            'view-sliders', 'create-sliders', 'edit-sliders', 'delete-sliders',
            'view-site-settings', 'edit-site-settings',
            'view-contacts', 'create-contacts', 'edit-contacts', 'delete-contacts',
            'view-provinces', 'create-provinces', 'edit-provinces', 'delete-provinces',
            'view-districts', 'create-districts', 'edit-districts', 'delete-districts',
            'view-local-levels', 'create-local-levels', 'edit-local-levels', 'delete-local-levels',
            'view-length-units', 'create-length-units', 'edit-length-units', 'delete-length-units',
            'view-components', 'create-components', 'edit-components', 'delete-components',
            'view-sub-components', 'create-sub-components', 'edit-sub-components', 'delete-sub-components',
            'view-expenditure-categories', 'create-expenditure-categories', 'edit-expenditure-categories', 'delete-expenditure-categories',
            'view-castes', 'create-castes', 'edit-castes', 'delete-castes',
            'view-genders', 'create-genders', 'edit-genders', 'delete-genders',
            'view-fiscal-years', 'create-fiscal-years', 'edit-fiscal-years', 'delete-fiscal-years',
            'view-crops', 'create-crops', 'edit-crops', 'delete-crops',
            'view-crop-varieties', 'create-crop-varieties', 'edit-crop-varieties', 'delete-crop-varieties',
            'view-livestocks', 'create-livestocks', 'edit-livestocks', 'delete-livestocks',
            'view-livestock-breeds', 'create-livestock-breeds', 'edit-livestock-breeds', 'delete-livestock-breeds',
            'view-cumulative-progress', 'create-cumulative-progress', 'edit-cumulative-progress', 'delete-cumulative-progress',
            'view-project-activities', 'create-project-activities', 'edit-project-activities', 'delete-project-activities',
            'view-input-activities', 'create-input-activities', 'edit-input-activities', 'delete-input-activities',
            'view-clusters', 'create-clusters', 'edit-clusters', 'delete-clusters',
            'view-designations', 'create-designations', 'edit-designations', 'delete-designations',
            'view-starter-categories', 'create-starter-categories', 'edit-starter-categories', 'delete-starter-categories',
            'view-lmbis-sections', 'create-lmbis-sections', 'edit-lmbis-sections', 'delete-lmbis-sections',
            'view-lmbis-activities', 'create-lmbis-activities', 'edit-lmbis-activities', 'delete-lmbis-activities',
            'view-forms', 'create-forms', 'edit-forms', 'delete-forms',
            'view-form-entries', 'create-form-entries', 'edit-form-entries', 'delete-form-entries',
            'view-form-verifications', 'create-form-verifications', 'edit-form-verifications', 'delete-form-verifications',



            'view-cluster-types', 'create-cluster-types', 'edit-cluster-types', 'delete-cluster-types',
            'view-groups', 'create-groups', 'edit-groups', 'delete-groups',
            'view-group-types', 'create-group-types', 'edit-group-types', 'delete-group-types',
            'view-group-members', 'create-group-members', 'edit-group-members', 'delete-group-members',
            'view-grievances', 'create-grievances', 'edit-grievances', 'delete-grievances',



            'view-calendar', 'create-calendar', 'edit-calendar', 'delete-calendar',
            'view-landingpage', 'create-landingpage', 'edit-landingpage', 'delete-landingpage',
            'view-calendar-year','create-calendar-year','edit-calendar-year','delete-calendar-year',
            'view-calendar-month','create-calendar-month','edit-calendar-month','delete-calendar-month',
            'view-calendar-day','create-calendar-day','edit-calendar-day','delete-calendar-day',
            'view-calendar-data','create-calendar-data','edit-calendar-data','delete-calendar-data',
            'view-calendar-grid','create-calendar-grid','edit-calendar-grid','delete-calendar-grid',
            'view-sectors','create-sectors','edit-sectors','delete-sectors',
            'view-categories','create-categories','edit-categories','delete-categories',

            'import-users','import-roles','import-permissions','import-contacts','import-logs',
            'export-users','export-roles','export-permissions','export-contacts','export-logs',
            'import-forms','import-groups','import-groupTypes',
            'export-forms','export-groups','export-groupTypes',
            'import-crops','import-crop-varieties','import-sectors',
            'export-crops','export-crop-varieties','export-sectors',
            'import-cluster-types','import clusters','import castes',
            'export-cluster-types','export clusters','export castes',
            'import-provinces','import-districts','import-local-levels',
            'export-provinces','export-districts','export-local-levels',
            'import-designations','import-expenditure-categories','importer-starter-categories',
            'export-designations','export-expenditure-categories','export-starter-categories',
            'import-lmbis-sections','import-lmbis-activities',
            'export-lmbis-sections','export-lmbis-activities',
            'view-dashboard','view-lmbis-report','view-group-report',

            'view-success-stories','create-success-stories','edit-success-stories','delete-success-stories',
            'view-faqs','create-faqs','edit-faqs','delete-faqs',
            'view-assets','create-assets','edit-assets','delete-assets',
            'view-infrastructures','create-infrastructures','edit-infrastructures','delete-infrastructures',
            'view-organizations','create-organizations','edit-organizations','delete-organizations',
            'view-grievances-nature','create-grievances-nature','edit-grievances-nature','delete-grievances-nature',
            'view-communication-mediums','create-communication-mediums','edit-communication-mediums','delete-communication-mediums',
            'view-grievances-registration','create-grievances-registration','edit-grievances-registration','delete-grievances-registration',
      
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
