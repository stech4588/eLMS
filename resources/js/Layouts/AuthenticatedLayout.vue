<script setup>
import { ref, computed } from 'vue'
import ApplicationLogo from '@/Components/ApplicationLogo.vue'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'
import NavLink from '@/Components/NavLink.vue'
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue'
import { Link, usePage } from '@inertiajs/vue3'
import AuthSidebar from '@/Components/AuthSidebar.vue'


const user = usePage().props.auth?.user;
const showingNavigationDropdown = ref(false)
const isSidebarOpen = ref(false)
const page = usePage();

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value
}

const isPlayerPage = computed(() => page.component === 'Course/Player');
const isCartPage = computed(() => page.component === 'cart/cart');
</script>

<template>
    <div class="flex min-h-screen bg-[#97d5ff] mobile_view_style" style="flex-direction: column;">

        <nav class="border-b border-gray-100 bg-white">
                <div class="mx-auto px-4 sm:px-6 lg:px-8" style="border-bottom: 1px solid rgb(225 225 225)">
                    <div class="flex h-16 justify-between">
                        <div class=" sidebar_button_nav">
<!--                           todo topbar items-->
<button class="sidebar_openbutton" @click="toggleSidebar">
            <img src="/images/sidebar_icon.svg">
        </button>

                        </div>

                        <!-- <a href="/dashboard">
                            <img src="/images/MBM_Uni.png" alt="logo" class="logo_image_nav" style=" width: 80px; height: 80px;">
                        </a> -->
                        <a :href="user ? '/dashboard' : '/'">
                       <img src="/images/MBM_Uni.png" alt="logo" class="logo_image_nav" style="width: 80px; height: 80px;">
                     </a>

                        <!-- User Dropdown -->
                        <div class="hidden sm:ms-6 sm:flex sm:items-center">
                            <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                    <span class="inline-flex rounded-md">
                      <button
                          type="button"
                          class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition hover:text-gray-700 focus:outline-none"
                      >
                        {{ $page.props.auth.user.name }}
                        <svg
                            class="-me-0.5 ms-2 h-4 w-4"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                          <path
                              fill-rule="evenodd"
                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                              clip-rule="evenodd"
                          />
                        </svg>
                      </button>
                    </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                                        <DropdownLink :href="route('cart')">Cart</DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Mobile Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-500 focus:outline-none"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Menu -->
                <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden">
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">Dashboard</ResponsiveNavLink>
                    </div>

                    <!-- User Info -->
                    <div class="border-t border-gray-200 pb-1 pt-4">
                        <div class="px-4">
                            <div class="text-base font-medium text-gray-800">{{ $page.props.auth.user.name }}</div>
                            <div class="text-sm font-medium text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')">Profile</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('profile.edit')">Checkout</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">Log Out</ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>
        <div style="display: flex; flex-direction: row;">
            <AuthSidebar v-if="!isPlayerPage && !isCartPage" :class="{ 'sidebar-closed': !isSidebarOpen }" />

<!-- Main Content Area -->
<div class="flex flex-col flex-1" :style="{ width: isCartPage ? '100% !important' : '56% !important' }">
    <!-- Top Navigation -->
    

    <!-- Optional Page Heading -->
    <header class="bg-white shadow" v-if="$slots.header">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <slot name="header" />
        </div>
    </header>

    <!-- Page Content -->
    <main class="flex-1 p-6 home_page_style flex-box">
        <slot :is-sidebar-open="isSidebarOpen" :is-player-page="isPlayerPage" />
    </main>
</div>
        </div>
        <!-- Sidebar -->
       
    </div>
</template>
<style scoped>
.flex-box{
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
@media (max-width: 770px) {
    .mobile_view_style{
    display: flex;
}
}
.sidebar_button_nav{
    display: none;
     
}
@media (max-width: 770px) {
    .sidebar_button_nav{
        display: flex;
    }
}
.logo_image_nav{
    display: block;
    cursor: pointer;
}
@media (max-width: 770px) {
    .logo_image_nav{
        /*   */
    }
}
.home_page_style {
    background-color: #97d5ff;
    text-align: start;
}
.h-16{
    height:5rem!important;
}
.p-6{
    padding:1.5rem!important;
}
</style>