<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

defineProps({
    pages: Array,
});

const deletePage = (id) => {
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
            router.delete(route('metatags.destroy', id), {
                onSuccess: () => {
                    Swal.fire(
                        'Deleted!',
                        'The page has been deleted.',
                        'success'
                    );
                },
                onError: () => {
                    Swal.fire(
                        'Error!',
                        'There was a problem deleting the page.',
                        'error'
                    );
                }
            });
        }
    });
};
</script>

<template>
    <Head title="Meta Tags" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">Meta Tags</h2>
        </template>

        <div class="py-12 ">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg dark:bg-dark-bg-secondary">
                    <div class="p-6 bg-white border-b border-gray-200 dark:bg-dark-bg-secondary dark:border-dark-border-secondary">
                        <div class="flex items-center justify-between mb-6">
                            <h1 class="text-2xl font-bold dark:text-white">Meta Tags Management</h1>
                            <Link :href="route('metatags.create')" class="inline-flex items-center px-4 py-2 bg-[#148ad9] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#148ad9] active:bg-[#148ad9] focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Create New Page
                            </Link>
                        </div>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 dark:bg-dark-bg-secondary ">
                                <tr class="dark_meta_tags_table_header">
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-white">
                                        Meta Tag ID
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-white">
                                        Meta Title
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-white">
                                        Page Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider justify-end flex dark:text-white">
                                        <span class="">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-[#293E4C]">
                                <tr v-for="page in pages" :key="page.id" class="dark_meta_tags_table_row">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">{{ page.metatag.id }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">{{ page.metatag ? page.metatag.meta_title : '' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">{{ page.name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-end">
                                        <Link :href="route('metatags.edit', page.id)" class="text-indigo-600 hover:text-indigo-900 mr-4"><img src="/images/pen_icon.svg" alt="Edit" class="dark_meta_tags_icons action-icon"/></Link>
                                        <button @click="deletePage(page.id)" class="text-red-600 hover:text-red-900"><img class="w-4 h-4 dark_meta_tags_icons action-icon" src="/images/delete_icon.svg" alt="Delete" /></button>
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
.dark .dark_meta_tags_icons{
    filter: invert(1);
}
.dark .dark_meta_tags_table_header{
    border-bottom: 1px solid #374151 ;
}
.dark .dark_meta_tags_table_row{
    border-bottom: 1px solid #374151 !important;
}
</style>