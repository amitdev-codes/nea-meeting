import DependentDropdown from "./dependentDropdown.js";

export function initFormEntriesDropdowns(routes, initialValues = {}) {
    //filter part 1
    // Component -> Subcomponent
    new DependentDropdown(
        "#component_id", // Assuming ID is used
        'select[name="sub_component_id"]',
        routes.sub_components, // Pass the route for fetching subcomponents
        {
            placeholder: "Select Sub component",
            placeholder: "Select Sub component",
            displayKey: "name", // Match the key from controller response
            initialValue: initialValues.sub_component_id,
        }
    );

    //subcomponent->lmbis activity
    new DependentDropdown(
        "#sub_component_id", // Assuming ID is used
        'select[name="lmbis_activity_id"]',
        routes.lmbis_activities, // Pass the route for fetching subcomponents
        {
            placeholder: "Select Lmbis Activity",
            placeholder: "Select Lmbis Activity",
            displayKey: "lmbis_activity_name", // Match the key from controller response
            initialValue: initialValues.lmbis_activity_id,
        }
    );

    // Lmbis Activity -> forms
    new DependentDropdown(
        "#lmbis_activity_id", // Assuming ID is used
        'select[name="form_id"]',
        routes.forms, // Pass the route for fetching subcomponents
        {
            placeholder: "Select Forms",
            displayKey: "name",
            initialValue: initialValues.form_id,
        }
    );

    //filter part 2
    new DependentDropdown(
        "#sector_id", // Assuming ID is used
        'select[name="local_level_id"]',
        routes.localLevels, 
        {
            placeholder: "Select LocalLevels",
            displayKey: "name",
            initialValue: initialValues.local_level_id,
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
