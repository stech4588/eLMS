<template>
    <AuthenticatedLayout>
        <div class="bg-[#97d5ff] min-h-screen py-12 text-black dark:bg-dark-bg-primary dark:text-white">
            <div class="container mx-auto px-4">
                <!-- Promo Banner -->
                <div class="mb-8 overflow-hidden rounded-xl bg-gradient-to-r from-[#9d85ff] to-[#6a6cff] p-1 text-white relative">
                    <div class="flex items-center justify-between p-4">
                        <div class="flex items-center gap-6">
                            <!-- Countdown Box -->
                            <div class="rounded-lg bg-[#6e58e0] p-3 text-center shadow-lg">
                                <div class="text-xs font-bold uppercase tracking-wider">Don't miss out!</div>
                                <div class="mt-1 font-mono text-2xl tracking-wider">
                                    <span>{{ formattedTime.days }}</span>:
                                    <span>{{ formattedTime.hours }}</span>:
                                    <span>{{ formattedTime.minutes }}</span>:
                                    <span>{{ formattedTime.seconds }}</span>
                                </div>
                            </div>
                            <!-- Promo Text -->
                            <div class="text-lg font-bold">
                                + 2 months free with a 48-month plan
                            </div>
                        </div>
                    </div>
                    <!-- Percentage Symbol -->
                    <div class="absolute right-0 top-0 flex h-full items-center pr-6 text-8xl font-black text-white opacity-20 transform -translate-y-1">
                        %
                    </div>
                </div>
                <h1 class="text-4xl font-bold text-center mb-8">Your cart</h1>
                <div class="flex flex-col lg:flex-row gap-8">

                    <!-- Left Side -->
                    <div class="lg:w-2/3">
                        <div class="bg-white rounded-lg shadow-md p-6 flex gap-4 cart_right_container dark:bg-dark-bg-secondary">
                            <div>
                                <img :src="course.videos[0].thumbnail_url" alt="Course Image"
                                    class=" h-45 object-cover rounded-lg mb-4" style="width: 512px;">
                            </div>
                            <div>
                                <h2 class="text-2xl font-semibold mb-4">{{ course.title }}</h2>
                                <p class="text-gray-600 mb-4 dark:text-white">{{ truncatedDescription }}</p>
                            

                                <div v-if="course.videos && course.videos.length > 0">
                                    <h3 class="text-xl font-semibold mb-2">Topics included:</h3>
                                    <ul class="list-disc list-inside text-gray-600 dark:text-white">
                                        <li v-for="video in visibleVideos" :key="video.id">{{ video.title }}</li>
                                    </ul>
                                </div>
                                <div class="mt-4">
                                    <p class="text-lg"><span class="font-semibold">Type:</span> {{ course.course_type ?
                                        course.course_type.name : 'N/A' }}</p>
                                </div>

                                <div
                                    class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mt-6 rounded-md">
                                    <p><span class="font-bold">Great news!</span> Your course includes lifetime access
                                        and all future updates for free.</p>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Right Side -->
                    <div class="lg:w-1/3">
                        <div class="bg-white rounded-lg shadow-md p-6 dark:bg-dark-bg-secondary">
                            <h2 class="text-2xl font-semibold mb-4">Order summary</h2>

                            <div class="flex justify-between items-center mb-4 pb-4 border-b">
                                <span class="text-gray-600 dark:text-white">{{ course.title }}</span>
                                <span class="font-semibold">${{ course.price }}</span>
                            </div>

                            <!-- <div class="flex justify-between items-center mb-4">
                                <span class="text-gray-600">Taxes</span>
                                <span class="text-gray-500 text-sm">Calculated at next step</span>
                            </div> -->

                            <div class="flex justify-between items-center font-bold text-xl my-4 pt-4 ">
                                <span>Subtotal</span>
                                <span>${{ course.price }}</span>
                            </div>

                            <div class="mb-6">
                                <div id="payment-element"></div>
                            </div>


                            <form @submit.prevent="checkout">
                                <button type="submit"
                                    class="w-full bg-[#3b82f6] text-white font-semibold py-3 rounded-lg hover:bg-[#5998ff] transition-colors"
                                    :disabled="paymentProcessing || !course.price">
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
import { onMounted, ref, onUnmounted, computed } from 'vue';
import { loadStripe } from '@stripe/stripe-js';
import { router, usePage } from '@inertiajs/vue3';

const countdown = ref({
    days: 2,
    hours: 14,
    minutes: 7,
    seconds: 2
});

const targetDate = new Date();
targetDate.setDate(targetDate.getDate() + countdown.value.days);
targetDate.setHours(targetDate.getHours() + countdown.value.hours);
targetDate.setMinutes(targetDate.getMinutes() + countdown.value.minutes);
targetDate.setSeconds(targetDate.getSeconds() + countdown.value.seconds);

let intervalId = null;

const updateCountdown = () => {
    const now = new Date();
    const difference = targetDate.getTime() - now.getTime();

    if (difference <= 0) {
        countdown.value = { days: 0, hours: 0, minutes: 0, seconds: 0 };
        if (intervalId) clearInterval(intervalId);
        return;
    }

    countdown.value.days = Math.floor(difference / (1000 * 60 * 60 * 24));
    countdown.value.hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    countdown.value.minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
    countdown.value.seconds = Math.floor((difference % (1000 * 60)) / 1000);
};

const pad = (num) => num.toString().padStart(2, '0');

const formattedTime = computed(() => ({
    days: pad(countdown.value.days),
    hours: pad(countdown.value.hours),
    minutes: pad(countdown.value.minutes),
    seconds: pad(countdown.value.seconds),
}));

const props = defineProps({
    course: Object
});

const page = usePage();
const stripePublishableKey = computed(() => {
    return page.props && page.props.stripe && page.props.stripe.key
        ? page.props.stripe.key
        : null;
});

const showFullDescription = ref(false);

const truncatedDescription = computed(() => {
    const description = props.course.description;
    if (description && description.length > 300 && !showFullDescription.value) {
        return description.substring(0, 300) + '...';
    }
    return description;
});

const visibleVideos = computed(() => {
    if (props.course.videos) {
        return props.course.videos.slice(0, 5);
    }
    return [];
});

let stripe = null;
let elements = null;
const paymentProcessing = ref(false);

const loadStripeDate = async () => {
    if (!props.course.price) return;
    const publishableKey = stripePublishableKey.value;
    if (!publishableKey) {
        console.error('Stripe publishable key is missing.');
        return;
    }
    stripe = await loadStripe(publishableKey);
    const price = Number(props.course.price);
    const amountInCents = Number.isNaN(price) ? 0 : Math.round(price * 100);
    if (amountInCents <= 0) {
        console.error('Invalid course price supplied for payment intent.');
        return;
    }
    try {
        const response = await axios.get(`/fetch-intent/${amountInCents}`);
        
        // Check if dark mode is enabled
        const isDarkMode = document.documentElement.classList.contains('dark');
        
        elements = stripe.elements({ 
            clientSecret: response.data.client_secret,
            appearance: {
                theme: isDarkMode ? 'night' : 'stripe',
                variables: {
                    colorPrimary: isDarkMode ? '#3b82f6' : '#2563eb',
                    colorBackground: isDarkMode ? '#2d2d2d' : '#ffffff',
                    colorText: isDarkMode ? '#ffffff' : '#1f2937',
                    colorDanger: '#ef4444',
                    fontFamily: 'system-ui, sans-serif',
                    spacingUnit: '4px',
                    borderRadius: '8px',
                    colorIcon: isDarkMode ? '#9ca3af' : '#6b7280',
                    colorIconHover: isDarkMode ? '#ffffff' : '#1f2937',
                    colorTextPlaceholder: isDarkMode ? '#9ca3af' : '#6b7280',
                    colorTextSecondary: isDarkMode ? '#d1d5db' : '#4b5563',
                    colorBorder: isDarkMode ? '#374151' : '#e5e7eb',
                    colorBorderFocus: isDarkMode ? '#3b82f6' : '#2563eb',
                }
            }
        });
        const paymentElement = elements.create('payment');
        paymentElement.mount('#payment-element');
    } catch (error) {
        console.error("Error fetching payment intent:", error);
    }
};

onMounted(() => {
    updateCountdown();
    intervalId = setInterval(updateCountdown, 1000);
    loadStripeDate();
    
    // Watch for theme changes
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.attributeName === 'class') {
                // Only update the appearance when theme changes
                if (elements) {
                    const isDarkMode = document.documentElement.classList.contains('dark');
                    elements.update({
                        appearance: {
                            theme: isDarkMode ? 'night' : 'stripe',
                            variables: {
                                colorPrimary: isDarkMode ? '#3b82f6' : '#2563eb',
                                colorBackground: isDarkMode ? '#2d2d2d' : '#ffffff',
                                colorText: isDarkMode ? '#ffffff' : '#1f2937',
                                colorDanger: '#ef4444',
                                fontFamily: 'system-ui, sans-serif',
                                spacingUnit: '4px',
                                borderRadius: '8px',
                                colorIcon: isDarkMode ? '#9ca3af' : '#6b7280',
                                colorIconHover: isDarkMode ? '#ffffff' : '#1f2937',
                                colorTextPlaceholder: isDarkMode ? '#9ca3af' : '#6b7280',
                                colorTextSecondary: isDarkMode ? '#d1d5db' : '#4b5563',
                                colorBorder: isDarkMode ? '#374151' : '#e5e7eb',
                                colorBorderFocus: isDarkMode ? '#3b82f6' : '#2563eb',
                            }
                        }
                    });
                }
            }
        });
    });

    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class']
    });
});

onUnmounted(() => {
    if (intervalId) clearInterval(intervalId);
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
@media (max-width: 425px) {
    .cart_right_container {
        flex-direction: column !important;
        width: 100% !important;

    }
}
</style>