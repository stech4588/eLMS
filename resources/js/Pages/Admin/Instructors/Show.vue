<template>
    <Head :title="'Instructor Details - ' + instructorUser.name" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-white dark:text-gray-200 leading-tight">
                Instructor Details: {{ instructorUser.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-[#0b1624] border border-gray-200 dark:border-[#1f2d40] overflow-hidden shadow-sm sm:rounded-lg transition-colors duration-200">
                    <div class="p-6 sm:p-8 text-gray-900 dark:text-gray-100 bg-white dark:bg-[#1A2C38] transition-colors duration-200">
                        <div class="space-y-10">
                            <!-- Summary -->
                            <div class="flex flex-col lg:flex-row lg:items-center gap-6">
                                <div class="flex items-center gap-4">
                                    <img :src="profilePhoto" alt="Instructor avatar" class="w-20 h-20 rounded-2xl border-4 border-white dark:border-[#0b1624] shadow-lg">
                                    <div>
                                        <h3 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ instructorUser.name }}</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-300">{{ instructorUser.email }}</p>
                                        <div class="mt-3 flex flex-wrap items-center gap-2">
                                            <span
                                                v-if="instructorUser.instructor"
                                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300"
                                            >
                                                Application: <span class="capitalize">{{ instructorUser.instructor.status }}</span>
                                            </span>
                                            <span
                                                v-if="instructorUser.instructor && instructorUser.instructor.status === 'approved'"
                                                :class="[
                                                    'inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold',
                                                    instructorUser.is_active ? 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-300' : 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-300'
                                                ]"
                                            >
                                                Account: {{ instructorUser.is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-1 grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div class="rounded-2xl border border-gray-200 dark:border-[#1f2d40] bg-gray-50 dark:bg-[#142233] p-4 shadow-sm">
                                        <span class="text-xs uppercase tracking-widest text-gray-500 dark:text-gray-400">Followers</span>
                                        <p class="mt-2 text-xl font-semibold text-gray-900 dark:text-gray-100">
                                            {{ (instructorUser.instructor && instructorUser.instructor.followers) || 'N/A' }}
                                        </p>
                                    </div>
                                    <div class="rounded-2xl border border-gray-200 dark:border-[#1f2d40] bg-gray-50 dark:bg-[#142233] p-4 shadow-sm">
                                        <span class="text-xs uppercase tracking-widest text-gray-500 dark:text-gray-400">Teaching Language</span>
                                        <p class="mt-2 text-xl font-semibold text-gray-900 dark:text-gray-100">
                                            {{ (instructorUser.instructor && instructorUser.instructor.teaching_language) || 'N/A' }}
                                        </p>
                                    </div>
                                    <div class="rounded-2xl border border-gray-200 dark:border-[#1f2d40] bg-gray-50 dark:bg-[#142233] p-4 shadow-sm">
                                        <span class="text-xs uppercase tracking-widest text-gray-500 dark:text-gray-400">Registered</span>
                                        <p class="mt-2 text-xl font-semibold text-gray-900 dark:text-gray-100">
                                            {{ instructorUser.created_at ? new Date(instructorUser.created_at).toLocaleDateString() : 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- User Details -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Instructor Information</h3>
                                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="px-4 py-3 rounded-lg bg-gray-50 dark:bg-[#142233] border border-gray-200 dark:border-[#1f2d40]">
                                        <span class="text-gray-600 dark:text-gray-300 block text-xs uppercase tracking-wide">Name</span>
                                        <span class="font-semibold text-gray-900 dark:text-gray-100">{{ instructorUser.name }}</span>
                                    </div>
                                    <div class="px-4 py-3 rounded-lg bg-gray-50 dark:bg-[#142233] border border-gray-200 dark:border-[#1f2d40]">
                                        <span class="text-gray-600 dark:text-gray-300 block text-xs uppercase tracking-wide">Email</span>
                                        <span class="font-semibold text-gray-900 dark:text-gray-100">{{ instructorUser.email }}</span>
                                    </div>
                                    <div class="px-4 py-3 rounded-lg bg-gray-50 dark:bg-[#142233] border border-gray-200 dark:border-[#1f2d40] md:col-span-2">
                                        <span class="text-gray-600 dark:text-gray-300 block text-xs uppercase tracking-wide">Phone</span>
                                        <span class="font-semibold text-gray-900 dark:text-gray-100">{{ instructorUser.phone_number || 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Instructor Details -->
                            <div v-if="instructorUser.instructor">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Instructor Profile</h3>
                                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="px-4 py-3 rounded-lg bg-gray-50 dark:bg-[#142233] border border-gray-200 dark:border-[#1f2d40]">
                                        <span class="text-gray-600 dark:text-gray-300 block text-xs uppercase tracking-wide">LinkedIn Profile</span>
                                        <a :href="instructorUser.instructor.linkedin_url" target="_blank" class="font-semibold text-blue-600 dark:text-blue-400 hover:underline break-all">{{ instructorUser.instructor.linkedin_url }}</a>
                                    </div>
                                    <div class="px-4 py-3 rounded-lg bg-gray-50 dark:bg-[#142233] border border-gray-200 dark:border-[#1f2d40]">
                                        <span class="text-gray-600 dark:text-gray-300 block text-xs uppercase tracking-wide">LinkedIn Programs</span>
                                        <span class="font-semibold text-gray-900 dark:text-gray-100">{{ instructorUser.instructor.linkedin_programs || 'N/A' }}</span>
                                    </div>
                                    <div class="px-4 py-3 rounded-lg bg-gray-50 dark:bg-[#142233] border border-gray-200 dark:border-[#1f2d40] md:col-span-2">
                                        <span class="text-gray-600 dark:text-gray-300 block text-xs uppercase tracking-wide">Additional Notes</span>
                                        <span class="font-semibold text-gray-900 dark:text-gray-100">{{ instructorUser.instructor.additional_notes || 'No additional notes provided.' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <Link :href="route('admin.instructors.index')" class="inline-flex items-center px-4 py-2 bg-[#2563eb] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#1d4ed8] active:bg-[#1e40af] focus:outline-none focus:border-[#1d4ed8] focus:ring ring-[#1d4ed8] disabled:opacity-25 transition ease-in-out duration-150">
                                &larr; Back to Instructor List
                                </Link>
                                <div v-if="instructorUser.instructor && instructorUser.instructor.status === 'pending' && source === 'request'" class="flex space-x-4 justify-end">
                                    <button @click="approveInstructor(instructorUser.instructor.id)" class="px-4 py-2 bg-[#2563eb] text-white rounded-lg hover:bg-[#1d4ed8] transition-colors">Approve</button>
                                    <button @click="rejectInstructor(instructorUser.instructor.id)" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-500 transition-colors">Reject</button>
                                </div>
                            </div>
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
import { defineProps, computed, toRefs } from 'vue';
import Swal from 'sweetalert2';

const props = defineProps({
    instructorUser: Object,
    source: String,
});

const { instructorUser, source } = toRefs(props);

const profilePhoto = computed(() => {
    if (!instructorUser.value) {
        return '';
    }
    if (instructorUser.value.profile_photo_url) {
        return instructorUser.value.profile_photo_url;
    }

    const name = encodeURIComponent(instructorUser.value.name ?? 'Instructor');
    return `https://ui-avatars.com/api/?name=${name}&background=0B1624&color=FFFFFF&size=256`;
});
const approveInstructor = (instructorId) => {
    console.log('=== Frontend: Instructor Approval Started ===');
    console.log('Instructor ID:', instructorId);
    console.log('Instructor User Data:', instructorUser.value);
    console.log('Current Status:', instructorUser.value?.instructor?.status);
    
    router.post(`/admin/instructors/${instructorId}/approve`, {}, {
        preserveScroll: true,
        onStart: () => {
            console.log('=== Frontend: Approval Request Started ===');
            console.log('Request URL:', `/admin/instructors/${instructorId}/approve`);
        },
        onProgress: (event) => {
            console.log('=== Frontend: Approval Request Progress ===', {
                progress: event.progress,
                total: event.total,
                percentage: event.progress?.percentage
            });
        },
        onSuccess: (page) => {
            console.log('=== Frontend: Approval Request Success ===');
            console.log('Response Status:', page?.status || 'unknown');
            console.log('Response Data:', page);
            console.log('Flash Messages:', page?.props?.flash);
        },
        onError: (errors) => {
            console.error('=== Frontend: Approval Request Error ===');
            console.error('Error Object:', errors);
            console.error('Error Keys:', Object.keys(errors || {}));
            
            // Log each error individually
            if (errors) {
                Object.keys(errors).forEach(key => {
                    console.error(`Error [${key}]:`, errors[key]);
                });
            }
            
            // Show error to user
            Swal.fire({
                icon: 'error',
                title: 'Approval Failed',
                text: errors?.error || errors?.message || 'An error occurred while approving the instructor. Please try again.',
            });
        },
        onFinish: () => {
            console.log('=== Frontend: Approval Request Finished ===');
        },
        onCancel: () => {
            console.warn('=== Frontend: Approval Request Cancelled ===');
        },
    }).catch((error) => {
        // Catch any unexpected errors
        console.error('=== Frontend: Unexpected Error in Approval ===');
        console.error('Error Type:', error?.constructor?.name);
        console.error('Error Message:', error?.message);
        console.error('Error Stack:', error?.stack);
        console.error('Full Error Object:', error);
        
        // Try to extract more details
        if (error?.response) {
            console.error('Error Response:', error.response);
            console.error('Error Response Status:', error.response?.status);
            console.error('Error Response Data:', error.response?.data);
        }
        
        if (error?.request) {
            console.error('Error Request:', error.request);
        }
        
        // Show error to user
        Swal.fire({
            icon: 'error',
            title: 'Unexpected Error',
            text: 'An unexpected error occurred. Please check the console for details.',
        });
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
            console.log('=== Frontend: Instructor Rejection Started ===');
            console.log('Instructor ID:', instructorId);
            console.log('Rejection Reason:', result.value);
            
            router.post(`/admin/instructors/${instructorId}/reject`, { reason: result.value }, {
                preserveScroll: true,
                onStart: () => {
                    console.log('=== Frontend: Rejection Request Started ===');
                    console.log('Request URL:', `/admin/instructors/${instructorId}/reject`);
                },
                onProgress: (event) => {
                    console.log('=== Frontend: Rejection Request Progress ===', {
                        progress: event.progress,
                        total: event.total,
                        percentage: event.progress?.percentage
                    });
                },
                onSuccess: (page) => {
                    console.log('=== Frontend: Rejection Request Success ===');
                    console.log('Response Status:', page?.status || 'unknown');
                    console.log('Response Data:', page);
                    console.log('Flash Messages:', page?.props?.flash);
                    Swal.fire('Rejected!', 'The instructor has been rejected.', 'success');
                },
                onError: (errors) => {
                    console.error('=== Frontend: Rejection Request Error ===');
                    console.error('Error Object:', errors);
                    console.error('Error Keys:', Object.keys(errors || {}));
                    
                    // Log each error individually
                    if (errors) {
                        Object.keys(errors).forEach(key => {
                            console.error(`Error [${key}]:`, errors[key]);
                        });
                    }
                    
                    let errorText = 'An unknown error occurred.';
                    if(errors.reason) {
                        errorText = errors.reason;
                    } else if (errors.error) {
                        errorText = errors.error;
                    } else if (errors.message) {
                        errorText = errors.message;
                    }
                    Swal.fire('Error', errorText, 'error');
                },
                onFinish: () => {
                    console.log('=== Frontend: Rejection Request Finished ===');
                },
                onCancel: () => {
                    console.warn('=== Frontend: Rejection Request Cancelled ===');
                },
            }).catch((error) => {
                // Catch any unexpected errors
                console.error('=== Frontend: Unexpected Error in Rejection ===');
                console.error('Error Type:', error?.constructor?.name);
                console.error('Error Message:', error?.message);
                console.error('Error Stack:', error?.stack);
                console.error('Full Error Object:', error);
                
                // Try to extract more details
                if (error?.response) {
                    console.error('Error Response:', error.response);
                    console.error('Error Response Status:', error.response?.status);
                    console.error('Error Response Data:', error.response?.data);
                }
                
                if (error?.request) {
                    console.error('Error Request:', error.request);
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Unexpected Error',
                    text: 'An unexpected error occurred. Please check the console for details.',
                });
            });
        }
    });
};
</script> 