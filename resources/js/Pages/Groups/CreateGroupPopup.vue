<template>
    <TransitionRoot appear :show="show" as="template">
        <Dialog as="div" @close="closeModal" class="relative z-50">
            <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100" leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-black bg-opacity-60" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95" enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
                        <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 p-6 text-left align-middle shadow-xl transition-all">
                            <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm-2 5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                {{ isEditing ? 'Edit Group' : 'Create a New Group' }}
                            </DialogTitle>
                            <form @submit.prevent="submit" class="mt-4 space-y-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Group Name</label>
                                    <input type="text" v-model="form.name" id="name" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50" required>
                                    <div v-if="form.errors.name" class="text-sm text-red-600 mt-1">{{ form.errors.name }}</div>
                                </div>
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                    <textarea v-model="form.description" id="description" rows="3" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50"></textarea>
                                    <div v-if="form.errors.description" class="text-sm text-red-600 mt-1">{{ form.errors.description }}</div>
                                </div>
                                <div>
                                    <label for="profile_picture" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Group Profile Picture</label>
                                    <input type="file" @input="form.profile_picture = $event.target.files[0]" id="profile_picture" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-800 hover:file:bg-gray-200 dark:file:bg-gray-700 dark:file:text-gray-200 dark:hover:file:bg-gray-600">
                                    <progress v-if="form.progress" :value="form.progress.percentage" max="100" class="w-full mt-2">
                                        {{ form.progress.percentage }}%
                                    </progress>
                                    <div v-if="form.errors.profile_picture" class="text-sm text-red-600 mt-1">{{ form.errors.profile_picture }}</div>
                                </div>
                                <div class="mt-6 flex justify-end gap-4">
                                    <button type="button" class="inline-flex justify-center rounded-md border border-transparent bg-gray-100 dark:bg-gray-700 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2" @click="closeModal">
                                        Cancel
                                    </button>
                                    <button type="submit" :disabled="form.processing" class="inline-flex justify-center rounded-md border border-transparent bg-[#22c55e] px-4 py-2 text-sm font-medium text-white hover:bg-[#16a34a] focus:outline-none focus-visible:ring-2 focus-visible:ring-green-500 focus-visible:ring-offset-2 disabled:opacity-50">
                                        {{ isEditing ? 'Save Changes' : 'Create Group' }}
                                    </button>
                                </div>
                            </form>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from '@headlessui/vue';

const props = defineProps({
    show: Boolean,
    group: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(['close']);

const form = useForm({
    name: '',
    description: '',
    profile_picture: null,
});

const imagePreview = ref(null);
const isEditing = computed(() => !!props.group);

const onFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.profile_picture = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const resetForm = () => {
    form.reset();
    imagePreview.value = null;
    form.profile_picture = null;
};

watch(() => props.group, (group) => {
    if (group) {
        form.name = group.name || '';
        form.description = group.description || '';
        imagePreview.value = group.profile_picture_url || null;
    } else {
        resetForm();
    }
}, { immediate: true });

const closeModal = () => {
    emit('close');
    resetForm();
};

const submit = () => {
    const endpoint = isEditing.value ? route('groups.update', props.group.id) : route('groups.store');

    form.transform((data) => {
        const payload = { ...data };
        if (isEditing.value) {
            payload._method = 'put';
        }
        return payload;
    }).post(endpoint, {
        forceFormData: true,
        onSuccess: () => {
            closeModal();
        },
        onFinish: () => {
            form.transform((data) => data);
        },
    });
};
</script>
