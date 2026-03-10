<template>
    <div class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50" @click.self="$emit('close')">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-lg">
            <div class="flex justify-between items-center pb-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Create Event</h2>
                <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600 dark:text-gray-300 dark:hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form @submit.prevent="submit" class="mt-6 space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Event name</label>
                    <input type="text" id="name" v-model="form.name" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:border-green-500 focus:ring-green-500">
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description (optional)</label>
                    <textarea id="description" v-model="form.description" rows="3" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:border-green-500 focus:ring-green-500"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start date and time</label>
                    <div class="flex gap-4 mt-1">
                        <div class="relative flex-1">
                             <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </span>
                            <input type="date" v-model="form.startDate" class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:border-green-500 focus:ring-green-500 pl-10">
                        </div>
                        <div class="relative flex-1">
                             <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            <input type="time" v-model="form.startTime" class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:border-green-500 focus:ring-green-500 pl-10">
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">End date and time (optional)</label>
                    <div class="flex gap-4 mt-1">
                        <div class="relative flex-1">
                             <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </span>
                            <input type="date" v-model="form.endDate" class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:border-green-500 focus:ring-green-500 pl-10">
                        </div>
                        <div class="relative flex-1">
                             <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            <input type="time" v-model="form.endTime" class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:border-green-500 focus:ring-green-500 pl-10">
                        </div>
                    </div>
                </div>
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location (optional)</label>
                    <div class="relative mt-1">
                         <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                              <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </span>
                        <input type="text" id="location" v-model="form.location" class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:border-green-500 focus:ring-green-500 pl-10">
                    </div>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">WhatsApp call link</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" v-model="form.hasCallLink" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 rounded-full peer peer-focus:ring-4 peer-focus:ring-green-300 dark:peer-focus:ring-green-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#1C355E]"></div>
                    </label>
                </div>
                <div v-if="form.hasCallLink">
                    <label for="call_link" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Call Link</label>
                    <input type="text" id="call_link" v-model="form.call_link" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:border-green-500 focus:ring-green-500">
                </div>
                <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" @click="$emit('close')" :disabled="isLoading" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50">Cancel</button>
                    <button type="submit" :disabled="isLoading" class="ml-3 inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-[#1C355E] border border-transparent rounded-md shadow-sm hover:bg-[#254a7a] disabled:opacity-50">
                        <span v-if="isLoading">Creating...</span>
                        <span v-else>Create Event</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import axios from 'axios';

const props = defineProps({
    groupId: {
        type: Number,
        required: true,
    },
});

const emit = defineEmits(['close', 'event-created']);

const isLoading = ref(false);

const form = ref({
    name: '',
    description: '',
    startDate: '',
    startTime: '',
    endDate: '',
    endTime: '',
    location: '',
    hasCallLink: false,
    call_link: '',
});

const submit = async () => {
    isLoading.value = true;
    const start_time = form.value.startDate && form.value.startTime ? `${form.value.startDate} ${form.value.startTime}` : null;
    const end_time = form.value.endDate && form.value.endTime ? `${form.value.endDate} ${form.value.endTime}` : null;

    try {
        await axios.post(route('api.groups.events.store', props.groupId), {
            name: form.value.name,
            description: form.value.description,
            start_time: start_time,
            end_time: end_time,
            location: form.value.location,
            call_link: form.value.hasCallLink ? form.value.call_link : null,
        });
        emit('event-created');
        window.dispatchEvent(new CustomEvent('new-notification'));
        emit('close');
    } catch (error) {
        console.error('Error creating event:', error);
    } finally {
        isLoading.value = false;
    }
};
</script>
