<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    settings: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    monthly_due_day: props.settings.monthly_due_day ?? 5,
    reminder_offsets: (props.settings.reminder_offsets || []).join(', '),
    grace_period_days: props.settings.grace_period_days ?? 3,
});

const submit = () => {
    form.post(route('subscription.settings.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Subscription Settings" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                        Subscription Settings
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Define due dates, reminders, and grace periods for all subscribers.</p>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-dark-bg-secondary shadow sm:rounded-lg p-6 space-y-6">
                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Monthly Due Day (1-28)
                            </label>
                            <input
                                v-model.number="form.monthly_due_day"
                                type="number"
                                min="1"
                                max="28"
                                class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#1f2937] p-2 text-gray-900 dark:text-gray-100 focus:ring-[#148ad9]"
                            />
                            <p v-if="form.errors.monthly_due_day" class="mt-1 text-sm text-red-500">{{ form.errors.monthly_due_day }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Reminder Offsets (comma separated days after 1st)
                            </label>
                            <input
                                v-model="form.reminder_offsets"
                                type="text"
                                placeholder="e.g. 0,2,4"
                                class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#1f2937] p-2 text-gray-900 dark:text-gray-100 focus:ring-[#148ad9]"
                            />
                            <p class="mt-1 text-xs text-gray-500">Example: "0,2,4" sends reminders on the 1st, 3rd, and 5th days of the month.</p>
                            <p v-if="form.errors.reminder_offsets" class="mt-1 text-sm text-red-500">{{ form.errors.reminder_offsets }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Grace Period (days after due date)
                            </label>
                            <input
                                v-model.number="form.grace_period_days"
                                type="number"
                                min="0"
                                max="15"
                                class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#1f2937] p-2 text-gray-900 dark:text-gray-100 focus:ring-[#148ad9]"
                            />
                            <p v-if="form.errors.grace_period_days" class="mt-1 text-sm text-red-500">{{ form.errors.grace_period_days }}</p>
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <button
                                type="submit"
                                class="inline-flex items-center px-4 py-2 bg-[#148ad9] text-white font-semibold rounded-lg shadow hover:bg-[#0f6fb3] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#148ad9]"
                                :disabled="form.processing"
                            >
                                <span v-if="form.processing">Saving...</span>
                                <span v-else>Save Settings</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

