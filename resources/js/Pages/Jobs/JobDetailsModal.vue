<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" @click.self="close">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl mx-4 p-6" @click.stop>
            <div class="flex justify-between items-center pb-3 border-b dark:border-gray-700">
                <h3 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ job.title }}</h3>
                <button @click="close" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="mt-4 space-y-4 text-gray-700 dark:text-gray-300 max-h-[70vh] overflow-y-auto pr-4">
                <p><strong class="font-semibold">Posted by:</strong> {{ job.user.name }}</p>
                <div>
                    <strong class="font-semibold">Description:</strong>
                    <p class="mt-1 whitespace-pre-wrap">{{ job.description }}</p>
                </div>
                <div>
                    <strong class="font-semibold">Skills:</strong>
                    <p class="mt-1">{{ job.skills }}</p>
                </div>
                <div>
                    <strong class="font-semibold">Contact:</strong>
                    <p class="mt-1">Email: {{ job.contact_email }} | Phone: {{ job.contact_phone }}</p>
                </div>
                <p class="text-sm text-gray-500">Posted on: {{ new Date(job.created_at).toLocaleDateString() }}</p>
            </div>
            <div class="mt-6 flex justify-end">
                <button @click="close" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                    Close
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    show: Boolean,
    job: Object,
});

const emit = defineEmits(['close']);

const close = () => {
    emit('close');
};
</script>
