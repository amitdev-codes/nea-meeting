<div class="contact-person-group row mb-3">
    <div class="mb-3 col-md-3">
        <x-forms.input-label name="contact_persons[{{ $index }}][name]" label="{{ __('contact_person_name') }}"
            :value="$contact['name'] ?? ''" placeholder="{{ __('Enter Name') }}" required />
    </div>
    <div class="mb-3 col-md-3">
        <x-forms.input-label-select2 name="contact_persons[{{ $index }}][position]" label="{{ __('position') }}"
            :options="$designations
                ->map(function ($designation) {
                    return [$designation->id, $designation->name . ' (' . $designation->name_np . ')'];
                })
                ->toArray()" :value="$contact['position'] ?? ''" placeholder="{{ __('Select Designation') }}" required />
    </div>
    <div class="mb-3 col-md-4">
        <x-forms.input-label-phone name="contact_persons[{{ $index }}][mobile_number]"
            label="{{ __('mobile_no') }}" :value="$contact['mobile_number'] ?? ''" required />
    </div>
    <div class="col-md-2 mb-8 d-flex align-items-end">
        <button type="button" class="btn btn-danger remove-contact-person">×</button>
    </div>
</div>
@push('scripts')
    <script type="module">
        // Dynamic Contact Person Fields
        document.getElementById('add-contact-person').addEventListener('click', function() {
            const container = document.getElementById('contact-persons-container');
            const index = container.children.length;
            fetch(`/groups/get-contact-person-fields?index=${index}`)
                .then(response => response.text())
                .then(html => {
                    container.insertAdjacentHTML('beforeend', html);
                    $(container.lastElementChild).find('select')
                        .select2(); // Initialize Select2 for new dropdown
                });
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-contact-person')) {
                e.target.closest('.contact-person-group').remove();
            }
        });

    </script>
@endpush

<style>
    .contact-person-group {
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 5px;
        background-color: #f9f9f9;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 15px;
    }

    .remove-contact-person {
        width: 100%;
        font-size: 1.2rem;
    }
</style>
