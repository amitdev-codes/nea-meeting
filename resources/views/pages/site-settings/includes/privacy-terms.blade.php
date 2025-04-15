<div class="row">
    <div class="col-md-12 mb-3">
        <x-forms.input-richtexteditor name="privacy_policy">
            {!! old('privacy_policy', $settings->settings['privacy_policy'] ?? '') !!}
        </x-forms.input-richtexteditor>
    </div>
    <div class="col-md-12 mb-3">
        <x-forms.input-richtexteditor name="terms_of_use">
            {!! old('terms_of_use', $settings->settings['terms_of_use'] ?? '') !!}
        </x-forms.input-richtexteditor>
    </div>
</div>

