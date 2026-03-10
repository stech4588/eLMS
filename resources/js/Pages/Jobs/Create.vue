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
    apply_url: '',
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
            <h2 class="font-semibold text-xl text-white dark:text-gray-200 leading-tight">Post a New Job</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-[#0b1624] border border-gray-200 dark:border-[#1f2d40] overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 sm:p-8 bg-white dark:bg-[#1A2C38] border-b border-gray-200 dark:border-[#1f2d40] transition-colors duration-200">
                        <form @submit.prevent="submit">
                            <div class="space-y-5">
                                <div class="space-y-2">
                                    <InputLabel for="title" value="Job Title" />
                                    <TextInput id="title" type="text" class="mt-1 block w-full dark:border-[#1f2d40] dark:bg-[#142233] dark:text-gray-100 focus:border-green-500 dark:focus:border-green-500 focus:ring-green-500 dark:focus:ring-green-500 transition-colors" v-model="form.title" required autofocus />
                                    <InputError class="mt-2" :message="form.errors.title" />
                                </div>

                                <div class="space-y-2">
                                    <InputLabel for="skills" value="Skills (comma-separated)" />
                                    <TextInput id="skills" type="text" class="mt-1 block w-full dark:border-[#1f2d40] dark:bg-[#142233] dark:text-gray-100 focus:border-green-500 dark:focus:border-green-500 focus:ring-green-500 dark:focus:ring-green-500 transition-colors" v-model="form.skills" required />
                                    <InputError class="mt-2" :message="form.errors.skills" />
                                </div>

                                <div>
                                    <InputLabel for="description" value="Job Description" />
                                    <textarea id="description" class="mt-1 block w-full border-gray-300 dark:border-[#1f2d40] dark:bg-[#142233] dark:text-gray-100 focus:border-green-500 dark:focus:border-green-500 focus:ring-green-500 dark:focus:ring-green-500 rounded-md shadow-sm transition-colors" v-model="form.description" required></textarea>
                                    <InputError class="mt-2" :message="form.errors.description" />
                                </div>

                                <div class="space-y-2">
                                    <InputLabel for="apply_url" value="Job URL (Apply Link)" />
                                    <TextInput id="apply_url" type="url" class="mt-1 block w-full dark:border-[#1f2d40] dark:bg-[#142233] dark:text-gray-100 focus:border-green-500 dark:focus:border-green-500 focus:ring-green-500 dark:focus:ring-green-500 transition-colors" v-model="form.apply_url" required placeholder="https://company.com/jobs/123" />
                                    <InputError class="mt-2" :message="form.errors.apply_url" />
                                </div>

                                <div class="space-y-2">
                                    <InputLabel for="contact_phone" value="Contact Phone (Optional)" />
                                    <TextInput id="contact_phone" type="text" class="mt-1 block w-full dark:border-[#1f2d40] dark:bg-[#142233] dark:text-gray-100 focus:border-green-500 dark:focus:border-green-500 focus:ring-green-500 dark:focus:ring-green-500 transition-colors" v-model="form.contact_phone" />
                                    <InputError class="mt-2" :message="form.errors.contact_phone" />
                                </div>
                                
                                <div class="space-y-2">
                                    <InputLabel for="contact_email" value="Contact Email (Optional)" />
                                    <TextInput id="contact_email" type="email" class="mt-1 block w-full dark:border-[#1f2d40] dark:bg-[#142233] dark:text-gray-100 focus:border-green-500 dark:focus:border-green-500 focus:ring-green-500 dark:focus:ring-green-500 transition-colors" v-model="form.contact_email" />
                                    <InputError class="mt-2" :message="form.errors.contact_email" />
                                </div>
                            </div>

                            <div class="mt-6 flex items-center justify-end space-x-3">
                                <a :href="route('jobs.index')" class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-800 dark:bg-[#24364a] dark:hover:bg-[#2f4460] dark:text-gray-100 transition-colors">Cancel</a>
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="bg-[#1C355E] hover:bg-[#254a7a] dark:bg-[#1C355E] dark:hover:bg-[#254a7a] transition-colors">
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
