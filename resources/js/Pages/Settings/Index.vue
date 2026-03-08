<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    user: Object,
});

const form = useForm(
    ['receives_course_completion_emails', 'receives_course_reminder_emails', 'receives_marketing_emails', 'receives_motivational_quote_emails', 'receives_new_course_notification_emails', 'receives_prompt_generated_emails', 'receives_wellness_checkin_emails']
    .reduce((acc, key) => {
        const setting = props.user.email_notification_settings.find(s => s.key === key);
        acc[key] = setting ? Boolean(setting.value) : true;
        return acc;
    }, {})
);

const submit = () => {
    form.post(route('settings.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Settings" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-white dark:text-gray-200 leading-tight">Settings</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                        <div v-if="user.role_id === 3">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Email Notifications</h3>
                            <form @submit.prevent="submit">
                                <div class="space-y-4">
                                    <div>
                                        <label class="flex items-center">
                                            <input type="checkbox" v-model="form.receives_course_completion_emails" class="form-checkbox h-5 w-5 text-green-600">
                                            <span class="ml-2 text-gray-700 dark:text-gray-300">Receive Course Completion Emails</span>
                                        </label>
                                    </div>
                                    <div>
                                        <label class="flex items-center">
                                            <input type="checkbox" v-model="form.receives_course_reminder_emails" class="form-checkbox h-5 w-5 text-green-600">
                                            <span class="ml-2 text-gray-700 dark:text-gray-300">Receive Course Reminder Emails</span>
                                        </label>
                                    </div>
                                    <div>
                                        <label class="flex items-center">
                                            <input type="checkbox" v-model="form.receives_marketing_emails" class="form-checkbox h-5 w-5 text-green-600">
                                            <span class="ml-2 text-gray-700 dark:text-gray-300">Receive Marketing Emails</span>
                                        </label>
                                    </div>
                                    <div>
                                        <label class="flex items-center">
                                            <input type="checkbox" v-model="form.receives_motivational_quote_emails" class="form-checkbox h-5 w-5 text-green-600">
                                            <span class="ml-2 text-gray-700 dark:text-gray-300">Receive Motivational Quote Emails</span>
                                        </label>
                                    </div>
                                    <div>
                                        <label class="flex items-center">
                                            <input type="checkbox" v-model="form.receives_new_course_notification_emails" class="form-checkbox h-5 w-5 text-green-600">
                                            <span class="ml-2 text-gray-700 dark:text-gray-300">Receive New Course Notification Emails</span>
                                        </label>
                                    </div>
                                    <div>
                                        <label class="flex items-center">
                                            <input type="checkbox" v-model="form.receives_prompt_generated_emails" class="form-checkbox h-5 w-5 text-green-600">
                                            <span class="ml-2 text-gray-700 dark:text-gray-300">Receive AI Prompt Generated Emails</span>
                                        </label>
                                    </div>
                                    <div>
                                        <label class="flex items-center">
                                            <input type="checkbox" v-model="form.receives_wellness_checkin_emails" class="form-checkbox h-5 w-5 text-green-600">
                                            <span class="ml-2 text-gray-700 dark:text-gray-300">Receive Wellness Check-in Emails</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="mt-6">
                                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                        Save
                                    </PrimaryButton>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
