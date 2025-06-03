<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    users: Array,
});

const editUser = (userId) => {
    console.log('Edit user:', userId);
};

const deleteUser = (userId) => {
    if (confirm('Are you sure you want to delete this user?')) {
        router.delete(route('users.destroy', userId), {
            preserveScroll: true,
            onSuccess: () => {
                // You might want to show a success notification here
                // The page should automatically reload with the updated user list
                // if your controller redirects back with Inertia::render or redirect()->route()
            },
            onError: (errors) => {
                console.error('Error deleting user:', errors);
                // Handle errors, e.g., show an error notification
            },
        });
    }
};
</script>

<template>
    <Head title="User Listing" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex space-x-4">
                <Link :href="route('users.index')"
                    class="px-4 py-2 text-xl font-semibold leading-tight text-white bg-[#148ad9] rounded hover:bg-[#148ad9] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
                >
                    User Listing
                </Link>
                <Link :href="route('invoices.index')"
                    class="px-4 py-2 text-xl font-semibold leading-tight text-white bg-[#148ad9] rounded hover:bg-[#148ad9] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
                >
                    Invoice Listing
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900" style="overflow-x: auto;">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Id</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Email</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <tr v-if="users && users.length === 0">
                                    <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No users found.</td>
                                </tr>
                                <tr v-for="user in users" :key="user.id">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ user.id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ user.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ user.email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" style="display: flex; align-items: center;">
                                        <Link :href="route('users.edit', user.id)" class="text-indigo-600 hover:text-indigo-900 mr-2"><img src="/images/pen_icon.svg" alt="Edit" class="w-4 h-4"></Link>
                                        <button @click="deleteUser(user.id)" class="text-red-600 hover:text-red-900"><img src="/images/delete_icon.svg" alt="Edit" class="w-4 h-4"></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
