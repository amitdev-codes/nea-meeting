<div class="row">
    <div class="col-md-12 mb-3">
        <x-forms.input-textarea name="footer_details" :value="old('footer_details', $settings->settings['footer_details'] ?? '')"/>
    </div>
    <div class="col-md-12 mb-3">
        <x-forms.input name="copyright_text" :value="old('copyright_text', $settings->settings['copyright_text'] ?? '')"/>
    </div>

    <div class="col-md-12 mb-3">
        <label for="quick_links" class="form-label">{{ __('field.quick_links') }}</label>
        <div class="table-responsive text-nowrap">
            <table class="table table-sm table-bordered table-responsive">
                <thead class="table-dark">
                    <tr>
                        <th class="py-2">Link title</th>
                        <th class="py-2">URL</th>
                        <th class="py-2 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody id="quickLinksTableBody">
                    @php
                        $oldQuickLinks = old('quick_links', $settings->settings['quick_links'] ?? []);
                        $quickLinksCount = old('quick_links_count', $settings->settings['quick_links_count'] ?? 5);
                    @endphp

                    @if(count($oldQuickLinks) === 0)
                        <tr id="no-links-row">
                            <td colspan="3" class="text-center">
                                <span>Click "Add New Link" to create a quick link.</span>
                            </td>
                        </tr>
                    @else
                        @foreach($oldQuickLinks as $index => $link)
                            <tr class="quick-link-row">
                                <td class="ps-2">
                                    <input type="text" class="form-control mx-0" placeholder="Link Title"
                                        name="quick_links[{{ $index }}][title]"
                                        value="{{ $link['title'] ?? '' }}"
                                        @class(['is-invalid' => $errors->has("quick_links.$index.title")])>
                                    @error("quick_links.$index.title")
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </td>
                                <td class="ps-2">
                                    <input type="url" class="form-control" placeholder="URL"
                                        name="quick_links[{{ $index }}][url]"
                                        value="{{ $link['url'] ?? '' }}"
                                        @class(['is-invalid' => $errors->has("quick_links.$index.url")])>
                                    @error("quick_links.$index.url")
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </td>
                                <td class="text-end col-1">
                                    <button type="button" class="btn btn-icon btn-outline-danger remove-link-btn">
                                        <i class='bx bx-trash-alt'></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
                <tfoot class="table-border-bottom-0">
                    <tr>
                        <td class="align-middle ps-2" colspan="3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Links Count</span>
                                        <input type="number" id="quickLinksCount" name="quick_links_count" class="form-control-sm" 
                                            min="1" step="1" max="10" value="{{ $quickLinksCount }}">
                                    </div>
                                </div>

                                <a href="javascript:void(0);" id="addQuickLinkBtn" class="link">
                                    <i class="bx bx-plus-circle me-1"></i>Add New Link
                                </a>

                                <span class="small col-2 text-end">Row Limit:
                                    <strong id="currentLinkCount">{{ count($oldQuickLinks) }}</strong> / 
                                    <strong id="maxLinkCount">{{ $quickLinksCount }}</strong>
                                </span>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize variables
    const quickLinksTableBody = document.getElementById('quickLinksTableBody');
    const quickLinksCountInput = document.getElementById('quickLinksCount');
    const addQuickLinkBtn = document.getElementById('addQuickLinkBtn');
    const currentLinkCountEl = document.getElementById('currentLinkCount');
    const maxLinkCountEl = document.getElementById('maxLinkCount');
    const noLinksRow = document.getElementById('no-links-row');
    
    // URL validation pattern
    const urlPattern = new RegExp(
        '[Hh][Tt][Tt][Pp][Ss]?:\\/\\/(?:(?:[a-zA-Z\\u00a1-\\uffff0-9]+-?)*[a-zA-Z\\u00a1-\\uffff0-9]+)(?:\\.(?:[a-zA-Z\\u00a1-\\uffff0-9]+-?)*[a-zA-Z\\u00a1-\\uffff0-9]+)*(?:\\.(?:[a-zA-Z\\u00a1-\\uffff]{2,}))(?:\\:\\d{2,5})?(?:\\/[^\\s]*)?'
    );
    
    // Function to get current links count
    function getCurrentLinksCount() {
        return document.querySelectorAll('.quick-link-row').length;
    }
    
    // Function to update links count display
    function updateLinksCountDisplay() {
        const currentCount = getCurrentLinksCount();
        const maxCount = parseInt(quickLinksCountInput.value);
        
        currentLinkCountEl.textContent = currentCount;
        maxLinkCountEl.textContent = maxCount;
        
        // Enable/disable add button
        if (currentCount >= maxCount) {
            addQuickLinkBtn.classList.add('disabled');
        } else {
            addQuickLinkBtn.classList.remove('disabled');
        }
        
        // Show/hide "no links" row
        if (currentCount === 0 && noLinksRow === null) {
            const newRow = document.createElement('tr');
            newRow.id = 'no-links-row';
            newRow.innerHTML = `
                <td colspan="3" class="text-center">
                    <span>Click "Add New Link" to create a quick link.</span>
                </td>
            `;
            quickLinksTableBody.appendChild(newRow);
        } else if (currentCount > 0 && noLinksRow !== null) {
            noLinksRow.remove();
        }
    }
    
    // Function to validate a single link row
    function validateLinkRow(row) {
        let isValid = true;
        const titleInput = row.querySelector('input[placeholder="Link Title"]');
        const urlInput = row.querySelector('input[placeholder="URL"]');
        
        // Clear previous error messages
        titleInput.classList.remove('is-invalid');
        urlInput.classList.remove('is-invalid');
        row.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
        
        // Validate title
        if (!titleInput.value.trim()) {
            isValid = false;
            titleInput.classList.add('is-invalid');
            
            const errorSpan = document.createElement('span');
            errorSpan.className = 'error invalid-feedback';
            errorSpan.textContent = 'Please enter a link title';
            titleInput.parentNode.appendChild(errorSpan);
        }
        
        // Validate URL
        if (!urlInput.value.trim() || !urlPattern.test(urlInput.value)) {
            isValid = false;
            urlInput.classList.add('is-invalid');
            
            const errorSpan = document.createElement('span');
            errorSpan.className = 'error invalid-feedback';
            errorSpan.textContent = 'Please enter a valid URL';
            urlInput.parentNode.appendChild(errorSpan);
        }
        
        return isValid;
    }
    
    // Function to validate all existing links
    function validateExistingLinks() {
        const rows = document.querySelectorAll('.quick-link-row');
        if (rows.length === 0) return true;
        
        // Only validate the last row if it exists
        const lastRow = rows[rows.length - 1];
        return validateLinkRow(lastRow);
    }
    
    // Function to add new link row
    function addNewLinkRow() {
        // First validate existing links
        if (!validateExistingLinks()) {
            return;
        }
        
        const currentCount = getCurrentLinksCount();
        const maxCount = parseInt(quickLinksCountInput.value);
        
        if (currentCount >= maxCount) {
            return;
        }
        
        // If there's a "no links" row, remove it
        if (noLinksRow) {
            noLinksRow.remove();
        }
        
        // Create new row
        const newRow = document.createElement('tr');
        newRow.className = 'quick-link-row';
        
        const index = currentCount;
        newRow.innerHTML = `
            <td class="ps-2">
                <input type="text" class="form-control mx-0" placeholder="Link Title"
                    name="quick_links[${index}][title]" value="">
            </td>
            <td class="ps-2">
                <input type="url" class="form-control" placeholder="URL"
                    name="quick_links[${index}][url]" value="">
            </td>
            <td class="text-end col-1">
                <button type="button" class="btn btn-icon btn-outline-danger remove-link-btn">
                    <i class='bx bx-trash-alt'></i>
                </button>
            </td>
        `;
        
        quickLinksTableBody.appendChild(newRow);
        
        // Add event listener to the new remove button
        newRow.querySelector('.remove-link-btn').addEventListener('click', function() {
            newRow.remove();
            updateLinksCountDisplay();
            reindexRows();
        });
        
        updateLinksCountDisplay();
    }
    
    // Function to reindex rows after removal
    function reindexRows() {
        const rows = document.querySelectorAll('.quick-link-row');
        rows.forEach((row, index) => {
            row.querySelector('input[placeholder="Link Title"]').name = `quick_links[${index}][title]`;
            row.querySelector('input[placeholder="URL"]').name = `quick_links[${index}][url]`;
        });
    }
    
    // Add event listener to the "Add New Link" button
    addQuickLinkBtn.addEventListener('click', addNewLinkRow);
    
    // Add event listeners to existing remove buttons
    document.querySelectorAll('.remove-link-btn').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('tr').remove();
            updateLinksCountDisplay();
            reindexRows();
        });
    });
    
    // Add event listener to the links count input
    quickLinksCountInput.addEventListener('change', function() {
        updateLinksCountDisplay();
    });
    
    // Initial update
    updateLinksCountDisplay();
});
</script>
@endpush