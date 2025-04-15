import DependentDropdown from './dependentDropdown.js';

export function initFansepDropdowns(routes, initialValues = {}) {
    // Component -> Subcomponent
    new DependentDropdown(
        '#component_id', // Assuming ID is used
        'select[name="sub_component_id"]',
        routes.subcomponents, // Pass the route for fetching subcomponents
        { placeholder: 'Select SubComponent' }
    );
      // Sector -> SubSector
    new DependentDropdown(
        '#sector_id', // Assuming ID is used
        'select[name="sub_sector_id"]',
        routes.subSectors, // Pass the route for fetching subcomponents
        { placeholder: 'Select SubSector', initialValue: initialValues.sub_sector_id  }
    );
}