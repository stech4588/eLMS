<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    invoice: Object,
});
</script>

<template>
    <Head :title="`Invoice Details - ${invoice.id}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Invoice Details: #{{ invoice.id }}
                </h2>
                <Link :href="route('invoices.index')"
                    class="px-4 py-2 text-sm font-semibold leading-tight text-white bg-[#1C355E] rounded-lg hover:bg-[#254a7a] focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-60 transition-colors"
                >
                    Back to Invoice List
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white dark:bg-[#0b1624] border border-gray-200 dark:border-[#1f2d40] shadow-sm sm:rounded-2xl transition-colors duration-200">
                    <div class="p-6 sm:p-10 bg-white dark:bg-[#1A2C38] border-b border-gray-200 dark:border-[#1f2d40] transition-colors duration-200">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-8">
                            <div>
                                <p class="text-sm uppercase tracking-widest text-gray-500 dark:text-gray-300">Invoice #{{ invoice.id }}</p>
                                <h3 class="mt-2 text-3xl font-semibold text-gray-900 dark:text-white">Invoice Overview</h3>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-300">
                                    Generated on {{ new Date(invoice.created_at).toLocaleDateString() }}
                                </p>
                            </div>
                            <div class="flex flex-wrap gap-4">
                                <div class="px-5 py-3 rounded-xl bg-gray-100 text-gray-800 dark:bg-gray-600/30 dark:text-gray-200">
                                    <span class="block text-xs uppercase tracking-wide">Amount</span>
                                    <span class="mt-1 text-xl font-semibold">{{ invoice.amount }}</span>
                                </div>
                                <div
                                    class="px-5 py-3 rounded-xl bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-200"
                                    v-if="invoice.payment_status === 'paid'"
                                >
                                    <span class="block text-xs uppercase tracking-wide">Status</span>
                                    <span class="mt-1 text-xl font-semibold capitalize">{{ invoice.payment_status }}</span>
                                </div>
                                <div
                                    class="px-5 py-3 rounded-xl bg-yellow-100 text-yellow-700 dark:bg-yellow-500/20 dark:text-yellow-200"
                                    v-else
                                >
                                    <span class="block text-xs uppercase tracking-wide">Status</span>
                                    <span class="mt-1 text-xl font-semibold capitalize">{{ invoice.payment_status }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="rounded-2xl border border-gray-200 dark:border-[#1f2d40] bg-gray-50 dark:bg-[#142233] p-5 shadow-sm transition-colors">
                                <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">User Name</h4>
                                <p class="mt-2 text-lg font-medium text-gray-900 dark:text-gray-100">{{ invoice.user.name }}</p>
                            </div>
                            <div class="rounded-2xl border border-gray-200 dark:border-[#1f2d40] bg-gray-50 dark:bg-[#142233] p-5 shadow-sm transition-colors">
                                <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Course Name</h4>
                                <p class="mt-2 text-lg font-medium text-gray-900 dark:text-gray-100">{{ invoice.details[0]?.course?.title || 'N/A' }}</p>
                            </div>
                            <div class="rounded-2xl border border-gray-200 dark:border-[#1f2d40] bg-gray-50 dark:bg-[#142233] p-5 shadow-sm transition-colors">
                                <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Payment Method</h4>
                                <p class="mt-2 text-lg font-medium text-gray-900 dark:text-gray-100 capitalize">{{ invoice.payment_method || 'N/A' }}</p>
                            </div>
                            <div class="rounded-2xl border border-gray-200 dark:border-[#1f2d40] bg-gray-50 dark:bg-[#142233] p-5 shadow-sm transition-colors">
                                <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Transaction ID</h4>
                                <p class="mt-2 text-lg font-medium text-gray-900 dark:text-gray-100 break-all">{{ invoice.transaction_id || 'N/A' }}</p>
                            </div>
                            <div class="rounded-2xl border border-gray-200 dark:border-[#1f2d40] bg-gray-50 dark:bg-[#142233] p-5 shadow-sm transition-colors">
                                <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Date Created</h4>
                                <p class="mt-2 text-lg font-medium text-gray-900 dark:text-gray-100">{{ new Date(invoice.created_at).toLocaleDateString() }}</p>
                            </div>
                            <div class="rounded-2xl border border-gray-200 dark:border-[#1f2d40] bg-gray-50 dark:bg-[#142233] p-5 shadow-sm transition-colors">
                                <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Last Updated</h4>
                                <p class="mt-2 text-lg font-medium text-gray-900 dark:text-gray-100">{{ new Date(invoice.updated_at).toLocaleDateString() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template> 