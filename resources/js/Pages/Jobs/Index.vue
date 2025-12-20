<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import JobDetailsModal from './JobDetailsModal.vue';
import { debounce } from 'lodash';
import Swal from 'sweetalert2';

const props = defineProps({
    jobs: Object,
    filters: Object,
    skills: Array,
    authUserId: Number,
});

const search = ref(props.filters.search);
const skill = ref(props.filters.skill);
const date = ref(props.filters.date);

const selectedJob = ref(null);
const isModalVisible = ref(false);
const modalMode = ref('view');

const openModal = (job, mode = 'view') => {
    selectedJob.value = job;
    modalMode.value = mode;
    isModalVisible.value = true;
};

const closeModal = () => {
    isModalVisible.value = false;
    selectedJob.value = null;
    modalMode.value = 'view';
};

const truncateDescription = (description, wordCount) => {
    const words = description.split(' ');
    if (words.length > wordCount) {
        return words.slice(0, wordCount).join(' ') + '...';
    }
    return description;
};

const clearFilters = () => {
    search.value = '';
    skill.value = '';
    date.value = '';
};

watch([search, skill, date], debounce(() => {
    router.get(route('jobs.index'), {
        search: search.value,
        skill: skill.value,
        date: date.value,
    }, {
        preserveState: true,
        replace: true,
    });
}, 300));

const deleteJob = (job) => {
    if (!job || job.user.id !== props.authUserId) return;

    Swal.fire({
        title: 'Delete job',
        text: `Are you sure you want to delete "${job.title}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Delete',
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('jobs.destroy', job.id), {
                preserveScroll: true,
            });
        }
    });
};

</script>

<template>
    <Head title="Jobs" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-white dark:text-gray-200 leading-tight">Jobs</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Job Listings</h3>
                            <Link :href="route('jobs.create')" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Post a Job
                            </Link>
                        </div>
                        
                        <!-- Search and Filters -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                            <input type="text" v-model="search" placeholder="Search by title or skill..." class="col-span-1 md:col-span-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <select v-model="skill" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Filter by skill</option>
                                <option v-for="s in skills" :key="s" :value="s">{{ s }}</option>
                            </select>
                            <input type="date" v-model="date" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <button @click="clearFilters" class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-500">
                                Clear
                            </button>
                        </div>

                        <!-- Job Listings -->
                        <div v-if="jobs.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div v-for="job in jobs.data" :key="job.id" class="bg-gray-50 dark:bg-gray-900 p-6 rounded-lg shadow-md cursor-pointer hover:shadow-lg transition-shadow duration-200 flex flex-col relative" @click="openModal(job)">
                                <div v-if="job.user.id === props.authUserId" class="absolute top-4 right-4 flex gap-2 z-10">
                                    <button @click.stop.prevent="openModal(job, 'edit')" class="icon-btn bg-blue-600 hover:bg-blue-700 text-white" title="Edit job">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                            <path d="M5 18h14v2H5zM15.586 3a2 2 0 012.828 0l1.586 1.586a2 2 0 010 2.828L9 18H5v-4L15.586 3z" />
                                        </svg>
                                    </button>
                                    <button @click.stop.prevent="deleteJob(job)" class="icon-btn bg-red-600 hover:bg-red-700 text-white" title="Delete job">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                            <path d="M9 3v1H4v2h16V4h-5V3H9zm2 5v9H9V8h2zm4 0v9h-2V8h2z" />
                                            <path d="M7 20c0 1.103.897 2 2 2h6c1.103 0 2-.897 2-2V8H7v12z" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="flex-grow">
                                    <h4 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ job.title }}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Posted by: {{ job.user.name }}</p>
                                    <p class="text-gray-700 dark:text-gray-300 mt-3">{{ truncateDescription(job.description, 15) }}</p>
                                </div>
                                <div class="mt-4">
                                    <h5 class="font-semibold text-gray-900 dark:text-gray-100">Skills:</h5>
                                    <p class="text-gray-600 dark:text-gray-400 truncate">{{ job.skills }}</p>
                                </div>
                                <div class="mt-4 flex justify-end">
                                    <a :href="job.apply_url" target="_blank" rel="noopener noreferrer" @click.stop class="inline-flex items-center px-3 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">
                                        Apply
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 3h7m0 0v7m0-7L10 14" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <p class="text-center text-gray-500 dark:text-gray-400">No jobs match your criteria.</p>
                        </div>
                        
                        <!-- Pagination -->
                        <div v-if="jobs.data.length > 0" class="mt-8">
                            <Pagination :links="jobs.links" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <JobDetailsModal :show="isModalVisible" :job="selectedJob" :auth-user-id="props.authUserId" :initial-mode="modalMode" @close="closeModal" />
    </AuthenticatedLayout>
</template>

<style scoped>
.icon-btn {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 9999px;
    transition: background-color 0.2s ease, opacity 0.2s ease;
}
</style>
