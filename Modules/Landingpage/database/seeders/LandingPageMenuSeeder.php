<?php

namespace Modules\Landingpage\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Landingpage\Models\LandingPageMenu;

class LandingPageMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            ['name' => 'home', 'icon' => 'bx-home', 'url' => '/', 'is_active' => true, 'order_id' => 1],
            ['name' => 'aboutus', 'icon' => 'bx-info-circle', 'url' => '/about', 'is_active' => true, 'order_id' => 2],
            ['name' => 'downloads', 'icon' => 'bx-download', 'url' => '/downloads', 'is_active' => true, 'order_id' => 3],
            ['name' => 'success-stories', 'icon' => 'bx-book-reader', 'url' => '/success-stories', 'is_active' => true, 'order_id' => 4],
            ['name' => 'grievances', 'icon' => 'bx-error', 'url' => '/grievances', 'is_active' => true, 'order_id' => 5],
            ['name' => 'faq', 'icon' => 'bx-help-circle', 'url' => '/faq', 'is_active' => true, 'order_id' => 6],
            ['name' => 'contact', 'icon' => 'bx-phone', 'url' => '/contacts', 'is_active' => true, 'order_id' => 7],

            ['name' => 'staff_details', 'icon' => 'bx-badge', 'url' => '/staff-details', 'is_active' => false, 'order_id' => 8],
            ['name' => 'notice_board', 'icon' => 'bx-bell', 'url' => '/notice-board', 'is_active' => false, 'order_id' => 9],
            ['name' => 'subordinate_offices', 'icon' => 'bx-buildings', 'url' => '/subordinate-offices', 'is_active' => false, 'order_id' => 10],
            ['name' => 'gallery', 'icon' => 'bx-photo-album', 'url' => '/gallery', 'is_active' => false, 'order_id' => 11],
            ['name' => 'feedback', 'icon' => 'bx-comment-detail', 'url' => '/feedback', 'is_active' => false, 'order_id' => 12],
            ['name' => 'suggestions', 'icon' => 'bx-bulb', 'url' => '/suggestions', 'is_active' => false, 'order_id' => 13],
            ['name' => 'related_links', 'icon' => 'bx-link', 'url' => '/related-links', 'is_active' => false, 'order_id' => 14],

        ];
    
        // Insert menu items
        foreach ($menus as $menu) {
            LandingPageMenu::create($menu);
        }
    }
}
