<template>
    <div class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50" @click.self="$emit('close')">
        <div v-if="event" class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-lg">
            <div class="flex justify-between items-center pb-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="bg-gray-100 dark:bg-gray-700 p-2 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ event.name }}</h2>
                </div>
                <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600 dark:text-gray-300 dark:hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="mt-6 space-y-4 text-sm text-gray-700 dark:text-gray-300">
                <p v-if="event.description" class="text-base">{{ event.description }}</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div>
                        <strong class="block font-medium text-gray-900 dark:text-white">Starts:</strong>
                        <span>{{ formatEventTime(event.start_time) }}</span>
                    </div>
                    <div v-if="event.end_time">
                        <strong class="block font-medium text-gray-900 dark:text-white">Ends:</strong>
                        <span>{{ formatEventTime(event.end_time) }}</span>
                    </div>
                    <div v-if="event.location" class="md:col-span-2">
                        <strong class="block font-medium text-gray-900 dark:text-white">Location:</strong>
                        <span>{{ event.location }}</span>
                    </div>
                    <div v-if="event.call_link" class="md:col-span-2">
                        <strong class="block font-medium text-gray-900 dark:text-white">Call Link:</strong>
                        <a :href="event.call_link" target="_blank" class="text-gray-900 dark:text-gray-200 hover:underline break-all">{{ event.call_link }}</a>
                    </div>
                </div>
            </div>
            <div class="flex justify-end pt-4 mt-6 border-t border-gray-200 dark:border-gray-700">
                <button type="button" @click="$emit('close')" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700">Close</button>
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    event: {
        type: Object,
        required: true,
    },
});

defineEmits(['close']);

const formatEventTime = (timestamp) => {
    if (!timestamp) return '';
    return new Date(timestamp).toLocaleString([], { dateStyle: 'full', timeStyle: 'short' });
};
</script>
