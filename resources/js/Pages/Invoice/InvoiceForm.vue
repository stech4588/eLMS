<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    invoice: Object, // For editing, null for creating
});

const form = useForm({
    user_id: props.invoice?.user_id || null,
    course_id: props.invoice?.course_id || null,
    amount: props.invoice?.amount || null,
    payment_method: props.invoice?.payment_method || '',
    status: props.invoice?.status || 'pending',
});

const submit = () => {
    if (props.invoice) {
        // Handle update
        form.put(route('invoices.update', props.invoice.id), {
            onSuccess: () => {
                // Optional: Show success notification
            },
            onError: (errors) => {
                console.error('Error updating invoice:', errors);
                // Optional: Handle errors
            },
        });
    } else {
        // Handle create
        form.post(route('invoices.store'), {
            onSuccess: () => {
                // Optional: Show success notification
            },
            onError: (errors) => {
                console.error('Error creating invoice:', errors);
                // Optional: Handle errors
            },
        });
    }
};
</script>

<template>
    <Head :title="invoice ? 'Edit Invoice' : 'Create Invoice'" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ invoice ? 'Edit Invoice' : 'Create New Invoice' }}
                </h2>
                <Link :href="route('invoices.index')"
                    class="px-4 py-2 text-sm font-semibold leading-tight text-white bg-[#148ad9] rounded hover:bg-[#148ad9] focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">
                Back to Invoice List
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <label for="user_id" class="block text-sm font-medium text-gray-700">User ID</label>
                                <input type="number" v-model="form.user_id" id="user_id" name="user_id"
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                                <div v-if="form.errors.user_id" class="mt-2 text-sm text-red-600">{{ form.errors.user_id }}</div>
                            </div>

                            <div class="mb-4">
                                <label for="course_id" class="block text-sm font-medium text-gray-700">Course ID</label>
                                <input type="number" v-model="form.course_id" id="course_id" name="course_id"
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                                <div v-if="form.errors.course_id" class="mt-2 text-sm text-red-600">{{ form.errors.course_id }}</div>
                            </div>

                            <div class="mb-4">
                                <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                                <input type="number" step="0.01" v-model="form.amount" id="amount" name="amount"
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                                <div v-if="form.errors.amount" class="mt-2 text-sm text-red-600">{{ form.errors.amount }}</div>
                            </div>

                            <div class="mb-4">
                                <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                                <select v-model="form.payment_method" id="payment_method" name="payment_method"
                                     class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select Payment Method</option>
                                    <option value="credit_card">Credit Card</option>
                                    <option value="paypal">PayPal</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="stripe">Stripe</option>
                                    <option value="other">Other</option>
                                </select>
                                <div v-if="form.errors.payment_method" class="mt-2 text-sm text-red-600">{{ form.errors.payment_method }}</div>
                            </div>

                            <div class="mb-4">
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                 <select v-model="form.status" id="status" name="status"
                                     class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="pending">Pending</option>
                                    <option value="completed">Completed</option>
                                    <option value="failed">Failed</option>
                                </select>
                                <div v-if="form.errors.status" class="mt-2 text-sm text-red-600">{{ form.errors.status }}</div>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <button type="submit"
                                    class="px-4 py-2 font-bold text-white bg-[#148ad9] rounded hover:bg-[#148ad9] focus:outline-none focus:shadow-outline-blue active:bg-blue-800"
                                    :disabled="form.processing">
                                    {{ invoice ? 'Update Invoice' : 'Create Invoice' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template> 