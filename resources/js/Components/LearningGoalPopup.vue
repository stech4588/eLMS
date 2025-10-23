<template>
    <Transition name="fade">
        <div v-if="showPopup" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-4" @click.self="dismissPopup">
            <Transition name="pop">
                <div v-if="showPopup" class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-lg w-full">
                    <div class="p-8 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-indigo-100 dark:bg-indigo-900/50 rounded-full flex items-center justify-center">
                            <svg class="w-10 h-10 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Set Your Daily Learning Goal!</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">How much time would you like to dedicate to learning each day?</p>
                    </div>

                    <div class="px-8 pb-8">
                        <div class="space-y-3">
                            <label v-for="option in goalOptions" :key="option.value" 
                                   class="flex items-center p-4 rounded-lg border cursor-pointer transition-all duration-200"
                                   :class="{
                                       'bg-indigo-100 dark:bg-indigo-900/50 border-indigo-500 dark:border-indigo-400 ring-2 ring-indigo-500': selectedGoal === option.value,
                                       'bg-white dark:bg-gray-700/50 border-gray-300 dark:border-gray-600 hover:border-indigo-400 dark:hover:border-indigo-500': selectedGoal !== option.value
                                   }">
                                <input type="radio" :id="'goal_' + option.value" :value="option.value" v-model="selectedGoal" class="hidden">
                                <span class="w-5 h-5 mr-4 border-2 rounded-full flex-shrink-0 transition-colors"
                                      :class="{
                                          'bg-indigo-600 border-indigo-600': selectedGoal === option.value,
                                          'border-gray-400': selectedGoal !== option.value
                                      }"></span>
                                <span class="text-gray-800 dark:text-gray-200 font-semibold">{{ option.label }}</span>
                            </label>
                        </div>

                        <div class="mt-8 grid grid-cols-2 gap-4">
                            <button @click="dismissPopup" type="button" class="w-full px-6 py-3 bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 font-semibold transition-colors">Ask Me Later</button>
                            <button @click="submitGoal" :disabled="!selectedGoal" class="w-full px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-semibold disabled:opacity-50 transition-colors">Set My Goal</button>
                        </div>
                    </div>
                </div>
            </Transition>
        </div>
    </Transition>
</template>

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

<script setup>
import { ref, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const showPopup = ref(false);
const selectedGoal = ref(null);

const goalOptions = [
    { value: 1, label: '1 Hour' },
    { value: 2, label: '2 Hours' },
    { value: 3, label: '3 Hours' },
    { value: 4, label: '3+ Hours' } // Representing 3+ as 4 for simplicity in DB
];

const LEARNING_GOAL_SHOWN_KEY = 'learning_goal_popup_shown_today';

onMounted(() => {
    const today = new Date().toISOString().split('T')[0];
    const lastShownDate = localStorage.getItem(LEARNING_GOAL_SHOWN_KEY);

    if (lastShownDate !== today) {
        showPopup.value = true;
    }
});

const submitGoal = () => {
    if (!selectedGoal.value) return;
    
    router.post(route('learning-goal.store'), {
        daily_learning_goal: selectedGoal.value
    }, {
        preserveScroll: true,
        onSuccess: () => {
            dismissPopup();
            Swal.fire('Goal Set!', 'Your daily learning goal has been saved.', 'success');
        },
        onError: (errors) => {
            console.error("Error saving learning goal:", errors);
            Swal.fire('Error!', 'There was a problem saving your goal.', 'error');
        }
    });
};

const dismissPopup = () => {
    const today = new Date().toISOString().split('T')[0];
    localStorage.setItem(LEARNING_GOAL_SHOWN_KEY, today);
    showPopup.value = false;
};
</script>
