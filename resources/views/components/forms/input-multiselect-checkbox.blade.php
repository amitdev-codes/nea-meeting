<!-- MultiSelect Checkbox Component -->
<div data-multiselect-checkbox-id {{ $attributes->merge(['class' => '']) }}>
    <label for="{{ $attributes['name'] }}" class="form-label">
        {{ __('field.' . $attributes['name']) }}@if ($attributes['required'])
            <span class="text-danger">*</span>
        @endif
    </label>
    
    <div class="dropdown multiselect-dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start d-flex justify-content-between align-items-center" type="button" 
                id="dropdown{{ $attributes['id'] ?? $attributes['name'] }}" 
                data-bs-toggle="dropdown" 
                aria-expanded="false"
                data-placeholder="{{ $attributes['placeholder'] ?? __('Select...') }}">
            <span class="selected-options">{{ $attributes['placeholder'] ?? __('Select...') }}</span>
            <span class="badge bg-primary ms-1 count-badge d-none">0</span>
        </button>
        
        <div class="dropdown-menu w-100 p-2" aria-labelledby="dropdown{{ $attributes['id'] ?? $attributes['name'] }}">
            <div class="mb-2">
                <div class="form-check">
                    <input class="form-check-input select-all-checkbox" type="checkbox" id="selectAll{{ $attributes['id'] ?? $attributes['name'] }}">
                    <label class="form-check-label" for="selectAll{{ $attributes['id'] ?? $attributes['name'] }}">
                        {{ __('Select All') }}
                    </label>
                </div>
            </div>
            
            <div class="dropdown-divider"></div>
            
            <div class="options-container" style="max-height: 200px; overflow-y: auto;">
                @foreach ($attributes['options'] as $option)
                <div class="form-check">
                    <input class="form-check-input option-checkbox" 
                           type="checkbox"
                           id="option{{ $attributes['id'] ?? $attributes['name'] }}_{{ $option[0] }}"
                           name="{{ $attributes['name'] }}[]" 
                           value="{{ $option[0] }}"
                           {{ in_array($option[0], (array)($attributes['value'] ?? [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="option{{ $attributes['id'] ?? $attributes['name'] }}_{{ $option[0] }}">
                        {{ is_scalar($option[1]) ? $option[1] : (is_array($option[1]) ? implode(', ', $option[1]) : json_encode($option[1])) }}
                    </label>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <span class="error invalid-feedback d-block">{{ $errors->first($attributes['name']) }}</span>
</div>
<!-- JavaScript for the multiselect component -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const multiselectContainers = document.querySelectorAll('[data-multiselect-checkbox-id]');
    
    multiselectContainers.forEach(container => {
        const dropdownButton = container.querySelector('.dropdown-toggle');
        const selectedOptionsText = container.querySelector('.selected-options');
        const countBadge = container.querySelector('.count-badge');
        const checkboxes = container.querySelectorAll('.option-checkbox');
        const selectAllCheckbox = container.querySelector('.select-all-checkbox');
        const optionLabels = {};
        
        // Store all option labels for reference
        checkboxes.forEach(checkbox => {
            const label = container.querySelector(`label[for="${checkbox.id}"]`);
            console.log('Checkbox ID:', checkbox.id, 'Label:', label);
            let labelText = checkbox.value || 'Option';
            if (label && label.textContent) {
                console.log('Label textContent:', label.textContent, 'Type:', typeof label.textContent);
                if (typeof label.textContent === 'string') {
                    labelText = label.textContent.trim();
                } else if (Array.isArray(label.textContent)) {
                    labelText = label.textContent.join(', ');
                } else {
                    console.warn('Unexpected label content:', label.textContent, 'Type:', typeof label.textContent);
                    labelText = String(label.textContent); // Force to string
                }
            } else {
                console.warn('Label not found for checkbox:', checkbox);
            }
            optionLabels[checkbox.value] = labelText;
        });
        
        // Function to update the button text
        function updateButtonText() {
            const checkedOptions = Array.from(checkboxes).filter(cb => cb.checked);
            const checkedCount = checkedOptions.length;
            
            if (checkedCount === 0) {
                selectedOptionsText.textContent = dropdownButton.getAttribute('data-placeholder') || 'Select...';
                countBadge.classList.add('d-none');
            } else if (checkedCount <= 2) {
                const selectedLabels = checkedOptions
                    .map(cb => optionLabels[cb.value] || cb.value)
                    .join(', ');
                selectedOptionsText.textContent = selectedLabels;
                countBadge.classList.add('d-none');
            } else {
                selectedOptionsText.textContent = 'Multiple selected';
                countBadge.textContent = checkedCount;
                countBadge.classList.remove('d-none');
            }
            
            if (checkboxes.length > 0) {
                if (checkedCount === checkboxes.length) {
                    selectAllCheckbox.checked = true;
                    selectAllCheckbox.indeterminate = false;
                } else if (checkedCount === 0) {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = false;
                } else {
                    selectAllCheckbox.indeterminate = true;
                }
            }
        }
        
        updateButtonText();
        
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateButtonText);
        });
        
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                const isChecked = this.checked;
                checkboxes.forEach(checkbox => {
                    checkbox.checked = isChecked;
                });
                updateButtonText();
            });
        }
        
        const dropdownMenu = container.querySelector('.dropdown-menu');
        if (dropdownMenu) {
            dropdownMenu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
    });
});
</script>

<!-- Optional CSS for styling -->
<style>
.multiselect-dropdown .dropdown-menu {
    padding: 10px;
}

.multiselect-dropdown .options-container {
    max-height: 200px;
    overflow-y: auto;
}

.multiselect-dropdown .form-check {
    padding-left: 30px;
    margin-bottom: 5px;
}

.multiselect-dropdown .dropdown-divider {
    margin: 5px 0;
}

.multiselect-dropdown .count-badge {
    font-size: 0.75rem;
    padding: 0.25em 0.6em;
}
</style>