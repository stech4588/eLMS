<template>
    <Head title="Edit Pricing" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-white dark:text-gray-200 leading-tight">Edit Pricing</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-[#0b1624] border border-gray-200 dark:border-[#1f2d40] overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 dark:bg-[#1A2C38]">
                        <form @submit.prevent="submit">
                            <div>
                                <label for="plan_name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Plan Name</label>
                                <input v-model="form.plan_name" id="plan_name" type="text" class="mt-1 block w-full border-gray-300 dark:border-[#1f2d40] dark:bg-[#142233] dark:text-gray-100 focus:border-indigo-500 dark:focus:border-blue-500 focus:ring-indigo-500 dark:focus:ring-blue-500 rounded-md shadow-sm transition-colors" required>
                            </div>
                            <div class="mt-4">
                                <label for="description" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Description</label>
                                <textarea v-model="form.description" id="description" class="mt-1 block w-full border-gray-300 dark:border-[#1f2d40] dark:bg-[#142233] dark:text-gray-100 focus:border-indigo-500 dark:focus:border-blue-500 focus:ring-indigo-500 dark:focus:ring-blue-500 rounded-md shadow-sm transition-colors" required></textarea>
                            </div>
                            <div class="mt-4">
                                <label for="price" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Price</label>
                                <input v-model="form.price" id="price" type="number" min="0" step="0.01" class="mt-1 block w-full border-gray-300 dark:border-[#1f2d40] dark:bg-[#142233] dark:text-gray-100 focus:border-indigo-500 dark:focus:border-blue-500 focus:ring-indigo-500 dark:focus:ring-blue-500 rounded-md shadow-sm transition-colors" required>
                            </div>
                            <!-- <div class="mt-4">
                                <label for="type" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Type</label>
                                <select v-model="form.type" id="type" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="monthly">Monthly</option>
                                    <option value="yearly">Yearly</option>
                                </select>
                            </div> -->
                            <div class="flex items-center justify-end mt-4">
                                <a :href="route('pricings.index')" class="bg-gray-500 hover:bg-gray-700 dark:bg-[#24364a] dark:hover:bg-[#2f4460] text-white font-bold py-2 px-4 rounded transition-colors">Cancel</a>
                                <button type="submit" class="ml-4 bg-[#1897e4] hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-500 text-white font-bold py-2 px-4 rounded transition-colors">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    pricing: Object
});

const form = useForm({
    plan_name: props.pricing.plan_name,
    description: props.pricing.description,
    price: props.pricing.price,
    type: props.pricing.type
});

const submit = () => {
    form.put(route('pricings.update', props.pricing.id));
};
</script> 