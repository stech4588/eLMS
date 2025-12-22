<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed, ref, watch, onMounted, onBeforeUnmount } from 'vue';
import { loadStripe } from '@stripe/stripe-js';
import axios from 'axios';

const props = defineProps({
    invoices: {
        type: Object,
        required: true,
    },
    subscriptionStatus: {
        type: Object,
        default: null,
    },
});

const page = usePage();
const publishableKey = computed(() => page.props?.stripe?.key || null);

const stripeInstance = ref(null);
const elements = ref(null);
const cardElement = ref(null);
const selectedInvoice = ref(null);
const paymentProcessing = ref(false);
const paymentError = ref('');
const paymentSuccess = ref('');

const unpaidInvoices = computed(() =>
    props.invoices.data ? props.invoices.data.filter((invoice) => invoice.status !== 'paid') : []
);

const statusBadgeClass = (status) => {
    switch (status) {
        case 'paid':
            return 'bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-200';
        case 'overdue':
            return 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-200';
        default:
            return 'bg-yellow-100 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-200';
    }
};

const ensureStripe = async () => {
    if (stripeInstance.value) {
        return stripeInstance.value;
    }
    if (!publishableKey.value) {
        throw new Error('Stripe key missing.');
    }
    stripeInstance.value = await loadStripe(publishableKey.value);
    return stripeInstance.value;
};

const destroyElements = () => {
    if (cardElement.value) {
        cardElement.value.destroy();
        cardElement.value = null;
    }
    if (elements.value) {
        elements.value = null;
    }
};

const cardStyle = () => {
    const isDark = document.documentElement.classList.contains('dark');
    return {
        base: {
            color: isDark ? '#F9FAFB' : '#111827',
            fontFamily: 'Montserrat, sans-serif',
            fontSize: '16px',
            '::placeholder': {
                color: isDark ? '#9CA3AF' : '#6B7280',
            },
        },
    };
};

const mountCardElement = async () => {
    await ensureStripe();
    destroyElements();
    elements.value = stripeInstance.value.elements();
    cardElement.value = elements.value.create('card', {
        style: cardStyle(),
    });
    cardElement.value.mount('#invoice-card-element');
};

let themeObserver = null;

onMounted(() => {
    themeObserver = new MutationObserver(() => {
        if (cardElement.value) {
            cardElement.value.update({
                style: cardStyle(),
            });
        }
    });

    themeObserver.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class'],
    });
});

onBeforeUnmount(() => {
    if (themeObserver) {
        themeObserver.disconnect();
        themeObserver = null;
    }
});

const beginPayment = async (invoice) => {
    selectedInvoice.value = invoice;
    paymentError.value = '';
    paymentSuccess.value = '';
    try {
        await mountCardElement();
        document.getElementById('payment-panel').scrollIntoView({ behavior: 'smooth' });
    } catch (error) {
        paymentError.value = 'Unable to initialize payment form. Please contact support.';
        console.error(error);
    }
};

const payInvoice = async () => {
    if (!selectedInvoice.value || !cardElement.value) {
        paymentError.value = 'Select an invoice to pay.';
        return;
    }
    paymentProcessing.value = true;
    paymentError.value = '';
    paymentSuccess.value = '';

    const amount = Number(selectedInvoice.value.amount);
    const amountInCents = Math.round(amount * 100);

    try {
        const intent = await axios.get(`/fetch-intent/${amountInCents}`);
        const stripe = await ensureStripe();
        const result = await stripe.confirmCardPayment(intent.data.client_secret, {
            payment_method: {
                card: cardElement.value,
                billing_details: {
                    name: page.props?.auth?.user?.name || '',
                    email: page.props?.auth?.user?.email || '',
                },
            },
        });

        if (result.error) {
            throw new Error(result.error.message);
        }

        const paymentIntent = result.paymentIntent;
        await axios.post(route('invoices.storeFromPayment'), {
            invoice_id: selectedInvoice.value.id,
            amount: paymentIntent.amount,
            payment_method: paymentIntent.payment_method_types?.[0] || 'card',
            transaction_id: paymentIntent.id,
            price: selectedInvoice.value.amount,
            billing_month: selectedInvoice.value.billing_month,
            plan: selectedInvoice.value.plan,
            billing_cycle: selectedInvoice.value.billing_cycle,
        });

        paymentSuccess.value = 'Payment successful! Refreshing...';
        destroyElements();
        selectedInvoice.value = null;
        await router.visit(route('billing.portal'), {
            only: ['invoices', 'subscriptionStatus'],
            preserveScroll: true,
            replace: true,
        });
    } catch (error) {
        console.error(error);
        paymentError.value = error.response?.data?.message || error.message || 'Payment failed.';
    } finally {
        paymentProcessing.value = false;
    }
};

watch(
    () => selectedInvoice.value?.id,
    () => {
        paymentError.value = '';
        paymentSuccess.value = '';
    }
);
</script>

<template>
    <Head title="Billing Portal" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                        Billing & Invoices
                    </h2>
                    <p class="text-sm text-white-500 dark:text-gray-400">
                        View your subscription invoices and settle outstanding balances.
                    </p>
                </div>
                <div class="text-sm text-white-600 dark:text-gray-300">
                    <span class="font-semibold">Status:</span>
                    <span class="capitalize">{{ subscriptionStatus?.state || 'pending' }}</span>
                </div>
            </div>
        </template>

        <div class="py-12 space-y-8">
            <div class="mx-auto max-w-6xl sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-dark-bg-secondary shadow sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Invoices</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Unpaid invoices appear with a "Pay Now" option.</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Period</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Plan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Due Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Status</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-[#1f2937] divide-y divide-gray-200 dark:divide-gray-700">
                                <tr v-if="!props.invoices.data.length">
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No invoices yet.
                                    </td>
                                </tr>
                                <tr v-for="invoice in props.invoices.data" :key="invoice.id">
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ invoice.billing_month ? new Date(invoice.billing_month).toLocaleString('default', { month: 'long', year: 'numeric' }) : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100 capitalize">
                                        {{ invoice.plan || 'N/A' }} <span class="text-xs text-gray-500">({{ invoice.billing_cycle || 'monthly' }})</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">${{ Number(invoice.amount).toFixed(2) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">
                                        {{ invoice.due_date ? new Date(invoice.due_date).toLocaleDateString() : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold" :class="statusBadgeClass(invoice.status)">
                                            {{ invoice.status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button
                                            v-if="invoice.status !== 'paid'"
                                            @click="beginPayment(invoice)"
                                            class="px-4 py-2 text-sm font-semibold bg-[#148ad9] text-white rounded-lg hover:bg-[#0f6fb3]"
                                        >
                                            Pay Now
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mx-auto max-w-6xl sm:px-6 lg:px-8" v-if="props.invoices.links">
                <div class="flex flex-col items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                    <div>
                        Showing
                        <span class="font-semibold">{{ props.invoices.from || 0 }}</span>
                        to
                        <span class="font-semibold">{{ props.invoices.to || 0 }}</span>
                        of
                        <span class="font-semibold">{{ props.invoices.total }}</span>
                        results
                    </div>
                    <div class="flex gap-2">
                        <button
                            class="px-4 py-2 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-bg-secondary disabled:opacity-50"
                            :disabled="!props.invoices.prev_page_url"
                            @click="router.get(props.invoices.prev_page_url, {}, { preserveScroll: true })"
                        >
                            Previous
                        </button>
                        <button
                            class="px-4 py-2 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-bg-secondary disabled:opacity-50"
                            :disabled="!props.invoices.next_page_url"
                            @click="router.get(props.invoices.next_page_url, {}, { preserveScroll: true })"
                        >
                            Next
                        </button>
                    </div>
                </div>
            </div>

            <div id="payment-panel" class="mx-auto max-w-3xl sm:px-6 lg:px-8" v-if="selectedInvoice">
                <div class="bg-white dark:bg-dark-bg-secondary shadow sm:rounded-lg p-6 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Pay Invoice – {{ selectedInvoice.billing_month ? new Date(selectedInvoice.billing_month).toLocaleString('default', { month: 'long', year: 'numeric' }) : selectedInvoice.id }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Amount due: <strong>${{ Number(selectedInvoice.amount).toFixed(2) }}</strong>. Enter your card details below.
                    </p>

                    <div class="border border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 bg-gray-50 dark:bg-[#111827] text-gray-900 dark:text-gray-100">
                        
                        <div id="invoice-card-element" class="min-h-[48px]"></div>
                    </div>

                    <div class="space-y-2">
                        <button
                            @click="payInvoice"
                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-[#148ad9] text-white font-semibold rounded-lg hover:bg-[#0f6fb3]"
                            :disabled="paymentProcessing"
                        >
                            <span v-if="paymentProcessing">Processing...</span>
                            <span v-else>Pay ${{ Number(selectedInvoice.amount).toFixed(2) }}</span>
                        </button>
                        <p v-if="paymentError" class="text-sm text-red-500">{{ paymentError }}</p>
                        <p v-if="paymentSuccess" class="text-sm text-green-600">{{ paymentSuccess }}</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

