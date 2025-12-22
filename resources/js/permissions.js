import { ref } from 'vue';
import axios from 'axios';

export const permissions = ref({});

const requiredPermissions = [
    'userView', 'instructorListing', 'pricingUpdate', 'coursemanagement',
    'metatagsUpdate', 'communitySettingsView', 'dashboardView', 'careerJourneyView',
    'communityView', 'libraryView', 'contentView', 'mycourses', 'addnewcourses', 'helpView', 'marketingmanagement',
    'jobPost'
];

let hasFetched = false;
let fetchPromise = null;

export const fetchPermissions = async () => {
    if (hasFetched) {
        return permissions.value;
    }

    if (fetchPromise) {
        return fetchPromise;
    }

    fetchPromise = axios.get('/check-permissions', {
        params: { permissions: requiredPermissions }
    })
        .then((response) => {
            permissions.value = response.data.permissions;
            hasFetched = true;
            return permissions.value;
        })
        .catch((error) => {
            console.error("Error checking permissions:", error);
            permissions.value = {};
            throw error;
        })
        .finally(() => {
            fetchPromise = null;
        });

    return fetchPromise;
};

export const hasPermission = (permissionName) => {
    return permissions.value[permissionName] === true;
};

export const clearPermissions = () => {
    permissions.value = {};
    hasFetched = false;
    fetchPromise = null;
};
