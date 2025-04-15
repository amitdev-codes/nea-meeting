<div class="row">
    <div class="col-md-12 mb-3">
        <x-forms.input name="subtitle" :value="old('subtitle', $slider->subtitle ?? '')" />
    </div>
    <div class="col-md-4 mb-3">
        <x-forms.input-dropdown name="subtitle_size" :options="$text_size" option-value="value" option-label="label"
            :value="old('subtitle_size', $slider->subtitle_size ?? '')" />
    </div>
    <div class="col-md-4 mb-3">
        <x-forms.input-dropdown name="subtitle_case" :options="$text_case" option-value="value" option-label="label"
            :value="old('subtitle_case', $slider->subtitle_case ?? '')"/>
    </div>
    <div class="col-md-4 mb-3">
        <x-forms.input name="subtitle_color" type="color" :value="old('subtitle_color', $slider->subtitle_color ?? '')"/>
    </div>
</div>
