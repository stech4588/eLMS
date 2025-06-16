<template>
    <AuthenticatedLayout>
        <div class="bg-[#97d5ff] min-h-screen py-12 text-black">
            <div class="container mx-auto px-4">
                <h1 class="text-4xl font-bold text-center mb-8">Your cart</h1>
                <div class="flex flex-col lg:flex-row gap-8">

                    <!-- Left Side -->
                    <div class="lg:w-2/3">
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <img :src="course.videos[0].thumbnail_url" alt="Course Image" class=" h-48 object-cover rounded-lg mb-4" style="width: 40%;">
                            <h2 class="text-2xl font-semibold mb-4">{{ course.title }}</h2>
                            <p class="text-gray-600 mb-4">{{ course.description }}</p>
                            
                            <div v-if="course.videos && course.videos.length > 0">
                                <h3 class="text-xl font-semibold mb-2">Topics included:</h3>
                                <ul class="list-disc list-inside text-gray-600">
                                    <li v-for="video in course.videos" :key="video.id">{{ video.title }}</li>
                                </ul>
                            </div>
                             <div class="mt-4">
                                <p class="text-lg"><span class="font-semibold">Type:</span> {{ course.course_type ? course.course_type.name : 'N/A' }}</p>
                            </div>

                            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mt-6 rounded-md">
                                <p><span class="font-bold">Great news!</span> Your course includes lifetime access and all future updates for free.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side -->
                    <div class="lg:w-1/3">
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h2 class="text-2xl font-semibold mb-4">Order summary</h2>
                            
                            <div class="flex justify-between items-center mb-4 pb-4 border-b">
                                <span class="text-gray-600">{{ course.title }}</span>
                                <span class="font-semibold">${{ course.price }}</span>
                            </div>

                            <div class="flex justify-between items-center mb-4">
                                <span class="text-gray-600">Taxes</span>
                                <span class="text-gray-500 text-sm">Calculated at next step</span>
                            </div>

                            <div class="flex justify-between items-center font-bold text-xl my-4 pt-4 border-t">
                                <span>Subtotal</span>
                                <span>${{ course.price }}</span>
                            </div>

                            <div class="mb-6">
                                 <div id="payment-element"></div>
                            </div>
                           

                             <form @submit.prevent="checkout">
                                <button type="submit" class="w-full bg-[#3b82f6] text-white font-semibold py-3 rounded-lg hover:bg-[#5998ff] transition-colors" :disabled="paymentProcessing || !course.price">
                                    <span v-if="paymentProcessing">Processing...</span>
                                    <span v-else>Continue</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
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
    course: Object
});

const pk = 'pk_test_51RZFkqPF8BzoPAVhNu7Lpqi40TnbQETCQGyiisJyPfztajSTaZdBOfXem930W375gIMhjyaLK9VAJOMk4mUb9NNc009zifzCDs';
let stripe = null;
let elements = null;
const paymentProcessing = ref(false);

const loadStripeDate = async () => {
    if (!props.course.price) return;
    stripe = await loadStripe(pk);
    try {
        // const amountInCents = Math.round(props.course.price * 100);
        const response = await axios.get(`/fetch-intent/${props.course.price}`);
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
                    course_id: props.course.id,
                    price: props.course.price,
                });
                console.log("Invoice created successfully.");
                router.visit(route('courses.show', { course: props.course.id }));
            } catch (invoiceError) {
                console.error("Error creating invoice:", invoiceError);
            }
        }
    }

    paymentProcessing.value = false;
};
</script>

<style>

</style>