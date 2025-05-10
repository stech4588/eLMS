<template>
    <aside class="w-44 bg-white shadow-md p-6 space-y-6">
        <div class="flex items-center justify-center">
            <Link :href="route('dashboard')">
                <ApplicationLogo class="h-10 w-auto text-indigo-600" />
            </Link>
        </div>

        <nav class="space-y-4">
            <SidebarItem
                v-for="item in menu"
                :key="item.title"
                :title="item.title"
                :description="item.description"
                :href="item.href"
            />
        </nav>
    </aside>
</template>

<script setup>
import SidebarItem from './SidebarItem.vue'
import ApplicationLogo from '@/Components/ApplicationLogo.vue'
import { Link } from '@inertiajs/vue3'

const menu = [
    {
        title: 'Dashboard',
        description: 'Your app overview',
        href: '/dashboard',
    },
    {
        title: 'Help Center',
        description: 'Support and FAQs',
        href: '/help',
    },
    {
        title: 'Contact',
        description: 'Reach out to us',
        href: '/contact',
    },
]
</script>
