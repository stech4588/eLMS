<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { computed, watch } from 'vue';

const props = defineProps({
    invoice: Object, // For editing, null for creating
    users: {
        type: Array,
        default: () => [],
    },
    plans: {
        type: Object,
        default: () => ({ monthly: [], yearly: [] }),
    },
});

const form = useForm({
    user_id: props.invoice?.user_id || '',
    plan: props.invoice?.plan || (props.plans.monthly?.[0]?.value ?? ''),
    billing_cycle: props.invoice?.billing_cycle || 'monthly',
    amount: props.invoice?.amount || '',
    due_date: props.invoice?.due_date || '',
    payment_method: props.invoice?.payment_method || 'stripe',
    status: props.invoice?.status || 'unpaid',
});

const activePlanOptions = computed(() => props.plans[form.billing_cycle] || []);

watch(
    () => form.billing_cycle,
    (cycle) => {
        if (!activePlanOptions.value.find((option) => option.value === form.plan)) {
            form.plan = activePlanOptions.value.length ? activePlanOptions.value[0].value : '';
        }
    },
    { immediate: true }
);

watch(
    () => [form.plan, form.billing_cycle],
    () => {
        const match = activePlanOptions.value.find((option) => option.value === form.plan);
        form.amount = match ? match.price : '';
    },
    { immediate: true }
);

const submit = () => {
    if (props.invoice) {
        form.put(route('invoices.update', props.invoice.id), {
            preserveScroll: true,
            onError: (errors) => console.error('Error updating invoice:', errors),
        });
    } else {
        form.post(route('invoices.store'), {
            preserveScroll: true,
            onError: (errors) => console.error('Error creating invoice:', errors),
        });
    }
};
</script>

<template>
    <Head :title="invoice ? 'Edit Invoice' : 'Create Invoice'" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-white">
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
                <div class="overflow-hidden bg-white dark:bg-dark-bg-secondary shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white dark:bg-dark-bg-secondary border-b border-gray-200 dark:border-gray-700">
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">User</label>
                                <select v-model="form.user_id" id="user_id" name="user_id"
                                    class="block w-full mt-1 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                    <option disabled value="">Select a user</option>
                                    <option v-for="user in users" :key="user.id" :value="user.id">
                                        {{ user.name }} ({{ user.email }})
                                    </option>
                                </select>
                                <div v-if="form.errors.user_id" class="mt-2 text-sm text-red-600 dark:text-red-400">{{ form.errors.user_id }}</div>
                            </div>

                            <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Billing Cycle</label>
                                    <select v-model="form.billing_cycle" class="block w-full mt-1 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                        <option value="monthly">Monthly</option>
                                        <option value="yearly">Yearly</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Plan</label>
                                    <select v-model="form.plan" class="block w-full mt-1 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                        <option v-for="plan in activePlanOptions" :key="plan.value" :value="plan.value">
                                            {{ plan.label }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount</label>
                                <input type="text" :value="Number(form.amount || 0).toFixed(2)" readonly
                                    class="block w-full mt-1 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm sm:text-sm cursor-not-allowed" />
                            </div>

                            <div class="mb-4">
                                <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Due Date</label>
                                <input type="date" v-model="form.due_date" id="due_date" name="due_date"
                                    class="block w-full mt-1 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm" />
                                <div v-if="form.errors.due_date" class="mt-2 text-sm text-red-600 dark:text-red-400">{{ form.errors.due_date }}</div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Method</label>
                                <input type="text" v-model="form.payment_method" readonly
                                    class="block w-full mt-1 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm sm:text-sm opacity-70 cursor-not-allowed" />
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Manual invoices default to Stripe; change the method later if needed.</p>
                                <div v-if="form.errors.payment_method" class="mt-2 text-sm text-red-600 dark:text-red-400">{{ form.errors.payment_method }}</div>
                            </div>

                            <div class="mb-4">
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                 <select v-model="form.status" id="status" name="status"
                                     class="block w-full mt-1 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                    <option value="unpaid">Unpaid</option>
                                    <option value="paid">Paid</option>
                                    <option value="overdue">Overdue</option>
                                </select>
                                <div v-if="form.errors.status" class="mt-2 text-sm text-red-600 dark:text-red-400">{{ form.errors.status }}</div>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <button type="submit"
                                    class="px-4 py-2 font-bold text-white bg-[#1C355E] rounded hover:bg-[#254a7a] focus:outline-none focus:shadow-outline active:bg-[#1a3d6e]"
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