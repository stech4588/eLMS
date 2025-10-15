<template>
    <div v-if="showPopup && promotion" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-700 rounded-lg shadow-xl max-w-lg w-full p-6 relative">
            <button @click="closePopup" class="absolute top-3 right-3 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-2xl font-bold">
                &times;
            </button>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">{{ promotion.title }}</h3>

            <div v-if="promotion.promotion_type === 'text'" class="text-gray-700 dark:text-gray-300">
                <p class="text-lg">{{ promotion.text_content }}</p>
            </div>

            <div v-else-if="promotion.promotion_type === 'poster'" class="mt-4">
                <img :src="promotion.image_path" alt="Promotion Poster" class="w-full h-auto rounded-md object-cover">
            </div>

            <div class="mt-6 text-sm text-gray-600 dark:text-gray-400">
                <p v-if="promotion.till_date">Offer valid until: {{ formattedTillDate }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import moment from 'moment';

const SHOW_PROMOTION_KEY = 'promotion_shown_this_session';
const showPopup = ref(false);
const promotion = ref(null);

const fetchPromotion = async () => {
    try {
        const response = await axios.get(route('promotions.randomActive'));
        if (response.data) {
            promotion.value = response.data;
            showPopup.value = true;
            sessionStorage.setItem(SHOW_PROMOTION_KEY, 'true');
        }
    } catch (error) {
        console.error('Error fetching promotion:', error);
    }
};

const closePopup = () => {
    showPopup.value = false;
    // sessionStorage.setItem(SHOW_PROMOTION_KEY, 'true'); // Already set on fetch, but can be set here too if needed
};

const formattedTillDate = computed(() => {
    return promotion.value && promotion.value.till_date
        ? moment(promotion.value.till_date).format('MMMM D, YYYY')
        : '';
});

onMounted(() => {
    const hasPromotionBeenShown = sessionStorage.getItem(SHOW_PROMOTION_KEY);

    if (!hasPromotionBeenShown) {
        fetchPromotion();
    }
});
</script>
