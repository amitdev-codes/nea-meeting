/**
 * EditableGrid - A reusable script for Laravel Blade editable grid functionality
 * 
 * This script handles common functionality for editable grid tables including:
 * - Row selection
 * - Inline cell editing
 * - Row operations (add, edit, save, cancel)
 * - Dependent dropdowns
 * - AJAX operations
 */
class EditableGrid {
    constructor(options = {}) {
        // Default options
        this.options = {
            gridSelector: '.editable-grid',
            selectAllSelector: '.select-all',
            addRowSelector: '.add-row',
            rowTemplateId: 'row-template',
            ...options
        };

        // State variables
        this.currentEditedCell = null;
        
        // Initialize
        this.init();
    }

    init() {
        document.addEventListener('DOMContentLoaded', () => {
            this.grid = document.querySelector(this.options.gridSelector);
            if (!this.grid) return;
            
            this.setupEventListeners();
            this.checkEmptyTable();
        });
    }

    setupEventListeners() {
        // Select all checkbox
        const selectAllCheckbox = document.querySelector(this.options.selectAllSelector);
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', () => {
                const isChecked = selectAllCheckbox.checked;
                document.querySelectorAll(`${this.options.gridSelector} .row-checkbox`).forEach(checkbox => {
                    checkbox.checked = isChecked;
                });
                if (typeof updateBulkActionsVisibility === 'function') {
                    updateBulkActionsVisibility();
                }
            });
        }

        // Add new row button
        const addRowBtn = document.querySelector(this.options.addRowSelector);
        if (addRowBtn) {
            addRowBtn.addEventListener('click', () => this.addNewRow());
        }

        // Row action buttons (using event delegation)
        const tbody = document.querySelector(`${this.options.gridSelector} tbody`);
        if (tbody) {
            tbody.addEventListener('click', (e) => {
                const row = e.target.closest('tr');
                if (!row) return;

                if (e.target.closest('.save-btn')) {
                    this.saveRow(row);
                    e.stopPropagation();
                    return;
                }

                if (e.target.closest('.edit-btn')) {
                    this.enableRowEditing(row);
                    e.stopPropagation();
                    return;
                }

                if (e.target.closest('.cancel-btn')) {
                    this.cancelRowEditing(row);
                    e.stopPropagation();
                    return;
                }

                // Cell click for inline editing
                const cell = e.target.closest('td.editable');
                if (cell && !cell.closest('tr').classList.contains('editing')) {
                    // If another cell is being edited, save it first
                    if (this.currentEditedCell && this.currentEditedCell !== cell) {
                        const currentRow = this.currentEditedCell.closest('tr');
                        this.saveCell(currentRow, this.currentEditedCell);
                    }

                    // Start editing the clicked cell
                    this.enableCellEditing(cell);
                    this.currentEditedCell = cell;
                }
            });

            // Handle dependent dropdowns
            tbody.addEventListener('change', (e) => {
                const select = e.target;
                if (select.tagName === 'SELECT') {
                    const fieldName = select.name;
                    const row = select.closest('tr');

                    // Handle dependencies between fields
                    if (fieldName === 'group_id') {
                        // When group changes, update beneficiaries
                        this.updateBeneficiaryOptions(row, select.value);
                    } else if (fieldName === 'crop_id') {
                        // When crop changes, update crop varieties
                        this.updateCropVarietyOptions(row, select.value);
                    }
                }
            });
        }

        // Handle click outside to save current editing cell
        document.addEventListener('click', (e) => {
            if (this.currentEditedCell && !this.currentEditedCell.contains(e.target)) {
                const row = this.currentEditedCell.closest('tr');
                this.saveCell(row, this.currentEditedCell);
                this.currentEditedCell = null;
            }
        });

        // Handle Enter key to save cell
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && this.currentEditedCell) {
                const row = this.currentEditedCell.closest('tr');
                this.saveCell(row, this.currentEditedCell);
                this.currentEditedCell = null;
            }
        });
    }

    checkEmptyTable() {
        const tbody = document.querySelector(`${this.options.gridSelector} tbody`);
        if (!tbody) return;
        
        const visibleRows = tbody.querySelectorAll('tr:not([data-removed="true"])');

        if (visibleRows.length === 0) {
            tbody.innerHTML =
                '<tr><td colspan="10" class="text-center">No records found. Add a new row to start.</td></tr>';
        }
    }

    addNewRow() {
        const template = document.getElementById(this.options.rowTemplateId).innerHTML;
        const tbody = document.querySelector(`${this.options.gridSelector} tbody`);

        // Clear "No records found" message if it exists
        if (tbody.querySelector('td[colspan="10"]')) {
            tbody.innerHTML = '';
        }

        // Replace placeholder id with temporary id
        const tempId = 'new-' + Date.now();
        let newRow = template.replace(/__id__/g, tempId);

        // Replace other placeholders with empty values
        const placeholders = [
            '__group_name__', '__beneficiary_name__', '__seed_source__', '__crop_name__', 
            '__crop_variety_name__', '__length_unit_name__', '__quantity__', '__remarks__'
        ];
        
        placeholders.forEach(placeholder => {
            newRow = newRow.replace(new RegExp(placeholder, 'g'), '');
        });

        // Insert the row
        tbody.insertAdjacentHTML('beforeend', newRow);

        // Get the newly added row and enable editing
        const row = tbody.lastElementChild;
        row.dataset.isNew = 'true';

        // Populate dropdowns
        this.populateDropdowns(row);

        // Focus on the first editable cell
        const firstEditableCell = row.querySelector('.editable');
        if (firstEditableCell) {
            this.enableCellEditing(firstEditableCell);
            this.currentEditedCell = firstEditableCell;
        }
    }

    enableCellEditing(cell) {
        const fieldType = cell.dataset.type;
        const editableField = cell.querySelector('.editable-field');
        const displayValue = cell.querySelector('.display-value');

        // Show the editable field and hide the display value
        editableField.classList.remove('d-none');
        displayValue.classList.add('d-none');

        // Focus the appropriate field
        editableField.focus();
        if (fieldType === 'text' || fieldType === 'number') {
            editableField.select();
        }
    }

    saveCell(row, cell) {
        const fieldType = cell.dataset.type;
        const editableField = cell.querySelector('.editable-field');
        const displayValue = cell.querySelector('.display-value');

        // Update the display value
        if (fieldType === 'select') {
            const selectedOption = editableField.options[editableField.selectedIndex];
            displayValue.textContent = selectedOption ? selectedOption.text : '';
        } else {
            displayValue.textContent = editableField.value;
        }

        // Hide the editable field and show the display value
        editableField.classList.add('d-none');
        displayValue.classList.remove('d-none');

        // If this is a new row, we might want to auto-save it
        if (row.dataset.isNew === 'true') {
            this.saveRow(row);
        }
    }

    enableRowEditing(row) {
        // Mark the row as being edited
        row.classList.add('editing');

        // Show/hide appropriate buttons
        row.querySelector('.edit-btn').classList.add('d-none');
        row.querySelector('.save-btn').classList.remove('d-none');
        row.querySelector('.cancel-btn').classList.remove('d-none');

        // Enable all form fields
        row.querySelectorAll('.editable-field').forEach(field => {
            field.disabled = false;
            field.classList.remove('d-none');
        });

        // Hide display values
        row.querySelectorAll('.display-value').forEach(span => {
            span.classList.add('d-none');
        });

        // Focus on the first editable field
        const firstInput = row.querySelector('.editable-field');
        if (firstInput) {
            firstInput.focus();
            if (firstInput.tagName === 'INPUT' && firstInput.type !== 'checkbox') {
                firstInput.select();
            }
        }
    }

    saveRow(row) {
        const rowData = this.collectRowData(row);
        const route = document.querySelector(this.options.gridSelector).dataset.route;
        const isNew = row.dataset.isNew === 'true';

        // Show saving indicator
        row.classList.add('saving');

        // Prepare fetch options
        const fetchOptions = {
            method: isNew ? 'POST' : 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify(rowData)
        };

        // Determine the correct URL
        const url = isNew ? route : `${route}/${rowData.id}`;

        // Send the request
        fetch(url, fetchOptions)
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw err;
                    });
                }
                return response.json();
            })
            .then(data => {
                // Update the row with returned data
                this.updateRowWithData(row, data);

                // Exit edit mode
                row.classList.remove('editing');
                row.querySelector('.edit-btn').classList.remove('d-none');
                row.querySelector('.save-btn').classList.remove('d-none'); // Keep save button visible
                row.querySelector('.cancel-btn').classList.add('d-none');

                // If it was a new row, update the ID and remove the isNew flag
                if (isNew) {
                    row.dataset.id = data.id; // Update the data-id with the new ID from the server
                    delete row.dataset.isNew;
                }

                // Show success message
                toastr.success(isNew ? 'Row created successfully' : 'Row updated successfully');
            })
            .catch(error => {
                console.error('Error saving row:', error);
                let errorMessage = 'Error saving row';
                if (error.errors) {
                    errorMessage = Object.values(error.errors).join('<br>');
                } else if (error.message) {
                    errorMessage = error.message;
                }
                toastr.error(errorMessage);
            })
            .finally(() => {
                row.classList.remove('saving');
            });
    }

    cancelRowEditing(row) {
        const isNew = row.dataset.isNew === 'true';

        if (isNew) {
            // If it's a new row, just remove it
            row.remove();
            this.checkEmptyTable();
        } else {
            // Reset to original values
            row.classList.remove('editing');
            row.querySelector('.edit-btn').classList.remove('d-none');
            row.querySelector('.save-btn').classList.add('d-none');
            row.querySelector('.cancel-btn').classList.add('d-none');

            // Hide form fields and show display values
            row.querySelectorAll('.editable-field').forEach(field => {
                field.disabled = true;
                field.classList.add('d-none');
            });

            row.querySelectorAll('.display-value').forEach(span => {
                span.classList.remove('d-none');
            });
        }
    }

    collectRowData(row) {
        const data = {
            id: row.dataset.id,
            group_id: row.querySelector('[name="group_id"]').value // Ensure group_id is included
        };

        row.querySelectorAll('.editable').forEach(cell => {
            const fieldName = cell.dataset.field;
            const fieldType = cell.dataset.type;
            const field = cell.querySelector('.editable-field');

            if (fieldName === 'group_id') return; // Skip group_id as it's already added

            if (fieldType === 'checkbox') {
                data[fieldName] = field.checked ? 1 : 0;
            } else if (field) {
                data[fieldName] = field.value;
            }
        });

        return data;
    }

    updateRowWithData(row, data) {
        // Ensure all editable fields are updated with server data
        row.querySelectorAll('.editable').forEach(cell => {
            const fieldName = cell.dataset.field;
            const fieldType = cell.dataset.type;
            const value = data[fieldName] !== undefined ? data[fieldName] : '';
            const displayElement = cell.querySelector('.display-value');
            const editableField = cell.querySelector('.editable-field');

            if (fieldType === 'select') {
                if (editableField.tagName === 'SELECT') {
                    editableField.value = value;
                    const selectedOption = editableField.options[editableField.selectedIndex];
                    displayElement.textContent = selectedOption ? selectedOption.text : '';
                }
            } else if (fieldType === 'checkbox') {
                editableField.checked = value == 1;
                displayElement.textContent = value == 1 ? 'Yes' : 'No';
            } else {
                editableField.value = value;
                displayElement.textContent = value;
            }
        });

        // Hide form fields and show display values
        row.querySelectorAll('.editable-field').forEach(field => {
            field.disabled = true;
            field.classList.add('d-none');
        });

        row.querySelectorAll('.display-value').forEach(span => {
            span.classList.remove('d-none');
        });
    }

    populateDropdowns(row, selectedData = {}) {
        const grid = document.querySelector(this.options.gridSelector);

        // Populate groups
        const groupCell = row.querySelector('.editable[data-field="group_id"]');
        if (groupCell) {
            const select = groupCell.querySelector('select');
            const optionsUrl = grid.dataset.groupOptions;

            this.fetchAndPopulateOptions(select, optionsUrl, selectedData.group_id);
        }

        // Populate crops
        const cropCell = row.querySelector('.editable[data-field="crop_id"]');
        if (cropCell) {
            const select = cropCell.querySelector('select');
            const optionsUrl = grid.dataset.cropOptions;

            this.fetchAndPopulateOptions(select, optionsUrl, selectedData.crop_id);
        }

        // Populate length units
        const unitCell = row.querySelector('.editable[data-field="length_unit_id"]');
        if (unitCell) {
            const select = unitCell.querySelector('select');
            const optionsUrl = grid.dataset.lengthUnitOptions;

            this.fetchAndPopulateOptions(select, optionsUrl, selectedData.length_unit_id);
        }

        // Populate dependent dropdowns if values are set
        if (selectedData.group_id) {
            this.updateBeneficiaryOptions(row, selectedData.group_id, selectedData.beneficiary_id);
        }

        if (selectedData.crop_id) {
            this.updateCropVarietyOptions(row, selectedData.crop_id, selectedData.crop_varieties_id);
        }
    }

    fetchAndPopulateOptions(select, url, selectedValue = null, callback = null) {
        if (!url) return;

        fetch(url)
            .then(response => response.json())
            .then(options => {
                // Clear existing options except the placeholder
                while (select.options.length > 1) {
                    select.remove(1);
                }

                // Populate options with flexibility for different response formats
                options.forEach(option => {
                    const optElement = document.createElement('option');
                    // Handle both { value, label } and { id, name } formats
                    optElement.value = option.value || option.id;
                    optElement.textContent = option.label || option.name;
                    select.appendChild(optElement);
                });

                // Set selected value if provided
                if (selectedValue) {
                    select.value = selectedValue;
                    const selectedOption = select.options[select.selectedIndex];
                    const displayElement = select.closest('td.editable').querySelector('.display-value');
                    if (displayElement && selectedOption) {
                        displayElement.textContent = selectedOption.text;
                    }
                } else {
                    // Reset display if no value is selected
                    const displayElement = select.closest('td.editable').querySelector('.display-value');
                    if (displayElement) {
                        displayElement.textContent = '';
                    }
                }

                if (callback) callback();
            })
            .catch(error => {
                console.error(`Error fetching options:`, error);
                toastr.error('Failed to load dropdown options');
            });
    }

    updateBeneficiaryOptions(row, groupId, selectedValue = null) {
        if (!groupId) return;

        const grid = document.querySelector(this.options.gridSelector);
        const beneficiaryCell = row.querySelector('.editable[data-field="beneficiary_id"]');
        if (!beneficiaryCell) return;

        const select = beneficiaryCell.querySelector('select');
        const optionsUrl = `${grid.dataset.beneficiaryOptions}?group_id=${groupId}`;

        this.fetchAndPopulateOptions(select, optionsUrl, selectedValue);
    }

    updateCropVarietyOptions(row, cropId, selectedValue = null) {
        if (!cropId) return;

        const grid = document.querySelector(this.options.gridSelector);
        const varietyCell = row.querySelector('.editable[data-field="crop_varieties_id"]');
        if (!varietyCell) return;

        const select = varietyCell.querySelector('select');
        const optionsUrl = `${grid.dataset.cropVarietyOptions}?crop_id=${cropId}`;

        this.fetchAndPopulateOptions(select, optionsUrl, selectedValue);
    }

    getSelectedRows() {
        const selectedRows = [];
        document.querySelectorAll(`${this.options.gridSelector} .row-checkbox:checked`).forEach(checkbox => {
            const row = checkbox.closest('tr');
            if (!row.dataset.removed) {
                selectedRows.push(row);
            }
        });
        return selectedRows;
    }

    saveAllRows() {
        const rows = document.querySelectorAll(`${this.options.gridSelector} tbody tr:not([data-removed="true"])`);
        if (rows.length === 0) {
            toastr.warning('No rows to save');
            return;
        }

        this.saveRows(Array.from(rows));
    }

    saveRows(rows) {
        const promises = [];
        let successCount = 0;
        let errorCount = 0;

        rows.forEach(row => {
            const rowData = this.collectRowData(row);
            const route = document.querySelector(this.options.gridSelector).dataset.route;
            const isNew = row.dataset.isNew === 'true';

            // Show saving indicator
            row.classList.add('saving');

            // Prepare fetch options
            const fetchOptions = {
                method: isNew ? 'POST' : 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(rowData)
            };

            // Determine the correct URL
            const url = isNew ? route : `${route}/${rowData.id}`;

            // Add to promises array
            promises.push(
                fetch(url, fetchOptions)
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw err;
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    // Update the row with returned data
                    this.updateRowWithData(row, data);

                    // If it was a new row, update the ID and remove the isNew flag
                    if (isNew) {
                        row.dataset.id = data.id; // Update the data-id with the new ID from the server
                        delete row.dataset.isNew;
                    }

                    successCount++;
                })
                .catch(error => {
                    console.error('Error saving row:', error);
                    errorCount++;
                })
                .finally(() => {
                    row.classList.remove('saving');
                })
            );
        });

        // Wait for all requests to complete
        Promise.all(promises).then(() => {
            if (errorCount === 0) {
                toastr.success(`Successfully saved ${successCount} row${successCount !== 1 ? 's' : ''}`);
            } else {
                toastr.warning(
                    `Saved ${successCount} row${successCount !== 1 ? 's' : ''}, ${errorCount} error${errorCount !== 1 ? 's' : ''} occurred`
                );
            }
        });
    }
}