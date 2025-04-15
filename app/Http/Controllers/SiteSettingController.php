<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSiteSettingRequest;
use App\Models\SiteSetting;
use App\Traits\HandlesExceptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class SiteSettingController extends Controller
{
    use HandlesExceptions;
    
    /**
     * Display the site settings page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        abort_if(Gate::denies('view site settings'), 403, 'You do not have access to this page.');
        
        $settings = SiteSetting::firstOrCreate(['id' => 1], ['settings' => []]);
        
        // Set default active tab if not already set
        if (!session()->has('active_settings_tab')) {
            session(['active_settings_tab' => 'navs-company-info']);
        }
        
        return view('pages.site-settings.settings', compact('settings'));
    }
    
    /**
     * Update the site settings
     *
     * @param UpdateSiteSettingRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateSiteSettingRequest $request)
    {
        try {
            $setting = SiteSetting::firstOrFail();
            $validated = $request->validated();
            
            // Group all settings by category
            $settingsGroups = [
                // Application settings
                'application_settings' => [
                    'failed_attempts' => $request->failed_attempts,
                    'force_password_change_days' => $request->force_password_change_days,
                    'session_lifetime' => $request->session_lifetime
                ],
                
                // Contact settings
                'contact_settings' => [
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'google_map' => $request->google_map
                ],
                
                // Social account settings
                'social_account_settings' => [
                    'facebook' => $request->facebook,
                    'youtube' => $request->youtube,
                    'instagram' => $request->instagram,
                    'tiktok' => $request->tiktok,
                    'twitter' => $request->twitter,
                    'linkedin' => $request->linkedin,
                ],
                
                // Privacy settings
                'privacy_settings' => [
                    'privacy_policy' => $request->privacy_policy,
                    'terms_of_use' => $request->terms_of_use,
                    'about_us_content' => $request->about_us_content,
                ],
                
                // Footer settings
                'footer_settings' => [
                    'footer_details' => $request->footer_details,
                    'copyright_text' => $request->copyright_text,
                ],
                
                // Quick links settings
                'quick_links_settings' => [
                    'quick_links' => $request->quick_links ?? [],
                    'quick_links_count' => $request->quick_links_count
                ],
                
                // Video links settings
                'video_links_settings' => [
                    'video_links' => $request->video_links ?? []
                ]
            ];
            
            // Flatten all settings into a single array and merge with existing settings
            $allSettings = [];
            foreach ($settingsGroups as $group) {
                $allSettings = array_merge($allSettings, $group);
            }
            
            $mergedSettings = array_merge(
                $setting->settings,
                $validated,
                $allSettings
            );
            
            // Update settings in database
            $setting->update(['settings' => $mergedSettings]);
            
            // Handle file uploads
            $this->handleMediaUploads($setting, $request);
            
            // Clear cache to ensure latest settings are used
            Cache::forget('site_settings');
            
            // Get active tab for success message
            $active_settings_tab = session('active_settings_tab') ?? 'navs-company-info';
            $tab_slug_title = str_replace('navs-', '', $active_settings_tab);
            $tab_title = ucwords(str_replace('-', ' ', $tab_slug_title));
            
            return redirect()
                ->route('admin.site-settings.index')
                ->with('success', $tab_title.' updated successfully.');
                
        } catch (\Exception $e) {
            return $this->handleExceptions($e, function() {
                return redirect()
                    ->route('admin.site-settings.index')
                    ->with('error', 'An error occurred while updating settings.');
            });
        }
    }
    
    /**
     * Handle all media uploads at once
     *
     * @param SiteSetting $setting
     * @param Request $request
     * @return void
     */
    protected function handleMediaUploads(SiteSetting $setting, Request $request)
    {
        $mediaCollections = [
            'company_logo' => 'company_logo',
            'company_favicon' => 'company_favicon',
            'about_us_image' => 'about_us_image'
        ];
        
        foreach ($mediaCollections as $inputKey => $mediaCollection) {
            $this->handleMediaUpload($setting, $request, $inputKey, $mediaCollection);
        }
    }
    
    /**
     * Handle a single media upload
     *
     * @param SiteSetting $setting
     * @param Request $request
     * @param string $inputKey
     * @param string $mediaCollection
     * @return void
     */
    protected function handleMediaUpload(SiteSetting $setting, Request $request, $inputKey, $mediaCollection)
    {
        if ($request->has($inputKey)) {
            $setting->clearMediaCollection($mediaCollection);
            
            foreach ($request->input($inputKey, []) as $file) {
                $filePath = Storage::path('temp/dropzone/'.$file);
                if (file_exists($filePath)) {
                    $setting->addMedia($filePath)->toMediaCollection($mediaCollection);
                }
            }
        }
    }
}