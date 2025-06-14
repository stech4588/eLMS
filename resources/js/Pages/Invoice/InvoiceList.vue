<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    invoices: Array,
});

const deleteInvoice = (invoiceId) => {
    if (confirm('Are you sure you want to delete this invoice?')) {
        router.delete(route('invoices.destroy', invoiceId), {
            preserveScroll: true,
            onSuccess: () => {
                // Optional: Show success notification
            },
            onError: (errors) => {
                console.error('Error deleting invoice:', errors);
                // Optional: Handle errors, e.g., show error notification
            },
        });
    }
};

</script>

<template>
    <Head title="Invoice Listing" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex space-x-4">
                <Link :href="route('users.index')"
                    class="px-4 py-2 text-xl font-semibold leading-tight text-white bg-[#148ad9] rounded hover:bg-[#148ad9] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
                >
                    User Listing
                </Link>
                <Link :href="route('invoices.index')"
                    class="px-4 py-2 text-xl font-semibold leading-tight text-white bg-[#148ad9] rounded hover:bg-[#148ad9] focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50"
                >
                    Invoice Listing
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="flex justify-end mb-4 mr-8">
                 <Link :href="route('invoices.create')"
                    class="px-4 py-2 text-lg font-semibold leading-tight text-black dark:text-white bg-white dark:bg-dark-bg-secondary rounded hover:bg-white dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                    Create Invoice
                </Link>
            </div>

            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white dark:bg-dark-bg-secondary shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900 dark:text-white" style="overflow-x: auto;">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Id</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">User ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Amount</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Payment Method</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Payment Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Transaction ID</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-dark-bg-secondary">
                                <tr v-if="invoices && invoices.length === 0">
                                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">No invoices found.</td>
                                </tr>
                                <tr v-for="invoice in invoices" :key="invoice.id">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ invoice.id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ invoice.user_id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ invoice.amount }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ invoice.payment_method }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ invoice.payment_status }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ invoice.transaction_id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ invoice.id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ invoice.user.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ invoice.details[0]?.course?.title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ invoice.amount }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ invoice.payment_method }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ invoice.payment_status }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ invoice.transaction_id }}</td>
                                    <td class="py-4 px-6 whitespace-nowrap text-sm font-medium">
                                        <Link :href="route('invoices.show', invoice.id)" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                            <img src="/images/view_icon.svg" alt="View" style="max-width: 20px; max-height: 20px;" class="">
                                        </Link>
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