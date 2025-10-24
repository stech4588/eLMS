<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

const form = useForm({
    title: '',
    skills: '',
    description: '',
    contact_phone: '',
    contact_email: '',
});

const submit = () => {
    form.post(route('jobs.store'));
};
</script>

<template>
    <Head title="Post a Job" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Post a New Job</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                        <form @submit.prevent="submit">
                            <div class="space-y-4">
                                <div>
                                    <InputLabel for="title" value="Job Title" />
                                    <TextInput id="title" type="text" class="mt-1 block w-full" v-model="form.title" required autofocus />
                                    <InputError class="mt-2" :message="form.errors.title" />
                                </div>

                                <div>
                                    <InputLabel for="skills" value="Skills (comma-separated)" />
                                    <TextInput id="skills" type="text" class="mt-1 block w-full" v-model="form.skills" required />
                                    <InputError class="mt-2" :message="form.errors.skills" />
                                </div>

                                <div>
                                    <InputLabel for="description" value="Job Description" />
                                    <textarea id="description" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" v-model="form.description" required></textarea>
                                    <InputError class="mt-2" :message="form.errors.description" />
                                </div>

                                <div>
                                    <InputLabel for="contact_phone" value="Contact Phone (Optional)" />
                                    <TextInput id="contact_phone" type="text" class="mt-1 block w-full" v-model="form.contact_phone" />
                                    <InputError class="mt-2" :message="form.errors.contact_phone" />
                                </div>
                                
                                <div>
                                    <InputLabel for="contact_email" value="Contact Email (Optional)" />
                                    <TextInput id="contact_email" type="email" class="mt-1 block w-full" v-model="form.contact_email" />
                                    <InputError class="mt-2" :message="form.errors.contact_email" />
                                </div>
                            </div>

                            <div class="mt-6">
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Post Job
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
