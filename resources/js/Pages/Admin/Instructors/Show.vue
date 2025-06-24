<template>
    <Head :title="'Instructor Details - ' + instructorUser.name" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Instructor Details: {{ instructorUser.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-[#2d2d2d] overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <!-- User Details -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold">Instructor Information</h3>
                            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div><strong>Name:</strong> {{ instructorUser.name }}</div>
                                <div><strong>Email:</strong> {{ instructorUser.email }}</div>
                                <div><strong>Phone:</strong> {{ instructorUser.phone_number || 'N/A' }}</div>
                            </div>
                        </div>

                        <!-- Instructor Details -->
                        <div v-if="instructorUser.instructor">
                             <h3 class="text-lg font-medium">Instructor Profile</h3>
                             <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div><strong>LinkedIn Profile:</strong> 
                                    <a :href="instructorUser.instructor.linkedin_url" target="_blank" class="text-blue-500 hover:underline">{{ instructorUser.instructor.linkedin_url }}</a>
                                </div>
                                <div><strong>Followers:</strong> {{ instructorUser.instructor.followers }}</div>
                                <div><strong>Teaching Language:</strong> {{ instructorUser.instructor.teaching_language }}</div>
                                <div class="md:col-span-2"><strong>LinkedIn Programs:</strong> {{ instructorUser.instructor.linkedin_programs || 'N/A' }}</div>
                                <div><strong>Application Status:</strong> <span class="capitalize font-semibold">{{ instructorUser.instructor.status }}</span></div>
                             </div>
                        </div>
                         <div class="mt-6">
                            <Link :href="route('admin.instructors.index')" class="inline-flex items-center px-4 py-2 bg-[#2563eb] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#2563eb] active:bg-[#2563eb] focus:outline-none focus:border-[#2563eb] focus:ring ring-[#2563eb] disabled:opacity-25 transition ease-in-out duration-150">
                                &larr; Back to Instructor List
                            </Link>
                        </div>
                        <div v-if="instructorUser.instructor.status === 'pending' && source === 'request'" class="mt-6 flex space-x-4 justify-end">
                            <button @click="approveInstructor(instructorUser.instructor.id)" class="px-4 py-2 bg-[#2563eb] text-white rounded hover:bg-[#2563eb]">Approve</button>
                            <button @click="rejectInstructor(instructorUser.instructor.id)" class="px-4 py-2 bg-black text-white rounded hover:bg-black-600">Reject</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import Swal from 'sweetalert2';
defineProps({
    instructorUser: Object,
    source: String,
});
const approveInstructor = (instructorId) => {
    router.post(`/admin/instructors/${instructorId}/approve`, {}, {
        preserveScroll: true,
    });
};

const rejectInstructor = (instructorId) => {
    Swal.fire({
        title: 'Reason for Rejection',
        input: 'textarea',
        inputPlaceholder: 'Enter the reason for rejection here...',
        showCancelButton: true,
        confirmButtonText: 'Submit Rejection',
        showLoaderOnConfirm: true,
        preConfirm: (reason) => {
            if (!reason || reason.length < 10) {
                Swal.showValidationMessage('A reason of at least 10 characters is required.');
                return false;
            }
            return reason;
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            router.post(`/admin/instructors/${instructorId}/reject`, { reason: result.value }, {
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire('Rejected!', 'The instructor has been rejected.', 'success');
                },
                onError: (errors) => {
                    let errorText = 'An unknown error occurred.';
                    if(errors.reason) {
                        errorText = errors.reason;
                    }
                    Swal.fire('Error', errorText, 'error');
                }
            });
        }
    });
};
</script> 