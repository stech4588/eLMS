import { ref } from 'vue';
import axios from 'axios';

export const permissions = ref({});

let isFetching = false;
let hasFetched = false;

export const fetchPermissions = async () => {
    if (isFetching || hasFetched) {
        return;
    }

    isFetching = true;

    const requiredPermissions = [
        'userView', 'instructorListing', 'pricingUpdate', 'coursemanagement',
        'metatagsUpdate', 'communitySettingsView', 'dashboardView', 'careerJourneyView',
        'communityView', 'libraryView', 'contentView', 'mycourses', 'addnewcourses', 'helpView', 'marketingmanagement',
        'jobPost'
    ];

    try {
        const response = await axios.get('/check-permissions', {
            params: { permissions: requiredPermissions }
        });
        permissions.value = response.data.permissions;
        hasFetched = true;
    } catch (error) {
        console.error("Error checking permissions:", error);
        permissions.value = {}; // Reset on error
    } finally {
        isFetching = false;
    }
};

export const hasPermission = (permissionName) => {
    return permissions.value[permissionName] === true;
};

export const clearPermissions = () => {
    permissions.value = {};
    hasFetched = false;
};
