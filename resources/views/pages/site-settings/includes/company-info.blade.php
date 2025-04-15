<div class="row">
    <div class="col-md-6 mb-3">
        <x-forms.input name="company_name" :value="old('company_name', $settings->settings['company_name'] ?? '')"/>
    </div>
    <div class="col-md-6 mb-3">
        <x-forms.input name="company_slogan" :value="old('company_slogan', $settings->settings['company_slogan'] ?? '')" />
    </div>
    <div class="col-md-12 mb-3">
        <x-forms.input-textarea name="meta_key" :value="old('meta_key', $settings->settings['meta_key'] ?? '')"/>
    </div>
    <div class="col-md-12 mb-3">
        <x-forms.input-textarea name="meta_description" :value="old('meta_description', $settings->settings['meta_description'] ?? '')"/>
    </div>
    <div class="col-md-6 mb-3">
        <x-forms.input-dropzone name="company_logo" :maxFileSize="2" mediaName="company_logo" :model="$settings"/>
    </div>
    <div class="col-md-6 mb-3">
        <x-forms.input-dropzone name="company_favicon" :maxFileSize="1" mediaName="company_favicon" :model="$settings"/>
    </div>
</div>

