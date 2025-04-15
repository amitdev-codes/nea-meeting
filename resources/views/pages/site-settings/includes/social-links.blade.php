<div class="row">
    <div class="col-md-6 mb-3">
        <x-forms.input name="facebook" type="url" :value="old('facebook', $settings->settings['facebook'] ?? '')"/>
    </div>
    <div class="col-md-6 mb-3">
        <x-forms.input name="youtube" type="url" :value="old('youtube', $settings->settings['youtube'] ?? '')"/>
    </div>
    <div class="col-md-6 mb-3">
        <x-forms.input name="instagram" type="url" :value="old('instagram', $settings->settings['instagram'] ?? '')"/>
    </div>
    <div class="col-md-6 mb-3">
        <x-forms.input name="tiktok" type="url" :value="old('tiktok', $settings->settings['tiktok'] ?? '')"/>
    </div>
    <div class="col-md-6 mb-3">
        <x-forms.input name="twitter" type="url" :value="old('twitter', $settings->settings['twitter'] ?? '')"/>
    </div>
    <div class="col-md-6 mb-3">
        <x-forms.input name="linkedin" type="url" :value="old('linkedin', $settings->settings['linkedin'] ?? '')"/>
    </div>
</div>

