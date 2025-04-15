import DependentDropdown from './dependentDropdown.js';

export function initWardDropdowns(routes, initialValues = {}) {
    // Local Level (rural_municipality_id) -> Wards
    new DependentDropdown(
        'select[name="localLevel_id"]', // Trigger based on rural_municipality_id
        'select[name="wards[]"]',                  // Target wards dropdown
        routes.wards,                            // Route to fetch wards count
        {
            placeholder: 'Select Ward',
            initialValue: initialValues.wards,
            customHandler: function(data) {      // Custom handler to generate ward options
                const wardCount = data.wards || 0;
                const options = [];
                for (let i = 1; i <= wardCount; i++) {
                    options.push([i, `Ward-${i}`]);
                }
                return options;
            }
        }
    );
}