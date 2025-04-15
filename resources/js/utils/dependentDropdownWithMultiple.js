export default class DependentDropdownWithMultiple {
    constructor(triggerSelector, dependentSelector, url, options = {}) {
        this.trigger = $(triggerSelector); // e.g., '#component_id'
        this.dependent = $(dependentSelector); // e.g., 'select[name="sub_component_id"]'
        this.url = url; // Route URL for fetching dependent data
        this.options = {
            placeholder: options.placeholder || 'Select an option',
            dataKey: options.dataKey || 'id', // Key for value in response data
            displayKey: options.displayKey || 'name', // Key for display text
            initialValue: options.initialValue || '',
            customHandler: options.customHandler || null,
            extraParams: options.extraParams || null,
            // New: Add support for additional parents
            additionalParents: options.additionalParents || {},
            ...options,
        };

        this.init();
    }

    init() {
        this.trigger.on('change', () => this.handleChange());
        
        // Add event listeners to additional parent dropdowns if any
        for (const key in this.options.additionalParents) {
            const parentSelector = this.options.additionalParents[key];
            $(parentSelector).on('change', () => {
                // Only reload if the main trigger has a value
                if (this.trigger.val()) {
                    this.handleChange();
                }
            });
        }
        
        this.handleChange(); // Trigger initial load if value exists
    }

    handleChange() {
        const triggerValue = this.trigger.val();
        this.dependent.empty().prop('disabled', true);
        this.dependent.append(`<option value="">${this.options.placeholder}</option>`);

        if (triggerValue) {
            // Prepare base data
            let requestData = { 
                parent_value: triggerValue 
            };
            
            // Add values from additional parents
            for (const key in this.options.additionalParents) {
                const parentSelector = this.options.additionalParents[key];
                const parentValue = $(parentSelector).val();
                if (parentValue) {
                    requestData[key] = parentValue;
                }
            }
            
            // Add extra params if specified
            if (this.options.extraParams) {
                requestData = { ...requestData, ...this.options.extraParams };
            }

            $.ajax({
                url: this.url,
                type: 'GET',
                data: requestData,
                dataType: 'json',
                success: (data) => {
                    this.populateOptions(data);
                },
                error: (xhr, status, error) => console.error('AJAX Error:', error),
            });
        }
    }

    populateOptions(data) {
        this.dependent.prop('disabled', false);

        let options = this.options.customHandler
            ? this.options.customHandler(data)
            : (Array.isArray(data) ? data : []); //Default to empty array if data isn't iterable

        // Populate the dropdown with options
        options.forEach((item) => {
            const value = Array.isArray(item) ? item[0] : item[this.options.dataKey];
            const text = Array.isArray(item) ? item[1] : item[this.options.displayKey];
            this.dependent.append(
                `<option value="${value}">${text}</option>`
            );
        });
        
        // Set initial value(s) - handle both single and multiple selections
        if (this.options.initialValue) {
            const initialValue = Array.isArray(this.options.initialValue)
                ? this.options.initialValue
                : [this.options.initialValue]; // Convert single value to array
            this.dependent.val(initialValue);
        }
        
        this.dependent.trigger('change'); // Re-initialize Select2 or other plugins
    }
}