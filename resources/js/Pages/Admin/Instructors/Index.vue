<template>
    <Head title="Instructor Listing" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-white dark:text-gray-200 leading-tight">Instructor Listing</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-[#0b1624] border border-gray-200 dark:border-[#1f2d40] overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 dark:bg-[#1A2C38]">
                        <div v-if="$page.props.flash.success" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            <p>{{ $page.props.flash.success }}</p>
                        </div>
                        <div v-if="$page.props.flash.error" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                            <p>{{ $page.props.flash.error }}</p>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-[#1f2d40] bg-white dark:bg-transparent">
                                <thead class="bg-gray-50 dark:bg-[#162437]">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Application Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Account Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-[#0b1624] divide-y divide-gray-200 dark:divide-[#1f2d40]">
                                    <tr v-for="instructorUser in instructors" :key="instructorUser.id" class="transition-colors duration-150 hover:bg-gray-50 dark:hover:bg-[#1b2c3f]">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ instructorUser.name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ instructorUser.email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium capitalize">
                                            <span
                                                :class="[
                                                    instructorUser.instructor.status === 'pending' && 'text-yellow-800 dark:text-yellow-300',
                                                    instructorUser.instructor.status === 'approved' && 'text-green-800 dark:text-green-300',
                                                    instructorUser.instructor.status === 'rejected' && 'text-red-800 dark:text-red-300',
                                                ]"
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                            >
                                                {{ instructorUser.instructor.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span
                                                v-if="instructorUser.instructor.status === 'approved'"
                                                :class="instructorUser.is_active ? 'text-green-800 dark:text-green-300' : 'text-red-800 dark:text-red-300'"
                                                class="inline-flex text-xs leading-5 font-semibold rounded-full"
                                            >
                                                {{ instructorUser.is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                            <span v-else class="text-gray-400">N/A</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-4">
                                                <Link :href="route('admin.instructors.show', { user: instructorUser.id })" class="text-indigo-600 hover:text-indigo-900 cursor-pointer dark:invert"><img src="/images/view_icon.svg" alt="View" class="w-4 h-4 action-icon"></Link>

                                                <button @click="deleteInstructor(instructorUser.id)" class="text-red-600 hover:text-red-900 cursor-pointer dark:invert"><img src="/images/delete_icon.svg" alt="Delete" class="w-4 h-4 action-icon"></button>
                                                
                                                <div v-if="instructorUser.instructor.status === 'pending'" class="flex space-x-2">
                                                    <Link :href="route('admin.instructors.show', { user: instructorUser.id, source: 'request' })" class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-xs">Request View</Link>
                                                   
                                                </div>
                                                <div v-else-if="instructorUser.instructor.status === 'approved'">
                                                    <label class="relative inline-flex items-center cursor-pointer">
                                                        <input type="checkbox" :checked="instructorUser.is_active" @change="toggleStatus(instructorUser.id)" class="sr-only peer">
                                                        <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                                    </label>
                                                </div>
                                                <div v-else>
                                                    <span class="text-gray-500 italic">Rejected</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import Swal from 'sweetalert2';

const props = defineProps({
    instructors: Array,
});



const toggleStatus = (userId) => {
    router.post(`/admin/instructors/${userId}/toggle-status`, {}, {
        preserveScroll: true,
    });
};

const deleteInstructor = (userId) => {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('admin.instructors.destroy', { user: userId }), {
                preserveScroll: true,
            });
        }
    });
};
</script> 