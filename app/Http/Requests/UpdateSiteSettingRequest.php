<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateSiteSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('edit site settings');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $activeTab = $this->input('active_settings_tab');
        session(['active_settings_tab' => $activeTab]);

        $this->mergeIfMissing(['video_links' => []]);

        $this->mergeIfMissing(['quick_links' => []]);

        // dd($this->all());
        return match ($activeTab) {
            'navs-company-info' => [
                'company_name' => 'required|string|max:255',
                'company_slogan' => 'nullable|string|max:255',
                'meta_key' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:255',
            ],
            'navs-contact-info' => [
                'email' => 'required|email',
                'phone' => 'required',
                'phone.*' => 'required|regex:/^[0-9\-]+$/|min:8|max:13',
                'address' => 'nullable|string|max:255',
                'google_map' => 'nullable|string|max:1000',
            ],
            'navs-social-links' => [
                'facebook' => 'required|url',
                'youtube' => 'nullable|url',
                'instagram' => 'required|url',
                'tiktok' => 'nullable|url',
                'twitter' => 'required|url',
                'linkedin' => 'nullable|url',
            ],
            'navs-privacy-terms' => [
                'privacy_policy' => 'required|min:50',
                'terms_of_use' => 'required|min:50',
            ],
            'navs-about-us' => [
                'about_us_content' => 'required|min:50',
            ],
            'navs-footer-details' => [
                'footer_details' => 'nullable',
                'copyright_text' => 'nullable|string|max:255',
                'quick_links' => 'nullable|array',
                'quick_links.*.title' => 'required|string|max:255',
                'quick_links.*.url' => 'required|url',
                'quick_links_count' => 'nullable|integer|min:1',
            ],
            'navs-video-links' => [
                'video_links' => 'nullable',
                'video_links.*.platform' => 'required',
                'video_links.*.videoId' => 'required',
                'video_links.*.linkType' => 'required',
            ],
            'navs-application-settings' => [
                'force_password_change_days' => 'required|integer|min:1',
                'failed_attempts' => 'required|integer|min:1',
            ],
            'navs-app-env' => [
                'email_env' => 'required|in:test,live',
                'test_email' => 'required|email',
                'live_email' => 'required|email',
            ],
            default => [],
        };
    }

    public function attributes()
    {
        return [
            'video_links.*.platform' => 'Platform field',
            'video_links.*.videoId' => 'Video ID field',
            'video_links.*.linkType' => 'Link type field',
            'quick_links.*.title' => 'Link title field',
            'quick_links.*.url' => 'Link URL field',
            'email_env' => 'Email',
        ];
    }
}
