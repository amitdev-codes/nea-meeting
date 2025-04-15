<div class="row">
    <div class="col-md-6 mb-3">
        <x-forms.input name="email" type="email" :value="old('email', $settings->settings['email'] ?? '')"/>
    </div>
    <div class="col-md-6 mb-3">
        <x-forms.input name="phone" :value="old('phone', $settings->settings['phone'] ?? '')"/>
    </div>
    {{-- <div class="col-md-6 mb-3">
        <x-forms.input-select2-tags name="phone"
            :options="$settings->settings['phone'] ?? []"
            :value="$settings->settings['phone'] ?? ''"
            multiple="true"
        />
    </div> --}}
    <div class="col-md-12 mb-3">
        <x-forms.input-textarea name="address" :value="old('address', $settings->settings['address'] ?? '')"/>
    </div>
    <div class="col-md-12 mb-3">
        <x-forms.input-textarea name="google_map" :value="old('google_map', $settings->settings['google_map'] ?? '')" />
    </div>
</div>

@push('script')
    <script type="module">
        $('#phone').select2({
            tags: true,
            tokenSeparators: [',', ' ', ';'],
            placeholder: "Add phone numbers",
        });
    </script>
@endpush
