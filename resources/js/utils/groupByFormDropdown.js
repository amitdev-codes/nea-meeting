import DependentDropdown from "./dependentDropdown.js";

export function initGroupByFormDropdowns(routes, initialValues = {}) {
    // Sector -> Sub sector
    new DependentDropdown(
        "#sector_id", // Assuming ID is used
        'select[name="sub_sector_id"]',
        routes.subSectors, // Pass the route for fetching subcomponents
        {
            placeholder: "Select Sub sector",
            displayKey: "name", // Match the key from controller response
            initialValue: initialValues.subSectors,
        }
    );

    // sector-> localLevels
    new DependentDropdown(
        "#sector_id", // Assuming ID is used
        'select[name="local_level_id"]',
        routes.localLevelsBySector, 
        {
            placeholder: "Select LocalLevels",
            displayKey: "name",
            initialValue: initialValues.local_level_id,
            forceLoad: true 
        }
    );
    // local levels -> Groups
    new DependentDropdown(
        "#local_level_id", // Assuming ID is used
        'select[name="group_id"]',
        routes.groups, // Pass the route for fetching subcomponents
        {
            placeholder: "Select Groups",
            displayKey: "name",
            initialValue: initialValues.group_id,
        }
    );
}