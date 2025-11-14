<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    courses: Array,
});

// const page = usePage(); // Already imported usePage if needed for flash messages directly in script

// Pagination for My Courses
const currentPageMyCourses = ref(1);
const itemsPerPageMyCourses = ref(9); // Display 6 courses per page, adjust as needed

const totalMyCoursesCount = computed(() => {
    return props.courses ? props.courses.length : 0;
});

const totalPagesMyCourses = computed(() => {
    if (totalMyCoursesCount.value === 0) return 1;
    return Math.ceil(totalMyCoursesCount.value / itemsPerPageMyCourses.value);
});

const paginatedMyCourses = computed(() => {
    if (!props.courses || props.courses.length === 0) return [];
    const start = (currentPageMyCourses.value - 1) * itemsPerPageMyCourses.value;
    const end = start + itemsPerPageMyCourses.value;
    return props.courses.slice(start, end);
});

function nextPageMyCourses() {
    if (currentPageMyCourses.value < totalPagesMyCourses.value) {
        currentPageMyCourses.value++;
    }
}

function prevPageMyCourses() {
    if (currentPageMyCourses.value > 1) {
        currentPageMyCourses.value--;
    }
}

const toggleFavorite = async (course) => {
    // Optimistically update the UI first
    const originalIsFavorited = course.is_favorited;
    course.is_favorited = !course.is_favorited;

    try {
        // router.post will send a POST request. 
        // Ensure you have a route like Route::post('/courses/{course}/favorite', [YourController::class, 'toggleFavorite']);
        await router.post(route('courses.toggleFavorite', { course: course.id }), {}, {
            preserveScroll: true, // Keep scroll position
            preserveState: true, // Preserve component state where possible
            onSuccess: (page) => {
                // Optionally, you can verify the change from the server response if needed
                // For example, if the controller returns the updated course or its favorite status.
                // However, if the `is_favorited` attribute is part of the main course data that's refreshed,
                // Inertia might handle the update automatically if you refetch data.
                // For now, we rely on the optimistic update and the backend to be consistent.
            },
            onError: (errors) => {
                // Revert the optimistic update if there was an error
                course.is_favorited = originalIsFavorited;
                console.error('Error toggling favorite:', errors);
                // Optionally, show a notification to the user
            },
        });
    } catch (error) {
        // Revert the optimistic update in case of an unexpected error with the request itself
        course.is_favorited = originalIsFavorited;
        console.error('Failed to send favorite toggle request:', error);
    }
};

</script>

<template>

    <Head title="My Courses" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-white">
                My Courses
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div v-if="$page.props.flash && $page.props.flash.success"
                    class="mb-4 p-4 bg-green-100 text-green-700 border border-green-300 rounded">
                    {{ $page.props.flash.success }}
                </div>
                <div v-if="$page.props.flash && $page.props.flash.error"
                    class="mb-4 p-4 bg-red-100 text-red-700 border border-red-300 rounded">
                    {{ $page.props.flash.error }}
                </div>

                <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6  border-b border-gray-200 dark:bg-[#1A2C38]">
                        <div v-if="paginatedMyCourses && paginatedMyCourses.length > 0"
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div v-for="course in paginatedMyCourses" :key="course.id">
                                <Link :href="route('courses.show', { course: course.id })" class="block hover:shadow-lg transition-shadow duration-200 ease-in-out rounded-lg h-full">
                                    <div class="bg-white rounded-lg shadow-md overflow-hidden my_course_card h-full flex flex-col dark:bg-gray-800">
                                        <div class="relative w-full h-48">
                                            <img v-if="course.thumbnail_url" :src="course.thumbnail_url" alt="Course thumbnail"
                                                class="absolute inset-0 w-full h-full object-cover">
                                            <img v-else-if="course.first_video_thumbnail_url"
                                                :src="course.first_video_thumbnail_url" alt="Video thumbnail"
                                                class="absolute inset-0 w-full h-full object-cover">
                                            <div v-else class="absolute inset-0 w-full h-full bg-gray-200 flex items-center justify-center">
                                                <span class="text-gray-500">No Thumbnail</span>
                                            </div>
                                        </div>
                                        <div class="p-4 flex flex-col flex-grow">
                                            <p class="text-gray-600 text-sm mb-1 dark:text-gray-400 truncate_description flex-grow">{{ course.type }}</p>
                                            <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-white">{{ course.title }}</h3>
                                            <div class="flex justify-between items-center mt-1">
                                                <p class="text-xs text-gray-500 dark:text-gray-400">By: {{ course.author || "Placeholder Author" }}</p>
                                                <button @click.stop.prevent="toggleFavorite(course)" class="p-1 rounded-full hover:bg-gray-200">
                                                    <svg v-if="course.is_favorited" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-red-500">
                                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                                    </svg>
                                                    <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-600">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                                    </svg>
                                                </button>
                                            </div>
                                            
                                            <!-- Example: <p>Price: {{ course.price }}</p> -->
                                        </div>
                                    </div>
                                </Link>
                            </div>
                        </div>
                        <div v-else>
                            <p>You have not created or enrolled in any courses yet.</p>
                        </div>

                        <!-- Pagination Controls -->
                        <div v-if="totalPagesMyCourses > 1" class="flex justify-center items-center mt-8 space-x-2">
                            <button @click="prevPageMyCourses" :disabled="currentPageMyCourses === 1"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded-md hover:bg-[#63c0ff] disabled:opacity-50">
                                Previous
                            </button>
                            <span>Page {{ currentPageMyCourses }} of {{ totalPagesMyCourses }}</span>
                            <button @click="nextPageMyCourses" :disabled="currentPageMyCourses === totalPagesMyCourses"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded-md hover:bg-[#63c0ff] disabled:opacity-50">
                                Next
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
@media (max-width: 540px) {
    .my_course_grid {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }
}

.my_course_card:hover {
    cursor: pointer;
    transform: scale(1.05);
    transition: transform 0.3s ease;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.truncate_description {
    /* You might want to add styles for truncating description if it's too long */
    /* Example for two lines, requires -webkit-line-clamp which is not universally supported */
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2; /* number of lines to show */
    -webkit-box-orient: vertical;
}
</style>