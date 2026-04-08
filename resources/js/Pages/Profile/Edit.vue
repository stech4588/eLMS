<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-white"
            >
                Profile
            </h2>
        </template>

        <div class="py-10">
            <div class="mx-auto max-w-5xl space-y-6 sm:px-6 lg:px-8 dark:text-white">
                <div
                    class="bg-white p-4 shadow-sm border border-gray-200 sm:rounded-lg sm:p-8 dark:bg-dark-bg-secondary dark:border-gray-700"
                >
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <div
                    class="bg-white p-4 shadow-sm border border-gray-200 sm:rounded-lg sm:p-8 dark:bg-dark-bg-secondary dark:border-gray-700"
                >
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div
                    class="bg-white p-4 shadow-sm border border-gray-200 sm:rounded-lg sm:p-8 dark:bg-dark-bg-secondary dark:border-gray-700"
                >
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
