<script setup>
import { ref, computed, onMounted } from 'vue'
import ApplicationLogo from '@/Components/ApplicationLogo.vue'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'
import NavLink from '@/Components/NavLink.vue'
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue'
import { Link, usePage, router, Head } from '@inertiajs/vue3'
import AuthSidebar from '@/Components/AuthSidebar.vue'
import axios from 'axios';
import { fetchPermissions, clearPermissions } from '@/permissions.js';

const user = usePage().props.auth?.user;
const showingNavigationDropdown = ref(false)
const isSidebarOpen = ref(false)
const page = usePage();
const isLoading = ref(false);
const isDark = ref(false);
const meta = computed(() => page.props.meta || {});
const notifications = ref([]);

const fetchNotifications = async () => {
    if (user?.type === 'admin') {
        try {
            const response = await axios.get(route('notifications.index'));
            notifications.value = response.data;
        } catch (error) {
            console.error('Error fetching notifications:', error);
        }
    }
};

// Check for saved theme preference or system preference
onMounted(() => {
    if (user) {
        fetchNotifications();
        fetchPermissions();
    }
    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        isDark.value = true;
        document.documentElement.classList.add('dark');
        document.documentElement.setAttribute('data-swal2-theme', 'dark');
    } else {
        isDark.value = false;
        document.documentElement.classList.remove('dark');
        document.documentElement.removeAttribute('data-swal2-theme');
    }
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
    isSidebarOpen.value = !isSidebarOpen.value
}

const isPlayerPage = computed(() => page.component === 'Course/Player');
const isCartPage = computed(() => page.component === 'cart/cart');

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
    <div :class="{ 'dark': isDark }" class="flex min-h-screen bg-[#97d5ff] dark:bg-dark-bg-primary mobile_view_style"
        style="flex-direction: column;">
        <nav class="border-b border-gray-100 dark:border-dark-border-primary bg-white dark:bg-dark-bg-secondary">
            <div class="mx-auto px-4 sm:px-6 lg:px-8" style="border-bottom: 1px solid rgb(225 225 225)">
                <div class="flex h-16 justify-between">
                    <div class="sidebar_button_nav">
                        <button class="sidebar_openbutton" @click="toggleSidebar">
                            <img src="/images/sidebar_icon.svg">
                        </button>
                    </div>

                    <a :href="user ? (user.type === 'instructor' ? '/coursess' : '/dashboard') : '/'">
                        <img src="/images/MBM_Uni.png" alt="logo" class="logo_image_nav"
                            style="width: 80px; height: 80px;">
                    </a>

                    <!-- User Dropdown -->
                    <div class="hidden sm:ms-6 sm:flex sm:items-center">
                        <div class="relative ms-3" style="display:flex;flex-direction: row;">
                            <div class="flex items-center justify-center mr-4">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button class="flex items-center justify-center relative">
                                            <img src="/images/notification_icon.svg" alt="notification" class="w-6 h-6 dark:invert">
                                            <span v-if="notifications.length > 0" class="absolute top-0 right-0 transform translate-x-1/2 -translate-y-1/2 text-xs text-white bg-red-500 rounded-full w-4 h-4 flex items-center justify-center">
                                                {{ notifications.length }}
                                            </span>
                                        </button>
                                    </template>
                                    <template #content>
                                        <div v-if="notifications.length > 0">
                                            <DropdownLink v-for="notification in notifications" :key="notification.id" :href="route('notifications.read', notification.id)" class="text-gray-700 bg-gray-100 dark:text-dark-text-secondary hover:bg-[#97d5ff] dark:hover:bg-dark-bg-tertiary">
                                                {{ notification.data.message }}
                                            </DropdownLink>
                                        </div>
                                        <div v-else class="px-4 py-2 text-sm text-gray-700 dark:text-dark-text-secondary">
                                            No new notifications
                                        </div>
                                    </template>
                                </Dropdown>
                            </div>
                            <div><button @click="toggleDarkMode"
                                    class="w-full text-left  text-sm text-gray-700 dark:text-dark-text-secondary " style="padding: 5px !important;">
                                    <i style="font-size: 22px;" :class="isDark ? 'fas fa-sun text-yellow-500' : 'fas fa-moon text-gray-700'" :title="isDark ? 'Light Mode' : 'Dark Mode'"></i>
                                </button></div>
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button type="button"
                                            class="inline-flex items-center rounded-md border border-transparent bg-white dark:bg-dark-bg-secondary text-sm font-medium leading-4 text-gray-500 dark:text-dark-text-secondary transition hover:text-gray-700 dark:hover:text-dark-text-primary focus:outline-none" style="padding:5px !important;">
                                            {{ $page.props.auth.user.name }}
                                            <svg class="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content class="dark:bg-dark-bg-secondary">
                                    <DropdownLink :href="route('profile.edit')"
                                        class="text-gray-700 dark:text-dark-text-secondary hover:bg-gray-100 dark:hover:bg-dark-bg-tertiary">
                                        Profile</DropdownLink>
                                    <DropdownLink :href="route('cart')"
                                        class="text-gray-700 dark:text-dark-text-secondary hover:bg-gray-100 dark:hover:bg-dark-bg-tertiary">
                                        Cart</DropdownLink>

                                    <DropdownLink @click="logout" as="button"
                                        class="text-gray-700 dark:text-white dark:text-whitehover:bg-gray-100 dark:hover:bg-dark-bg-tertiary">
                                        Log Out</DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                    </div>

                    <!-- Mobile Hamburger -->
                    <div class="-me-2 flex items-center sm:hidden">
                        <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                            class="inline-flex items-center justify-center rounded-md p-2 dark:bg-dark-bg-secondarytext-gray-400 dark:text-white transition hover:bg-gray-100 dark:hover:bg-dark-bg-tertiary hover:text-gray-500 dark:hover:text-dark-text-primary focus:outline-none">
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
            <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden">
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
            <AuthSidebar v-if="!isPlayerPage && !isCartPage" :class="{ 'sidebar-closed': !isSidebarOpen }" />

            <!-- Main Content Area -->
            <div class="flex flex-col flex-1" :style="{ width: isCartPage ? '100% !important' : '56% !important' }">
                <!-- Optional Page Heading -->
                <header class="bg-white dark:bg-dark-bg-secondary shadow dark:shadow-dark" v-if="$slots.header">
                    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                        <slot name="header" />
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 home_page_style flex-box relative">
                    <div v-if="isLoading && !meta.disableLoader" class="page-transition-loader dark:bg-dark">
                        <div id="loader">
                            <div id="box1"></div>
                            <div id="box2"></div>
                            <div id="box3"></div>
                            <div id="shadow1"></div>
                            <div id="shadow2"></div>
                            <div id="shadow3"></div>
                        </div>
                    </div>
                    <slot :is-sidebar-open="isSidebarOpen" :is-player-page="isPlayerPage" />
                </main>
            </div>
        </div>
    </div>
</template>

<style scoped>
.flex-box {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

@media (max-width: 770px) {
    .mobile_view_style {
        display: flex;
    }
}

.sidebar_button_nav {
    display: none;
}

@media (max-width: 770px) {
    .sidebar_button_nav {
        display: flex;
    }
}

.logo_image_nav {
    display: block;
    cursor: pointer;
}

@media (max-width: 770px) {
    .logo_image_nav {
        /*   */
    }
}

.home_page_style {
    background-color: #97d5ff;
    text-align: start;
}

.dark .home_page_style {
    background-color: #1a1a1a;
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
    background-color: #97D5FF;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    border-radius: 8px;
}

.dark .page-transition-loader {
    background-color: #1a1a1a !important;
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
    background: #2b2899;
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
</style>