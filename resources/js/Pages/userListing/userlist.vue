<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

defineProps({
    users: Array,
});

const editUser = (userId) => {
    console.log('Edit user:', userId);
};

const deleteUser = (userId) => {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('users.destroy', userId), {
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire(
                        'Deleted!',
                        'The user has been deleted.',
                        'success'
                    );
                },
                onError: (errors) => {
                    console.error('Error deleting user:', errors);
                    Swal.fire(
                        'Error!',
                        'There was a problem deleting the user.',
                        'error'
                    );
                },
            });
        }
    });
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
                    class="overflow-hidden bg-white dark:bg-dark-bg-secondary shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900 dark:text-white" style="overflow-x: auto;">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Id</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Email</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-dark-bg-secondary">
                                <tr v-if="users && users.length === 0">
                                    <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">No users found.</td>
                                </tr>
                                <tr v-for="user in users" :key="user.id">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ user.id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ user.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ user.email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" style="display: flex; align-items: center;">
                                        <Link :href="route('users.edit', user.id)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-2"><img src="/images/pen_icon.svg" alt="Edit" class="w-4 h-4 user_listing_dark_icons"></Link>
                                        <button @click="deleteUser(user.id)" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"><img src="/images/delete_icon.svg" alt="Edit" class="w-4 h-4 user_listing_dark_icons"></button>
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

<style>
.dark .user_listing_dark_icons{
    filter: invert(1);
}
</style>