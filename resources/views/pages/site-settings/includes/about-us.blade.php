<div class="row">
    <div class="col-md-12 mb-3">
        <x-forms.input-richtexteditor name="about_us_content">
            {!! old('about_us_content', $settings->settings['about_us_content'] ?? '') !!}
        </x-forms.input-richtexteditor>
    </div>
    <div class="col-md-12 mb-3">
        <x-forms.input-dropzone name="about_us_image" :maxFileSize="5" mediaName="about_us_image" :model="$settings"/>
    </div>
</div>
