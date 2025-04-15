<div class="row">
    <div class="col-md-12 mb-3">
        <x-forms.input name="title" :value="old('title', $slider->title ?? '')" required/>
    </div>
    <div class="col-md-4 mb-3">
        <x-forms.input-dropdown name="title_size" :options="$text_size" option-value="value" option-label="label"
            :value="old('text_size', $slider->text_size ?? 'h1')"/>
    </div>
    <div class="col-md-4 mb-3">
        <x-forms.input-dropdown name="title_case" :options="$text_case" option-value="value" option-label="label"
            :value="old('text_case', $slider->text_case ?? 'capitalize')"/>
    </div>
    <div class="col-md-4 mb-3">
        <x-forms.input name="title_color" type="color"  :value="old('text_color', $slider->text_color ?? '#ffffff')"/>
    </div>
    <div class="col-md-4 my-3">
        <x-forms.input-switch name="slider_status" :value="old('slider_status', $slider->slider_status ?? 1)"/>
    </div>
</div>
