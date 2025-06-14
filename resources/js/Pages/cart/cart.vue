<template>
    <AuthenticatedLayout>
        <div class=" min-h-screen flex flex-col items-center py-8">
            <!-- <div class="text-3xl font-bold mb-8">Logo</div> -->
            <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
                <h1 class="text-2xl font-semibold mb-2">Checkout</h1>
                <p class="text-gray-600 mb-6">All transactions are secure and encrypted</p>

                <div class="flex justify-between items-center mb-4">
                    <div>
                        <p class="text-lg font-medium">{{ title }}</p>
                    </div>
                    <p class="text-lg font-semibold">Total: ${{ price }}</p>
                </div>

                <button class="w-full bg-yellow-400 text-blue-800 font-semibold py-3 rounded-lg mb-4 flex items-center justify-center">
                    <span class="text-xl italic font-bold mr-1">P</span> PayPal
                </button>

                <div class="flex items-center my-4">
                    <hr class="w-full border-gray-300" />
                    <span class="px-2 text-gray-500 text-sm" style="width: 600px; justify-content: center; align-items: center; display: flex;">or Pay with Card</span>
                    <hr class="w-full border-gray-300" />
                </div>

                <form @submit.prevent="checkout">
                    <div class="mb-6">
                        <div id="payment-element"></div>
                    </div>

                    <button type="submit" class="w-full bg-[#148ad9] text-white font-semibold py-3 rounded-lg hover:bg-[#76c3f1]" :disabled="paymentProcessing || !price">
                        <span v-if="paymentProcessing">Processing...</span>
                        <span v-else>Pay ${{ price }}</span>
                    </button>
                </form>
                <p class="text-xs text-gray-500 mt-6">
                   We're looking for passionate educators and industry experts. At MBM Learning, your knowledge matters. Whether you're a seasoned professional or an emerging leader in your field, we provide the tools and support to help you succeed.
                </p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { onMounted, ref } from 'vue';
import { loadStripe } from '@stripe/stripe-js';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    course_id: [String, Number],
    title: String,
    price: [String, Number]
});

const pk = 'pk_test_51RZFkqPF8BzoPAVhNu7Lpqi40TnbQETCQGyiisJyPfztajSTaZdBOfXem930W375gIMhjyaLK9VAJOMk4mUb9NNc009zifzCDs';
let stripe = null;
let elements = null;
const paymentProcessing = ref(false);

const loadStripeDate = async () => {
    if (!props.price) return;
    stripe = await loadStripe(pk);
    try {
        // const amountInCents = Math.round(props.price * 100);
        const response = await axios.get(`/fetch-intent/${props.price}`);
        elements = stripe.elements({ clientSecret: response.data.client_secret });
        const paymentElement = elements.create('payment');
        paymentElement.mount('#payment-element');
    } catch (error) {
        console.error("Error fetching payment intent:", error);
    }
};

onMounted(() => {
    loadStripeDate();
});

const checkout = async () => {
    if (paymentProcessing.value || !stripe || !elements) {
        return;
    }
    paymentProcessing.value = true;

    const result = await stripe.confirmPayment({
        elements,
        confirmParams: {
            return_url: window.location.href, // Or a specific success URL
        },
        redirect: 'if_required'
    });

    if (result.error) {
        console.error(result.error.message);
        paymentProcessing.value = false;
    } else {
        if (result.paymentIntent.status === 'succeeded') {
            try {
                await axios.post('/invoices/create-from-payment', {
                    amount: result.paymentIntent.amount,
                    payment_method: result.paymentIntent.payment_method_types[0],
                    transaction_id: result.paymentIntent.id,
                    course_id: props.course_id,
                    price: props.price,
                });
                console.log("Invoice created successfully.");
                router.visit(route('courses.show', { course: props.course_id }));
            } catch (invoiceError) {
                console.error("Error creating invoice:", invoiceError);
            }
        }
    }

    paymentProcessing.value = false;
};
</script>

<style>
@media (min-width: 770px) {
    /* .main_sidebar{
        display: none;
    } */
}
</style>