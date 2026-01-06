<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue'
import ApplicationLogo from '@/Components/ApplicationLogo.vue'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'
import NavLink from '@/Components/NavLink.vue'
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue'
import PromotionPopup from '@/Components/PromotionPopup.vue';
import LearningGoalPopup from '@/Components/LearningGoalPopup.vue';
import ContactNotificationModal from '@/Components/ContactNotificationModal.vue';
import { Link, usePage, router, Head } from '@inertiajs/vue3'
import AuthSidebar from '@/Components/AuthSidebar.vue'
import axios from 'axios';
import { fetchPermissions, clearPermissions, hasPermission } from '@/permissions.js';
import { formatDistanceToNow } from 'date-fns';

const user = computed(() => {
    const authUser = usePage().props.auth?.user;
    return authUser || null;
});
const showingNavigationDropdown = ref(false)
const isSidebarOpen = ref(false)
const isSidebarCollapsed = ref(false);
const SIDEBAR_OPEN_STORAGE_KEY = 'elms.sidebar.open';
const SIDEBAR_COLLAPSED_STORAGE_KEY = 'elms.sidebar.collapsed';
const page = usePage();
const isLoading = ref(false);
const isDark = ref(false);
const meta = computed(() => page.props.meta || {});
const notifications = ref([]);
const checkingPagePermission = ref(false);
const hasPageAccess = ref(true);
const permissionMessage = ref('');
const showContactModal = ref(false);
const selectedContactNotification = ref(null);
const pagePermissionMap = {
    'Dashboard': 'dashboardView',
    'careerJourney/myCareerJourney': 'careerJourneyView',
    'Community/Index': 'communityView',
    'Groups/Index': 'communityView',
    'Groups/Chat': 'communityView',
    'library/mylibrary': 'libraryView',
    'content/mycontent': 'contentView',
    'Courses/myCourses': 'mycourses',
    'addCourses/addNewCourses': 'addnewcourses',
    'help/help': 'helpView',
    'Admin/Marketing/Index': 'marketingmanagement',
    'Admin/CommunitySettings': 'communitySettingsView',
    'CourseManagement/Index': 'coursemanagement',
    'Admin/MetaTags/Index': 'metatagsUpdate',
    'Admin/MetaTags/Create': 'metatagsUpdate',
    'Admin/MetaTags/Edit': 'metatagsUpdate',
    'Admin/Pricings/Index': 'pricingUpdate',
    'Admin/Pricings/Create': 'pricingUpdate',
    'Admin/Pricings/Edit': 'pricingUpdate',
    'Admin/Pricings/Show': 'pricingUpdate',
    'Admin/Instructors/Index': 'instructorListing',
    'Admin/Instructors/Show': 'instructorListing',
    'userListing/userlist': 'userView',
    'userListing/EditUser': 'userView',
    // 'Jobs/Index': 'jobPost',
    // 'Jobs/Create': 'jobPost',
};

watch(isSidebarOpen, (value) => {
    if (typeof window !== 'undefined') {
        try {
            window.localStorage.setItem(SIDEBAR_OPEN_STORAGE_KEY, value ? 'true' : 'false');
        } catch (error) {
            console.warn('Unable to persist sidebar open state:', error);
        }
    }
});

watch(isSidebarCollapsed, (value) => {
    if (typeof window !== 'undefined') {
        try {
            window.localStorage.setItem(SIDEBAR_COLLAPSED_STORAGE_KEY, value ? 'true' : 'false');
        } catch (error) {
            console.warn('Unable to persist sidebar collapsed state:', error);
        }
    }
});

const fetchNotifications = async () => {
    if (user) {
        try {
            const response = await axios.get(route('notifications.index'));
            notifications.value = response.data;
        } catch (error) {
            console.error('Error fetching notifications:', error);
        }
    }
};

const handleNotificationClick = async (notification) => {
    // Check if it's a contact form notification (by type or by presence of contact fields)
    const isContactNotification = notification.data && (
        notification.data.type === 'contact_form' || 
        (notification.data.contact_name && notification.data.contact_email)
    );
    
    if (isContactNotification) {
        // Mark as read
        try {
            await axios.post(route('notifications.markRead', notification.id));
            // Remove from notifications list
            notifications.value = notifications.value.filter(n => n.id !== notification.id);
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
        
        // Show modal with notification data
        selectedContactNotification.value = {
            ...notification.data,
            created_at: notification.created_at,
        };
        showContactModal.value = true;
    } else {
        // For other notifications, use the default link behavior
        window.location.href = route('notifications.read', notification.id);
    }
};

const closeContactModal = () => {
    showContactModal.value = false;
    selectedContactNotification.value = null;
};

// Check for saved theme preference or system preference
onMounted(() => {
    // Tawk.to widget management
    window.Tawk_API = window.Tawk_API || {};
    // When the widget loads, check if it should be shown
    window.Tawk_API.onLoad = manageTawkToWidget;
    // Also run the check on mount in case the widget is already loaded
    manageTawkToWidget();

    if (user) {
        fetchNotifications();
        fetchPermissions();
        window.addEventListener('new-notification', fetchNotifications);
    }

    if (typeof window !== 'undefined') {
        try {
            const savedSidebarOpen = window.localStorage.getItem(SIDEBAR_OPEN_STORAGE_KEY);
            if (savedSidebarOpen !== null) {
                isSidebarOpen.value = savedSidebarOpen === 'true';
            }

            const savedSidebarCollapsed = window.localStorage.getItem(SIDEBAR_COLLAPSED_STORAGE_KEY);
            if (savedSidebarCollapsed !== null) {
                isSidebarCollapsed.value = savedSidebarCollapsed === 'true';
            }
        } catch (error) {
            console.warn('Unable to restore sidebar state:', error);
        }
    }

    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'light') {
        // Respect explicit light choice; otherwise default to dark
        isDark.value = false;
        document.documentElement.classList.remove('dark');
        document.documentElement.removeAttribute('data-swal2-theme');
        localStorage.setItem('theme', 'light');
    } else {
        isDark.value = true;
        document.documentElement.classList.add('dark');
        document.documentElement.setAttribute('data-swal2-theme', 'dark');
        if (!savedTheme) {
            localStorage.setItem('theme', 'dark');
        }
    }

    // Expose global loader controls
    window.showPageLoader = () => { isLoading.value = true; };
    window.hidePageLoader = () => { isLoading.value = false; };
    
    // Initialize and watch screen size
    if (typeof window !== 'undefined') {
        const updateScreenSize = () => {
            isLargeScreen.value = window.innerWidth >= 1025;
        };
        updateScreenSize();
        window.addEventListener('resize', updateScreenSize);
        window.updateLayoutScreenSize = updateScreenSize; // Store for cleanup
    }
});

const evaluatePagePermission = async () => {
    if (!user) {
        hasPageAccess.value = true;
        permissionMessage.value = '';
        checkingPagePermission.value = false;
        return;
    }

    const requiredPermission = pagePermissionMap[page.component];

    if (!requiredPermission) {
        hasPageAccess.value = true;
        permissionMessage.value = '';
        checkingPagePermission.value = false;
        return;
    }

    checkingPagePermission.value = true;

    try {
        await fetchPermissions();
        hasPageAccess.value = hasPermission(requiredPermission);
        permissionMessage.value = hasPageAccess.value
            ? ''
            : 'You do not have permission to view this page.';
    } catch (error) {
        hasPageAccess.value = false;
        permissionMessage.value = 'Unable to verify permissions. Please try again.';
    } finally {
        checkingPagePermission.value = false;
    }
};

watch(() => page.component, () => {
    evaluatePagePermission();
}, { immediate: true });

onUnmounted(() => {
    window.removeEventListener('new-notification', fetchNotifications);
    if (typeof window !== 'undefined' && window.updateLayoutScreenSize) {
        window.removeEventListener('resize', window.updateLayoutScreenSize);
        delete window.updateLayoutScreenSize;
    }
    // Clean up globals (optional)
    delete window.showPageLoader;
    delete window.hidePageLoader;
});

// Toggle dark mode
const toggleDarkMode = () => {
    isDark.value = !isDark.value;
    if (isDark.value) {
        document.documentElement.classList.add('dark');
        document.documentElement.setAttribute('data-swal2-theme', 'dark');
        localStorage.theme = 'dark';
    } else {
        document.documentElement.classList.remove('dark');
        document.documentElement.removeAttribute('data-swal2-theme');
        localStorage.theme = 'light';
    }
};

const logout = () => {
    clearPermissions();
    router.post(route('logout'));
};

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
}

const toggleSidebarCollapse = () => {
    isSidebarCollapsed.value = !isSidebarCollapsed.value;
};

const isPlayerPage = computed(() => page.component === 'Course/Player');
const isCartPage = computed(() => page.component === 'cart/cart');
const isGroupChatPage = computed(() => page.component === 'Groups/Chat');
const isHelpPage = computed(() => page.component === 'help/help');
const isLargeScreen = ref(typeof window !== 'undefined' ? window.innerWidth >= 1025 : false);

const manageTawkToWidget = () => {
    // Use optional chaining for safety, as Tawk_API might not be loaded yet.
    if (window.Tawk_API?.hideWidget) {
        if (isPlayerPage.value || isGroupChatPage.value || isHelpPage.value) {
            window.Tawk_API.hideWidget();
        } else {
            window.Tawk_API.showWidget();
        }
    }
};

watch(isPlayerPage, manageTawkToWidget);
watch(isGroupChatPage, manageTawkToWidget);
watch(isHelpPage, manageTawkToWidget);

onMounted(() => {
    router.on('start', () => {
        isLoading.value = true;
    });

    router.on('finish', () => {
        isLoading.value = false;
    });
});
</script>

<template>

    <Head>
        <title>{{ meta.meta_title }}</title>
        <meta name="description" :content="meta.meta_description">
        <meta name="keywords" :content="meta.meta_keywords">
    </Head>
    <div :class="{ 'dark': isDark }" class="flex min-h-screen bg-[#5A8FB3] dark:bg-dark-bg-primary mobile_view_style"
        style="flex-direction: column;">
        <nav class="border-b border-gray-100 dark:border-dark-border-primary bg-white dark:bg-[#1A2C38] nav-gradient">
            <div class="mx-auto px-4 sm:px-6 lg:px-8" style="border-bottom: 1px solid rgb(225 225 225)">
                <div class="flex h-16 justify-between">
                  

                    <div class="flex items-center">
                        <div class="sidebar_button_nav">
                        <button class="sidebar_openbutton" @click="toggleSidebar">
                            <img src="/images/sidebar_icon.svg" class="dark:invert">
                        </button>
                    </div>
                        <button class="sidebar_openbutton hidden min-[1025px]:block" @click="toggleSidebarCollapse">
                            <img src="/images/sidebar_icon.svg" class="dark:invert" style="height: 40px;">
                        </button>

                        <a :href="user && user.type === 'instructor' ? '/coursess' : (user ? '/dashboard' : '/')">
                            <img src="/images/MBM_Uni.png" alt="logo" class="logo_image_nav"
                                style="width: 80px; height: 80px;">
                        </a>
                    </div>


                    <!-- User Dropdown -->
                    <div v-if="user" class="flex items-center ms-3 sm:ms-6">
                        <div class="relative" style="display:flex;flex-direction: row;">
                            <div class="flex items-center justify-center mr-2 sm:mr-4">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button class="flex items-center justify-center relative p-1">
                                            <img src="/images/notification_icon.svg" alt="notification" class="w-5 h-5 sm:w-6 sm:h-6 dark:invert notification-bell">
                                            <span v-if="notifications.length > 0" class="absolute top-0 right-0 transform translate-x-1/2 -translate-y-1/2 text-xs text-white bg-red-500 rounded-full w-4 h-4 flex items-center justify-center">
                                                {{ notifications.length }}
                                            </span>
                                        </button>
                                    </template>
                                    <template #content>
                                        <div class="p-2 font-semibold text-center text-gray-800 bg-gray-50 border-b border-gray-200 dark:bg-dark-bg-tertiary dark:text-white dark:border-gray-600">
                                            Notifications
                                        </div>
                                        <div v-if="notifications.length > 0" class="max-h-96 overflow-y-auto">
                                            <a 
                                                v-for="notification in notifications" 
                                                :key="notification.id" 
                                                @click.prevent="handleNotificationClick(notification)"
                                                class="flex items-start px-4 py-3 text-sm transition duration-150 ease-in-out border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-dark-bg-tertiary cursor-pointer"
                                            >
                                                <div class="w-full">
                                                    <p class="text-gray-700 dark:text-gray-300">{{ notification.data.message }}</p>
                                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                        {{ formatDistanceToNow(new Date(notification.created_at), { addSuffix: true }) }}
                                                    </p>
                                                </div>
                                            </a>
                                        </div>
                                        <div v-else class="px-4 py-10 text-sm text-center text-gray-500 dark:text-dark-text-secondary">
                                            You have no new notifications
                                        </div>
                                    </template>
                                </Dropdown>
                            </div>
                            <div class="mr-2 sm:mr-0"><button @click="toggleDarkMode"
                                    class="text-left text-sm text-gray-700 dark:text-dark-text-secondary p-1 sm:p-1.5">
                                    <i class="text-lg sm:text-xl" :class="isDark ? 'fas fa-sun text-yellow-500' : 'fas fa-moon text-gray-700'" :title="isDark ? 'Light Mode' : 'Dark Mode'"></i>
                                </button></div>
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button type="button"
                                            class="inline-flex items-center rounded-md border border-transparent bg-white dark:bg-[#1A2C38] text-xs sm:text-sm font-medium leading-4 text-gray-500 dark:text-dark-text-secondary transition hover:text-gray-700 dark:hover:text-dark-text-primary focus:outline-none px-2 py-1 sm:px-2.5 sm:py-1.5">
                                            <span class="hidden sm:inline">{{ user?.name || page.props.auth?.user?.name || 'Account' }}</span>
                                            <span class="sm:hidden">{{ (user?.name || page.props.auth?.user?.name || 'A').charAt(0).toUpperCase() }}</span>
                                            <svg class="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content class="dark:bg-[#1A2C38]">
                                    <DropdownLink :href="route('profile.edit')"
                                        class="text-gray-700 dark:text-dark-text-secondary hover:bg-gray-100 dark:hover:bg-dark-bg-tertiary">
                                        Profile</DropdownLink>
                                    <DropdownLink v-if="hasPermission('emailSettingsManage')" :href="route('settings.index')"
                                        class="text-gray-700 dark:text-dark-text-secondary hover:bg-gray-100 dark:hover:bg-dark-bg-tertiary">
                                        Settings</DropdownLink>

                                    <DropdownLink @click="logout" as="button"
                                        class="text-gray-700 dark:text-white dark:text-whitehover:bg-gray-100 dark:hover:bg-dark-bg-tertiary">
                                        Log Out</DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                    </div>

                    <!-- Mobile Hamburger -->
                    <div class="hidden -me-2 flex items-center sm:hidden">
                        <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                            class="inline-flex items-center justify-center rounded-md p-2 dark:bg-[#1A2C38]text-gray-400 dark:text-white transition hover:bg-gray-100 dark:hover:bg-dark-bg-tertiary hover:text-gray-500 dark:hover:text-dark-text-primary focus:outline-none">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path
                                    :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                                <path
                                    :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Responsive Menu -->
            <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="hidden sm:hidden">
                <div class="space-y-1 pb-3 pt-2">
                    <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')"
                        class="text-gray-900 dark:text-dark-text-primary">Dashboard</ResponsiveNavLink>
                </div>

                <!-- User Info -->
                <div class="border-t border-gray-200 dark:border-dark-border-primary pb-1 pt-4">
                    <div class="px-4">
                        <div class="text-base font-medium text-gray-800 dark:text-dark-text-primary">{{
                            $page.props.auth.user.name
                            }}</div>
                        <div class="text-sm font-medium text-gray-500 dark:text-dark-text-secondary">{{
                            $page.props.auth.user.email
                            }}</div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <ResponsiveNavLink :href="route('profile.edit')"
                            class="text-gray-700 dark:text-dark-text-secondary">Profile
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('cart')" class="text-gray-700 dark:text-dark-text-secondary">
                            Cart
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('billing.portal')" class="text-gray-700 dark:text-dark-text-secondary">
                            Billing
                        </ResponsiveNavLink>
                        <button @click="toggleDarkMode"
                            class="w-full text-left text-sm text-gray-700 dark:text-dark-text-secondary" style="padding: 5px;">
                            <i class="fas" :class="isDark ? 'fa-sun text-yellow-500' : 'fa-moon text-gray-700'"></i>
                            <span class="ml-2">{{ isDark ? 'Light Mode' : 'Dark Mode' }}</span>
                        </button>
                        <ResponsiveNavLink @click="logout" as="button"
                            class="text-gray-700 dark:text-dark-text-secondary">Log Out</ResponsiveNavLink>
                    </div>
                </div>
            </div>
        </nav>

        <div style="display: flex; flex-direction: row;">
            <AuthSidebar v-if="!isCartPage" :class="{ 'sidebar-closed': !isSidebarOpen, 'player-page-sidebar': isPlayerPage }" :is-collapsed="isSidebarCollapsed"/>

            <!-- Main Content Area -->
            <div class="flex flex-col flex-1 main-content" :class="{ 
                'content-expanded': !isSidebarCollapsed && !isPlayerPage && !isCartPage, 
                'content-collapsed': isSidebarCollapsed && !isPlayerPage && !isCartPage, 
                'no-sidebar': (isPlayerPage || isCartPage) && !isSidebarOpen && !isLargeScreen,
                'player-content-with-sidebar': isPlayerPage && ((isSidebarOpen && !isSidebarCollapsed) || (isLargeScreen && !isSidebarCollapsed)),
                'player-content-with-sidebar-collapsed': isPlayerPage && ((isSidebarOpen && isSidebarCollapsed) || (isLargeScreen && isSidebarCollapsed))
            }">
                <!-- Optional Page Heading -->
                <header class="bg-light-header dark:bg-[#1A2C38] shadow dark:shadow-dark" v-if="$slots.header">
                    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                        <slot name="header" />
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 home_page_style flex-box relative">
                    <div v-if="isLoading && !meta.disableLoader" class="page-transition-loader">
                        <div id="loader">
                            <div id="box1"></div>
                            <div id="box2"></div>
                            <div id="box3"></div>
                            <div id="shadow1"></div>
                            <div id="shadow2"></div>
                            <div id="shadow3"></div>
                        </div>
                    </div>
                    <div v-if="checkingPagePermission" class="w-full py-12">
                        <div class="max-w-4xl mx-auto text-center text-gray-700 dark:text-gray-300">
                            Checking permissions...
                        </div>
                    </div>
                    <div v-else-if="!hasPageAccess" class="w-full py-12">
                        <div class="max-w-4xl mx-auto text-center bg-white dark:bg-[#1A2C38] border border-gray-200 dark:border-gray-700 rounded-lg shadow-md p-8">
                            <h2 class="text-2xl font-semibold text-red-600 dark:text-red-400">Access Denied</h2>
                            <p class="mt-4 text-gray-700 dark:text-gray-300">
                                {{ permissionMessage || 'You do not have permission to view this page.' }}
                            </p>
                            <Link :href="route('dashboard')" class="mt-6 inline-block bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-700 transition">
                                Go to Dashboard
                            </Link>
                        </div>
                    </div>
                    <slot v-else :is-sidebar-open="isSidebarOpen" :is-player-page="isPlayerPage" />
                </main>
            </div>
        </div>
        <PromotionPopup v-if="user && (user.type === 'student' || user.type === 'instructor')" />
        <LearningGoalPopup v-if="user && user.type === 'student'" />
        <ContactNotificationModal 
            :show="showContactModal" 
            :notification-data="selectedContactNotification"
            @close="closeContactModal"
        />
    </div>
</template>

<style scoped>
.flex-box {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

@media (max-width: 1024px) {
    .mobile_view_style {
        display: flex;
    }
}

.sidebar_button_nav {
    display: none;
}

.sidebar_openbutton {
    position: relative;
    z-index: 70;
}

@media (max-width: 1024px) {
    .sidebar_button_nav {
        display: flex;
    }
}

.logo_image_nav {
    display: block;
    cursor: pointer;
}

.nav-gradient {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 120;
    width: 100%;
}

.mobile_view_style {
    padding-top: 5rem; /* match navbar height */
}

@media (max-width: 1024px) {
    .logo_image_nav {
        /*   */
    }
}

.home_page_style {
    background-color: #5A8FB3;
    text-align: start;
}

.dark .home_page_style {
    background-color: #0F202D;
}

.notification-bell {
    animation: bellPulse 5s ease-in-out infinite;
    transform-origin: top center;
}

@keyframes bellPulse {
    0% {
        transform: rotate(0deg);
    }
    4% {
        transform: rotate(-14deg);
    }
    8% {
        transform: rotate(12deg);
    }
    12% {
        transform: rotate(-8deg);
    }
    16% {
        transform: rotate(6deg);
    }
    20% {
        transform: rotate(-3deg);
    }
    24% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(0deg);
    }
}

.h-16 {
    height: 5rem !important;
}

.p-6 {
    padding: 1.51rem;
}

.page-transition-loader {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #fbfbfb;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    /* border-radius: 8px; */
}

.dark .page-transition-loader {
    background-color: #1A2C38 !important;
}

main {
    position: relative;
}

#loader {
    position: relative;
    width: 200px;
    height: 60px;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
}

#box1,
#box2,
#box3 {
    width: 50px;
    height: 50px;
    background: #4CCAFF;
    animation: animate .4s linear infinite;
    border-radius: 3px;
}

.dark #box1,
.dark #box2,
.dark #box3 {
    background: #4CCAFF !important;
}

@keyframes animate {
    17% {
        border-bottom-right-radius: 3px;
    }

    25% {
        transform: translateY(9px) rotate(22.5deg);
    }

    50% {
        transform: translateY(18px) scale(1, .9) rotate(45deg);
        border-bottom-right-radius: 40px;
    }

    75% {
        transform: translateY(9px) rotate(67.5deg);
    }

    100% {
        transform: translateY(0) rotate(90deg);
    }
}

#shadow1,
#shadow2,
#shadow3 {
    width: 50px;
    height: 5px;
    background: #000;
    opacity: 0.1;
    position: absolute;
    top: 59px;
    border-radius: 50%;
    animation: shadow .4s linear infinite;
}

.dark #shadow1,
.dark #shadow2,
.dark #shadow3 {
    background: #fff !important;
    opacity: 0.2;
}

@keyframes shadow {
    50% {
        transform: scale(1.2, 1);
    }
}

/* Add transition for smooth theme switching */
:root {
    @apply transition-colors duration-200;
}

/* Dark mode transitions */
.dark {
    @apply transition-colors duration-200;
}

/* Ensure all elements transition smoothly */
* {
    @apply transition-colors duration-200;
}

/* Make SVGs light colored in dark mode */
.dark svg {
    @apply text-white;
}

.dark svg path {
    @apply stroke-white;
}

.dark svg g {
    @apply fill-white;
}

.main-content {
    transition: margin-left 0.3s ease-in-out;
}

.content-expanded {
    margin-left: 300px;
}

.content-collapsed {
    margin-left: 92px;
}

.no-sidebar {
    margin-left: 0 !important;
}

@media (max-width: 1024px) {
    .main-content,
    .content-expanded,
    .content-collapsed {
        margin-left: 0 !important;
    }
}

/* Player page sidebar styling */
.player-page-sidebar {
    z-index: 1001 !important;
}

/* Player page content adjustments when sidebar is open */
.player-content-with-sidebar {
    margin-left: 300px !important;
    transition: margin-left 0.3s ease-in-out;
}

.player-content-with-sidebar-collapsed {
    margin-left: 92px !important;
    transition: margin-left 0.3s ease-in-out;
}

/* Desktop: sidebar pushes content when open */
@media (min-width: 1025px) {
    .player-page-sidebar {
        position: fixed !important;
    }
    
    /* On large screens, player page content should always account for sidebar */
    .player-content-with-sidebar {
        margin-left: 300px !important;
    }
    
    .player-content-with-sidebar-collapsed {
        margin-left: 92px !important;
    }
    
    /* Override no-sidebar on large screens for player page */
    .main-content.no-sidebar {
        /* Will be overridden by player-content-with-sidebar classes above */
    }
}

/* Mobile: sidebar overlays content */
@media (max-width: 1024px) {
    .player-page-sidebar.sidebar-closed {
        left: -100% !important;
        opacity: 0 !important;
        pointer-events: none !important;
    }
    
    .player-page-sidebar:not(.sidebar-closed) {
        left: 0 !important;
        opacity: 1 !important;
        pointer-events: auto !important;
    }
    
    /* On mobile, sidebar overlays, so no margin adjustment needed */
    .player-content-with-sidebar,
    .player-content-with-sidebar-collapsed {
        margin-left: 0 !important;
    }
}
</style>