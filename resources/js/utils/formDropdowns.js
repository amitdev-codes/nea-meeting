import DependentDropdown from './dependentDropdown.js';

export function initFormDropdowns(routes, initialValues = {}) {
    // Component -> Lmbis Activity
    new DependentDropdown(
        '#component_id', // Assuming ID is used
        'select[name="lmbis_activity_id"]',
        routes.lmbis_activities, // Pass the route for fetching subcomponents
        { placeholder: 'Select Lmbis Activity',
            placeholder: 'Select Lmbis Activity',
            displayKey: 'lmbis_activity_name', // Match the key from controller response
            initialValue: initialValues.lmbis_activity_id
         }
    );

      // Lmbis Activity -> forms
    new DependentDropdown(
        '#lmbis_activity_id', // Assuming ID is used
        'select[name="form_id"]',
        routes.forms, // Pass the route for fetching subcomponents
        { placeholder: 'Select Forms',displayKey: 'name', initialValue: initialValues.form_id  }
    );
    // Group -> Beneficiary
    new DependentDropdown(
        '#group_id', // Assuming ID is used
        'select[name="beneficiary_id"]',
        routes.beneficiaries, // Pass the route for fetching subcomponents
        { 
            placeholder: 'Select Beneficiary',
            displayKey: 'name', // Match the key from controller response
            initialValue: initialValues.beneficiary_id
         }
    );

      // crop -> crop variety
    new DependentDropdown(
        '#crop_id', // Assuming ID is used
        'select[name="crop_varieties_id"]',
        routes.crop_varieties, // Pass the route for fetching subcomponents
        { placeholder: 'Select Crop Varieties',displayKey: 'name', initialValue: initialValues.crop_varieties_id  }
    );
}