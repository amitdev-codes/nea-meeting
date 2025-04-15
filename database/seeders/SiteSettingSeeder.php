<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $userId = $user ? $user->id : null;
        // Structure settings according to your controller
        $settings = [
            'application_settings' => [
                'failed_attempts' => 5,
                'force_password_change_days' => 90,
                'session_lifetime' => 120
            ],
            'contact_settings' => [
                'website' => 'https://www.nea.org.np',
                'email' => 'info@nea.org.np',
                'phone' => '+977-1-4153051, 4153052',
                'fax' => '+977-1-4153061',
                'address' => 'NEA Central Office, Durbarmarg, Kathmandu, Nepal',
                'google_map' => 'https://www.google.com/maps/place/Nepal+Electricity+Authority/@27.712271,85.315382,17z/data=!3m1!4b1!4m6!3m5!1s0x39eb1908434cb1c9:0x44a6a7e7b1c4e1d!8m2!3d27.712271!4d85.317957!16s%2Fg%2F1tg9q7_1?entry=ttu'
            ],
            'social_account_settings' => [
                'facebook' => 'https://www.facebook.com/NepalElectricityAuthority',
                'youtube' => 'https://www.youtube.com/user/neaorg',
                'twitter' => 'https://twitter.com/nea_nepal',
                'linkedin' => 'https://www.linkedin.com/company/nepal-electricity-authority',
                'instagram' => 'https://www.instagram.com/nea_nepal/',
            ],
            'privacy_settings' => [
                'privacy_policy' => 'Default privacy policy content',
                'terms_of_use' => 'Default terms of use content',
                'about_us_content' => 'About us content'
            ],
            'footer_settings' => [
                'footer_details' => 'Default footer details',
                'copyright_text' => '© ' . date('Y') . ' All Rights Reserved'
            ],
            'quick_links_settings' => [
                'related_links' => [
                    [
                        'title' => 'Ministry of Energy, Water Resources and Irrigation', 
                        'url' => 'https://mowr.gov.np/'
                    ],
                    [
                        'title' => 'NEA Customer Portal', 
                        'url' => 'https://customer.nea.org.np/'
                    ],
                    [
                        'title' => 'NEA Outage Reporting', 
                        'url' => 'https://nea.org.np/outage'
                    ],
                    [
                        'title' => 'NEA Tariff Rates', 
                        'url' => 'https://nea.org.np/tariff'
                    ],
                    [
                        'title' => 'NEA Career Opportunities', 
                        'url' => 'https://nea.org.np/career'
                    ],
                    [
                        'title' => 'Independent Power Producers (IPP)', 
                        'url' => 'https://ippnepal.org.np/'
                    ],
                    [
                        'title' => 'Nepal Electricity Regulatory Commission', 
                        'url' => 'https://www.nec.org.np/'
                    ]
                ],
                'quick_links_count' => 7
            ],
            'video_links_settings' => [
                'video_gallery' => [
                    ['title' => 'Intro Video', 'url' => 'https://youtube.com/watch?v=example'],
                ]
            ],
            // Additional settings you had in your original seeder
            'company_name' => 'Food and Nutrition Security Enhancement Project',
            'company_name_nepali' => 'Food and Nutrition Security Enhancement Project',
            'slogan' => 'कृषि तथा पशुपन्छी विकास मन्त्रालय खाद्य तथा पोषण सुरक्षा सुधार आयोजना',
            'meta_key' => '',
            'meta_description' => '',
            'company_logo' => '',
            'company_favicon' => '',
            'nl_heading' => 'Newsletter',
            'nl_sub_text' => 'Subscribe to our newsletter'
        ];

        // Create or update the single settings row
        SiteSetting::updateOrCreate(
            ['id' => 1], // Assuming you want only one settings record
            [
                'settings' => $settings,
                'created_by' => 1, // Assuming user with ID 1 is admin
                'modified_by' => 1
            ]
        );
    }
}