<template>
    <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-xl w-full max-w-md">
            <button @click="close" class="absolute top-3 right-3 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-2xl font-bold">&times;</button>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Leave a Review</h3>
            <form @submit.prevent="submitReview">
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Rating</label>
                    <div class="flex items-center">
                        <svg v-for="star in 5" :key="star" @click="form.rating = star" class="w-8 h-8 cursor-pointer" :class="star <= form.rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-500'" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                        </svg>
                    </div>
                </div>
                <div class="mb-6">
                    <label for="comment" class="block text-gray-700 dark:text-gray-300 mb-2">Comment</label>
                    <textarea id="comment" v-model="form.comment" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500" rows="4"></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="button" @click="close" class="px-4 py-2 mr-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Submit</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { defineProps, defineEmits } from 'vue';

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
