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
                <div v-if="!isEditing">
                    <strong class="font-semibold">Description:</strong>
                    <p class="mt-1 whitespace-pre-wrap">{{ job.description }}</p>
                </div>
                <div v-else>
                    <strong class="font-semibold">Description:</strong>
                    <textarea v-model="form.description" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md"></textarea>
                </div>
                <div v-if="!isEditing">
                    <strong class="font-semibold">Skills:</strong>
                    <p class="mt-1">{{ job.skills }}</p>
                </div>
                <div v-else>
                    <strong class="font-semibold">Skills (comma-separated):</strong>
                    <input v-model="form.skills" type="text" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md" />
                </div>
                <div v-if="!isEditing">
                    <strong class="font-semibold">Contact:</strong>
                    <p class="mt-1">Email: {{ job.contact_email }} | Phone: {{ job.contact_phone }}</p>
                </div>
                <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <strong class="font-semibold">Contact Email:</strong>
                        <input v-model="form.contact_email" type="email" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md" />
                    </div>
                    <div>
                        <strong class="font-semibold">Contact Phone:</strong>
                        <input v-model="form.contact_phone" type="text" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md" />
                    </div>
                </div>
                <div v-if="!isEditing">
                    <strong class="font-semibold">Apply URL:</strong>
                    <p class="mt-1 break-all">{{ job.apply_url }}</p>
                </div>
                <div v-else>
                    <strong class="font-semibold">Apply URL:</strong>
                    <input v-model="form.apply_url" type="url" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md" />
                </div>
                <p class="text-sm text-gray-500">Posted on: {{ new Date(job.created_at).toLocaleDateString() }}</p>
            </div>
            <div class="mt-6 flex justify-between items-center">
                <div>
                    <a :href="job.apply_url" target="_blank" rel="noopener noreferrer" class="mr-3 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Open Job Link
                    </a>
                </div>
                <div class="flex items-center">
                    <template v-if="isOwner">
                        <template v-if="!isEditing">
                            <button @click="startEdit" class="mr-3 px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">Edit</button>
                            <button @click="confirmDelete" class="mr-3 px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Delete</button>
                        </template>
                        <template v-else>
                            <button @click="saveEdit" class="mr-3 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700" :disabled="saving">Save</button>
                            <button @click="cancelEdit" class="mr-3 px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-600" :disabled="saving">Cancel</button>
                        </template>
                    </template>
                    <button @click="close" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, reactive, ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
const props = defineProps({
    show: Boolean,
    job: Object,
    authUserId: Number,
    initialMode: {
        type: String,
        default: 'view',
    },
});

const emit = defineEmits(['close']);

const isEditing = ref(false);
const saving = ref(false);

const isOwner = computed(() => {
    if (!props.job || !props.authUserId) return false;
    const job = props.job;
    const authId = props.authUserId;
    return (job.user && job.user.id === authId) || (job.user_id === authId);
});

const form = reactive({
    title: '',
    skills: '',
    description: '',
    apply_url: '',
    contact_email: '',
    contact_phone: '',
});

watch(() => props.job, (j) => {
    if (j) {
        form.title = j.title || '';
        form.skills = j.skills || '';
        form.description = j.description || '';
        form.apply_url = j.apply_url || '';
        form.contact_email = j.contact_email || '';
        form.contact_phone = j.contact_phone || '';
    }
});

watch(() => props.show, (show) => {
    if (show) {
        if (props.initialMode === 'edit' && isOwner.value) {
            isEditing.value = true;
        } else {
            isEditing.value = false;
        }
    } else {
        isEditing.value = false;
    }
});

const startEdit = () => {
    isEditing.value = true;
};

const cancelEdit = () => {
    isEditing.value = false;
    // reset to job values
    const j = props.job;
    form.title = j.title || '';
    form.skills = j.skills || '';
    form.description = j.description || '';
    form.apply_url = j.apply_url || '';
    form.contact_email = j.contact_email || '';
    form.contact_phone = j.contact_phone || '';
};

const saveEdit = () => {
    if (!props.job) return;
    saving.value = true;
    router.put(route('jobs.update', props.job.id), { ...form }, {
        onFinish: () => { saving.value = false; },
        onSuccess: () => {
            isEditing.value = false;
            emit('close');
        },
    });
};

const confirmDelete = () => {
    if (!props.job) return;
    if (confirm('Are you sure you want to delete this job?')) {
        router.delete(route('jobs.destroy', props.job.id), {
            onSuccess: () => {
                emit('close');
            },
        });
    }
};

const close = () => {
    emit('close');
};
</script>
