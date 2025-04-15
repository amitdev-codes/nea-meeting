<?php

namespace Database\Seeders;

use App\Models\Resource;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ResourceSeeder extends Seeder
{
    public function run(): void
    {
        $resources = [
            // Resource-based main
            ['name' => 'users', 'icon' => 'bx-user', 'type' => 'resource'],
            ['name' => 'roles', 'icon' => 'bx-shield', 'type' => 'resource'],
            ['name' => 'settings', 'icon' => 'bx-cog', 'type' => 'resource'],
            ['name' => 'contacts', 'icon' => 'bx-phone', 'type' => 'resource'],
            ['name' => 'logs', 'icon' => 'bx-file', 'type' => 'resource'],
            ['name' => 'permissions', 'icon' => 'bx-lock', 'type' => 'resource'],
            // Master module
            ['name' => 'landingpage', 'icon' => 'bx-home', 'type' => 'resource'],
            // Route-based (Imports)
            // URL-based
            ['name' => 'Dashboard', 'icon' => 'bx-home', 'type' => 'url', 'url' => '/dashboard'],
            ['name' => 'SiteSettings', 'icon' => 'bx-home', 'type' => 'url', 'url' => '/site-settings'],
            ['name' => 'groupReport', 'icon' => 'bx-home', 'type' => 'url', 'url' => '/group-reports'],
            ['name' => 'lmbisReport', 'icon' => 'bx-home', 'type' => 'url', 'url' => '/lmbis-reports'],
            //meetings
            ['name' => 'meetings', 'icon' => 'bx-lock', 'type' => 'resource'],

            ['name' => 'faqs', 'icon' => 'bx bx-question-mark', 'type' => 'resource'],
        ];

        foreach ($resources as $resourceData) {
            Resource::updateOrCreate(
                ['name' => $resourceData['name']],
                [
                    'icon' => $resourceData['icon'],
                    'type' => $resourceData['type'],
                    'route_name' => $resourceData['route_name'] ?? null,
                    'url' => $resourceData['url'] ?? null,
                    'is_menu' => in_array($resourceData['name'], ['Dashboard', 'Users', 'Settings Page'])
                ]
            );
        }
    }
}