<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    pages: Array,
});

const form = useForm({
    page_id: '',
    meta_title: '',
    meta_description: '',
    meta_keywords: '',
});

const submit = () => {
    form.post(route('metatags.store'));
};
</script>

<template>
    <Head title="Create New Meta Tag" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create New Meta Tag</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit">
                            <div>
                                <label for="page_id" class="block font-medium text-sm text-gray-700">Page</label>
                                <select id="page_id" v-model="form.page_id" class="block w-full mt-1">
                                    <option value="">Select a Page</option>
                                    <option v-for="page in pages" :key="page.id" :value="page.id">{{ page.name }}</option>
                                </select>
                                <div v-if="form.errors.page_id" class="text-sm text-red-600 mt-1">{{ form.errors.page_id }}</div>
                            </div>

                            <div class="mt-4">
                                <label for="meta_title" class="block font-medium text-sm text-gray-700">Meta Title</label>
                                <input type="text" id="meta_title" v-model="form.meta_title" class="block w-full mt-1">
                                <div v-if="form.errors.meta_title" class="text-sm text-red-600 mt-1">{{ form.errors.meta_title }}</div>
                            </div>

                            <div class="mt-4">
                                <label for="meta_description" class="block font-medium text-sm text-gray-700">Meta Description</label>
                                <textarea id="meta_description" v-model="form.meta_description" rows="4" class="block w-full mt-1"></textarea>
                            </div>

                            <div class="mt-4">
                                <label for="meta_keywords" class="block font-medium text-sm text-gray-700">Meta Keywords</label>
                                <input type="text" id="meta_keywords" v-model="form.meta_keywords" class="block w-full mt-1">
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <Link :href="route('metatags.index')" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-4">
                                    Cancel
                                </Link>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-[#148ad9] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#148ad9] active:bg-[#148ad9] focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150" :disabled="form.processing">
                                    Create Meta Tag
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template> 