<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    message: String,
});
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Access Denied" />
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-[#1A2C38] overflow-hidden shadow-sm sm:rounded-lg text-center p-8">
                    <h1 class="text-2xl font-bold text-red-600 dark:text-red-400 mt-3">Access Denied</h1>
                    <p class="mt-4 text-gray-700 dark:text-gray-300">{{ message }}</p>
                    <Link :href="route('dashboard')" class="mt-6 mb-6 inline-block bg-[#22c55e] text-white px-4 py-2 rounded hover:bg-[#16a34a]">
                        Go to Dashboard
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
