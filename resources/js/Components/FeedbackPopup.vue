<template>
    <Transition name="fade">
        <div v-if="show" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-4" @click.self="close">
            <Transition name="pop">
                <div v-if="show" class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md">
                    <div class="p-8 relative">
                        <button @click="close" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Leave a Review</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">Share your thoughts on this course.</p>
                        
                        <form @submit.prevent="submitReview">
                            <div class="mb-6">
                                <label class="block text-gray-700 dark:text-gray-300 mb-3 font-semibold">Your Rating</label>
                                <div class="flex items-center space-x-1" @mouseleave="hoverRating = 0">
                                    <svg v-for="star in 5" :key="star" 
                                         @click="form.rating = star" 
                                         @mouseover="hoverRating = star"
                                         class="w-10 h-10 cursor-pointer transition-colors duration-200" 
                                         :class="star <= (hoverRating || form.rating) ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-500 hover:text-yellow-300'" 
                                         fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="mb-6">
                                <label for="comment" class="block text-gray-700 dark:text-gray-300 mb-2 font-semibold">Your Comment</label>
                                <textarea id="comment" v-model="form.comment" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition" rows="4" placeholder="Tell us about your experience..."></textarea>
                            </div>
                            <div class="flex justify-end space-x-4">
                                <button type="button" @click="close" class="px-6 py-2.5 bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 font-semibold">Cancel</button>
                                <button type="submit" :disabled="form.processing" class="px-6 py-2.5 bg-[#1C355E] text-white rounded-lg hover:bg-[#254a7a] font-semibold disabled:opacity-50">Submit Review</button>
                            </div>
                        </form>
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
import { useForm } from '@inertiajs/vue3';
import { defineProps, defineEmits, ref } from 'vue';

const props = defineProps({
    show: Boolean,
    course: Object,
});

const emit = defineEmits(['close']);

const form = useForm({
    rating: 0,
    comment: '',
    course_id: props.course ? props.course.id : null,
});

const hoverRating = ref(0);

function submitReview() {
    if (props.course) {
        form.course_id = props.course.id;
        form.post(route('reviews.store'), {
            onSuccess: () => {
                close();
            }
        });
    }
}

function close() {
    emit('close');
}
</script>
