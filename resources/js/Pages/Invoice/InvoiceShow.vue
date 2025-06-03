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
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Invoice Details: #{{ invoice.id }}
                </h2>
                <Link :href="route('invoices.index')"
                    class="px-4 py-2 text-sm font-semibold leading-tight text-white bg-[#148ad9] rounded hover:bg-[#148ad9] focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50"
                >
                    Back to Invoice List
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Invoice ID</h3>
                                <p class="mt-1 text-sm text-gray-600">{{ invoice.id }}</p>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">User ID</h3>
                                <p class="mt-1 text-sm text-gray-600">{{ invoice.user_id }}</p>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Amount</h3>
                                <p class="mt-1 text-sm text-gray-600">{{ invoice.amount }}</p>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Payment Method</h3>
                                <p class="mt-1 text-sm text-gray-600">{{ invoice.payment_method }}</p>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Payment Status</h3>
                                <p class="mt-1 text-sm text-gray-600">{{ invoice.payment_status }}</p>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Transaction ID</h3>
                                <p class="mt-1 text-sm text-gray-600">{{ invoice.transaction_id }}</p>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Date Created</h3>
                                <p class="mt-1 text-sm text-gray-600">{{ new Date(invoice.created_at).toLocaleDateString() }}</p>
                            </div>
                           <div>
                                <h3 class="text-lg font-medium text-gray-900">Last Updated</h3>
                                <p class="mt-1 text-sm text-gray-600">{{ new Date(invoice.updated_at).toLocaleDateString() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template> 