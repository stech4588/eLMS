<template>
    <AuthenticatedLayout>
        <div class=" min-h-screen flex flex-col items-center py-8">
            <!-- <div class="text-3xl font-bold mb-8">Logo</div> -->
            <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
                <h1 class="text-2xl font-semibold mb-2">Checkout</h1>
                <p class="text-gray-600 mb-6">All transactions are secure and encrypted</p>

                <div class="flex justify-between items-center mb-4">
                    <div>
                        <p class="text-lg font-medium">Monthly</p>
                        <p class="text-sm text-gray-500">$29.99/mo</p>
                    </div>
                    <p class="text-lg font-semibold">Total: $30</p>
                </div>

                <button class="w-full bg-yellow-400 text-blue-800 font-semibold py-3 rounded-lg mb-4 flex items-center justify-center">
                    <span class="text-xl italic font-bold mr-1">P</span> PayPal
                </button>

                <div class="flex items-center my-4">
                    <hr class="w-full border-gray-300" />
                    <span class="px-2 text-gray-500 text-sm" style="width: 600px; justify-content: center; align-items: center; display: flex;">or Pay with Card</span>
                    <hr class="w-full border-gray-300" />
                </div>

                <form>
                    <div class="mb-4">
                        <label for="card-name" class="block text-sm font-medium text-gray-700 mb-1">Name on card</label>
                        <input type="text" id="card-name" placeholder="Name on card" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#76c3f1] focus:border-[#76c3f1]">
                    </div>

                    <div class="mb-4">
                        <label for="card-number" class="block text-sm font-medium text-gray-700 mb-1">Card Number</label>
                        <input type="text" id="card-number" placeholder="1234 1234 1234 1234" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#76c3f1] focus:border-[#76c3f1]">
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label for="expiry-date" class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                            <input type="text" id="expiry-date" placeholder="MM / YY" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#76c3f1] focus:border-[#76c3f1]">
                        </div>
                        <div>
                            <label for="cvc" class="block text-sm font-medium text-gray-700 mb-1">CVC</label>
                            <input type="text" id="cvc" placeholder="CVC" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#76c3f1] focus:border-[#76c3f1]">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-[#148ad9] text-white font-semibold py-3 rounded-lg hover:bg-[#76c3f1]">
                        Pay $30.00
                    </button>
                </form>
                <p class="text-xs text-gray-500 mt-6">
                   We’re looking for passionate educators and industry experts. At MBM Learning, your knowledge matters. Whether you’re a seasoned professional or an emerging leader in your field, we provide the tools and support to help you succeed.
                </p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<style>
@media (min-width: 770px) {
    /* .main_sidebar{
        display: none;
    } */
}
</style>