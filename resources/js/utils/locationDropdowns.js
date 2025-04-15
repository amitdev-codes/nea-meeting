import DependentDropdown from './dependentDropdown.js';

export function initLocationDropdowns(routes, initialValues = {}) {
    // Province -> District
    new DependentDropdown(
        'select[name="province_id"]',
        'select[name="district_id"]',
        routes.districts, // Pass the route for fetching districts
        { placeholder: 'Select District',initialValue: initialValues.district_id  }
    );

    // District -> Local Level
    new DependentDropdown(
        'select[name="district_id"]',
        'select[name="localLevel_id"]',
        routes.localLevels, // Pass the route for fetching local levels
        { placeholder: 'Select Local Level',initialValue: initialValues.localLevel_id }
    );
}