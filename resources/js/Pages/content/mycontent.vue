<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    skillBasedCourses: Array,
});

const getThumbnailSrc = (course) => {
    return course.first_video_thumbnail_url ? course.first_video_thumbnail_url : '/images/skill_section_thumbnail.svg';
};

const selectedCourseType = ref('java'); // default selected for other sections if still used

const displayedCourses = computed(() => {
    // This computed property will now directly use the paginated courses
    return paginatedMyCourses.value;
});

// Pagination for My Courses
const currentPageMyCourses = ref(1);
const itemsPerPageMyCourses = ref(9); // Display 6 courses per page, adjust as needed

const totalMyCoursesCount = computed(() => {
    return props.skillBasedCourses ? props.skillBasedCourses.length : 0;
});

const totalPagesMyCourses = computed(() => {
    if (totalMyCoursesCount.value === 0) return 1;
    return Math.ceil(totalMyCoursesCount.value / itemsPerPageMyCourses.value);
});

const paginatedMyCourses = computed(() => {
    if (!props.skillBasedCourses || props.skillBasedCourses.length === 0) return [];
    const start = (currentPageMyCourses.value - 1) * itemsPerPageMyCourses.value;
    const end = start + itemsPerPageMyCourses.value;
    return props.skillBasedCourses.slice(start, end);
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

    <Head title="Content" />

    <AuthenticatedLayout>


        <div class="">
            <div class="mx-auto max-w-7xl ">
                <div class="overflow-hidden sm:rounded-lg">
                    <div class="course-card-wrapper">
                        <div class="course-card">
                            <div class="course-card-text">
                                Leadership & Management
                                <a href="/content" class="course-card-button">Explore</a>
                            </div>
                            <img src="/images/leadership_management_image.svg" alt="Leadership & Management"
                                class="course-card-image" />
                        </div>

                        <div class="course-card">
                            <div class="course-card-text">
                                Diversity & Equity
                                <a href="/content" class="course-card-button">Explore</a>
                            </div>
                            <img src="/images/diversity_image.svg" alt="Diversity & Equity" class="course-card-image" />
                        </div>

                        <div class="course-card">
                            <div class="course-card-text">
                                Productivity
                                <a href="/content" class="course-card-button">Explore</a>
                            </div>
                            <img src="/images/productivity_image.svg" alt="Productivity" class="course-card-image" />
                        </div>
                    </div>

                    <!-- Skills Section -->
                <div class="section_box">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-xl font-bold">Because of Skills you Follow</h3>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4" style="gap: 25px; padding: 20px;">
                        <div v-for="(course, index) in displayedCourses" :key="`skill-${index}`">
                            <Link :href="route('courses.show', { course: course.id })" class="block hover:shadow-lg transition-shadow duration-200 ease-in-out rounded-lg h-full">
                                <div class="bg-white rounded-lg overflow-hidden shadow my_course_card">
                                    <img :src="getThumbnailSrc(course)" class="w-full h-48 object-cover"
                                        alt="Course thumbnail" />
                                    <div class="p-4">
                                        <p class="text-xs text-gray-500">{{ course.type }}</p>
                                        <p class="text-sm font-semibold leading-tight title_hidden">{{ course.title }}
                                        </p>
                                        <div class="flex justify-between items-center mt-1">
                                                <p class="text-xs text-gray-500">By: {{ course.author || "Placeholder Author" }}</p>
                                                <button @click.stop.prevent="toggleFavorite(course)" class="p-1 rounded-full hover:bg-gray-200">
                                                    <svg v-if="course.is_favorited" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-red-500">
                                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                                    </svg>
                                                    <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-600">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                                    </svg>
                                                </button>
                                            </div>

                                    </div>
                                </div>
                            </Link>
                        </div>
                    </div>
                     <!-- Pagination Controls -->
                     <div v-if="totalPagesMyCourses > 1" class="flex justify-center items-center mt-8 space-x-2">
                            <button @click="prevPageMyCourses" :disabled="currentPageMyCourses === 1"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-[#97d5ff] rounded-md hover:bg-[#63c0ff] disabled:opacity-50">
                                Previous
                            </button>
                            <span>Page {{ currentPageMyCourses }} of {{ totalPagesMyCourses }}</span>
                            <button @click="nextPageMyCourses" :disabled="currentPageMyCourses === totalPagesMyCourses"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-[#97d5ff] rounded-md hover:bg-[#63c0ff] disabled:opacity-50">
                                Next
                            </button>
                        </div>
                </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
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

.section_box {
    background-color: white;
    padding: 20px;
    border-radius: 16px;
    margin-top:30px;
}
@media (max-width: 430px) {
    .main_left_right_button {
        display: flex;
    }
}
.left_right_button {
    border: 1px solid #000000;
    border-radius: 50%;
    padding: 5px 10px;
    background: none;
    cursor: pointer;
}

@media (max-width: 430px) {
    .left_right_button {
        width: 35px;
    }
}
.main_skill_buttons {
    margin-top: 16px;
    margin-bottom: 35px;
}

.skill_buttons {
    font-weight: 600;
    border-color: black;
}


.course-card-wrapper {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    gap: 16px;
    background-color: white;
    padding: 20px;
    border-radius: 16px;
}

.course-card {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    gap: 12px;
    border: 1px solid #7E7E7E;
    border-radius: 12px;
    padding: 16px;
    width: 100%;
    max-width: 318px;
    box-sizing: border-box;
    height: 249px;
}

.course-card-text {
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 24px;
    font-size: 20px;
    font-weight: 600;
}

.course-card-button {
    font-size: 16px;
    border: 1px solid #000;
    border-radius: 20px;
    padding: 8px 16px;
    background-color: transparent;
    cursor: pointer;
    width: 90px;
}

.course-card-image {
    width: 117px;
}

/* Responsive for smaller screens */
@media (max-width: 768px) {
    .course-card {
        flex-direction: column-reverse;
        align-items: center;
        text-align: center;
    }

    .course-card-image {
        width: 80px;
    }

    .course-card-text {
        align-items: center;
    }
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