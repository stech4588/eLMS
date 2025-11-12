<template>
    <Transition name="fade">
        <div v-if="showPopup && promotion" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-4" @click.self="closePopup">
            <Transition name="pop">
                <div v-if="showPopup" class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full overflow-hidden">
                    <div class="relative">
                        <button @click="closePopup" class="absolute top-3 right-3 text-gray-500 bg-white/50 dark:bg-gray-900/50 backdrop-blur-sm rounded-full p-1 hover:bg-white/75 dark:hover:bg-gray-900/75 z-10 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                        
                        <div v-if="promotion.promotion_type === 'poster'" class="w-full">
                            <img :src="promotion.image_path" alt="Promotion Poster" class="w-full h-60 object-cover">
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ promotion.title }}</h3>
                        
                        <div v-if="promotion.promotion_type === 'text'" class="text-gray-700 dark:text-gray-300 mt-4">
                            <p class="text-lg">{{ promotion.text_content }}</p>
                        </div>
                        
                        <div class="mt-6 flex items-center justify-between text-sm text-gray-600 dark:text-gray-400">
                            <p v-if="promotion.till_date">
                                Offer valid until: <span class="font-semibold">{{ formattedTillDate }}</span>
                            </p>
                            <span v-else></span>
                            <button @click="closePopup" class="ml-auto bg-indigo-600 text-white font-bold py-2 px-5 rounded-lg hover:bg-indigo-700 transition-transform transform hover:scale-105 duration-300">
                                Got it!
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </div>
    </Transition>
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
        if (response.data && Object.keys(response.data).length > 0) {
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

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
.pop-enter-active {
  transition: all 0.3s ease-out;
}
.pop-leave-active {
  transition: all 0.2s ease-in;
}
.pop-enter-from, .pop-leave-to {
  transform: scale(0.95) translateY(20px);
  opacity: 0;
}
</style>
