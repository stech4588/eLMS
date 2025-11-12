<template>
    <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-xl w-full max-w-lg">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Review Details</h3>
                <button @click="close" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-2xl font-bold">&times;</button>
            </div>
            <div v-if="review">
                <div class="mb-4">
                    <h4 class="font-semibold text-lg">Student</h4>
                    <p>{{ review.user.name }} ({{ review.user.email }})</p>
                </div>
                <div class="mb-4">
                    <h4 class="font-semibold text-lg">Rating</h4>
                    <p>{{ review.rating }} / 5</p>
                </div>
                <div class="mb-4">
                    <h4 class="font-semibold text-lg">Date</h4>
                    <p>{{ formatDateTime(review.reviewed_at) }}</p>
                </div>
                <div>
                    <h4 class="font-semibold text-lg">Comment</h4>
                    <p class="whitespace-pre-wrap">{{ review.comment }}</p>
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <button @click="close" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Close</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
    show: Boolean,
    review: Object,
});

const emit = defineEmits(['close']);

function close() {
    emit('close');
}

function formatDateTime(dateTimeString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit', hour12: true };
    return new Date(dateTimeString).toLocaleString(undefined, options);
}
</script>
