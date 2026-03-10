<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    user: Object,
});

const form = useForm({
    name: props.user.name,
    email: props.user.email,
});

const submit = () => {
    form.put(route('users.update', props.user.id), {
        onSuccess: () => {
            // Redirect to user listing or show success message
            // router.visit(route('users.index')); // Example redirect
        },
    });
};
</script>

<template>
    <Head :title="`Edit User - ${user.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 >
                Edit User: {{ user.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white dark:bg-dark-bg-secondary shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white dark:bg-dark-bg-secondary border-b border-gray-200 dark:border-gray-700">
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                                <input type="text" v-model="form.name" id="name" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                                <div v-if="form.errors.name" class="text-sm text-red-600 dark:text-red-400">{{ form.errors.name }}</div>
                            </div>

                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                <input type="email" v-model="form.email" id="email" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                                <div v-if="form.errors.email" class="text-sm text-red-600 dark:text-red-400">{{ form.errors.email }}</div>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <Link :href="route('users.index')" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300 underline mr-4">
                                    Cancel
                                </Link>
                                <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-[#148ad9] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template> 