<template>
    <Head title="Pricings" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-white dark:text-gray-200 leading-tight">Pricings</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-[#1A2C38] overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-white">
                        <div class="flex justify-between mb-6">
                          
                            <div>
                                <button @click="activeTab = 'monthly'" :class="{'bg-[#1897e4] text-white': activeTab === 'monthly'}" class="px-4 py-2 rounded-l-lg">Monthly</button>
                                <button @click="activeTab = 'yearly'" :class="{'bg-[#1897e4] text-white': activeTab === 'yearly'}" class="px-4 py-2 rounded-r-lg">Yearly</button>
                            </div>
                        </div>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 dark:bg-[#0F212E]">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Plan Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-[#293E4C] divide-y divide-gray-200">
                                <tr v-for="pricing in filteredPricings" :key="pricing.id">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ pricing.plan_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">${{ pricing.price }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <Link :href="route('pricings.edit', pricing.id)" class="text-indigo-600 hover:text-indigo-900"><img src="/images/pen_icon.svg" alt="Edit" class="dark_meta_tags_icons inline-block action-icon"/></Link>
                                      
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

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    pricings: Array
});

const activeTab = ref('monthly');

const filteredPricings = computed(() => {
    return props.pricings.filter(pricing => pricing.type === activeTab.value);
});

const form = useForm({});

const deletePricing = (id) => {
    if (confirm('Are you sure you want to delete this pricing?')) {
        form.delete(route('pricings.destroy', id));
    }
};
</script> 

<style>
.dark .dark_meta_tags_icons{
    filter: invert(1);
}
</style> 