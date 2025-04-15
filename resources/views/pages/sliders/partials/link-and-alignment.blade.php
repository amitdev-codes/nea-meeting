<div class="row">
    <div class="col-md-12 mb-3">
        <x-forms.input name="url_text" :value="old('url_text', $slider->url_text ?? '')"/>
    </div>

    <div class="col-md-12 mb-3">
        <x-forms.input name="url" type="url" :value="old('url', $slider->url ?? '')"/>
    </div>

    <div class="col-md-12 mb-3">
        <x-forms.input-dropdown name="link_type" :options="$link_type" option-value="value" option-label="label"
            :value="old('link_type', $slider->link_type ?? '')"/>
    </div>

    <div class="col-md-12 mb-3">
        <x-forms.input-dropdown name="content_alignment" :options="$content_align" option-value="value" option-label="label"
            :value="old('content_alignment', $slider->content_alignment ?? 'left')"/>
    </div>

    <div class="col-md-12">
        <x-forms.input-dropzone name="image" :model="$slider ?? null"/>
    </div>
</div>
