<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" @click.self="closeModal">
        <div class="bg-white dark:bg-dark-bg-secondary rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-dark-border-primary flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-dark-text-primary">Contact Form Details</h2>
                <button @click="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div class="px-6 py-4 overflow-y-auto flex-1">
                <div v-if="notificationData" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-dark-text-secondary mb-1">Name</label>
                        <p class="text-gray-900 dark:text-dark-text-primary text-base">{{ notificationData.contact_name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-dark-text-secondary mb-1">Email</label>
                        <p class="text-gray-900 dark:text-dark-text-primary text-base break-all">{{ notificationData.contact_email }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-dark-text-secondary mb-1">Message</label>
                        <div class="bg-gray-50 dark:bg-dark-bg-tertiary rounded-md p-4 border border-gray-200 dark:border-dark-border-primary">
                            <p class="text-gray-900 dark:text-dark-text-primary whitespace-pre-wrap">{{ notificationData.contact_message }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-dark-text-secondary mb-1">Received</label>
                        <p class="text-gray-600 dark:text-dark-text-tertiary text-sm">{{ formatDate(notificationData.created_at) }}</p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-dark-border-primary flex justify-end">
                <button 
                    @click="closeModal" 
                    class="px-4 py-2 bg-[#22c55e] hover:bg-[#16a34a] text-white rounded-md transition-colors font-medium"
                >
                    Close
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { formatDistanceToNow } from 'date-fns';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    notificationData: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(['close']);

const closeModal = () => {
    emit('close');
};

const formatDate = (dateString) => {
    if (!dateString) return '';
    try {
        return formatDistanceToNow(new Date(dateString), { addSuffix: true });
    } catch (e) {
        return dateString;
    }
};
</script>

<style scoped>
</style>
