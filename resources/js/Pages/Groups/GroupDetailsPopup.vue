<template>
    <TransitionRoot appear :show="show" as="template">
        <Dialog as="div" @close="$emit('close')" class="relative z-50">
            <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100" leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-black bg-opacity-60" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95" enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
                        <DialogPanel v-if="group" class="w-full max-w-lg transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 p-6 text-left align-middle shadow-xl transition-all">
                            <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 flex items-center gap-4">
                                <img :src="group.profile_picture_url" alt="Group Avatar" class="w-12 h-12 rounded-full object-cover">
                                <span>{{ group.name }}</span>
                            </DialogTitle>
                            
                            <div class="mt-4">
                                <p class="text-sm text-gray-600 dark:text-gray-300">{{ group.description }}</p>
                            </div>

                            <div class="mt-6">
                                <h4 class="font-medium text-gray-800 dark:text-gray-200">Members ({{ group.members.length }})</h4>
                                <ul class="mt-3 max-h-60 overflow-y-auto space-y-3 pr-2">
                                    <li v-for="member in group.members" :key="member.id" class="flex items-center justify-between p-2 rounded-md bg-gray-50 dark:bg-gray-700/50">
                                        <div class="flex items-center gap-3">
                                            <img :src="member.profile_photo_url" alt="Member Avatar" class="w-8 h-8 rounded-full object-cover">
                                            <span class="text-sm font-medium text-gray-800 dark:text-gray-200">
                                                {{ member.name }}
                                                <span v-if="member.id === group.creator.id" class="ml-1 text-xs font-semibold text-gray-900 dark:text-gray-200">(Admin)</span>
                                            </span>
                                        </div>
                                        <span class="text-xs font-semibold uppercase px-2 py-1 rounded-full"
                                              :class="member.pivot.role === 'admin' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' : 'bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-gray-300'">
                                            {{ member.pivot.role }}
                                        </span>
                                    </li>
                                </ul>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button type="button" class="inline-flex justify-center rounded-md border border-transparent bg-gray-100 dark:bg-gray-700 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-500 focus-visible:ring-offset-2" @click="$emit('close')">
                                    Close
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from '@headlessui/vue';

defineProps({
    show: Boolean,
    group: Object,
});

defineEmits(['close']);
</script>
