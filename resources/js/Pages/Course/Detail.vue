<template>
    <Head :title="course ? course.title : 'Course Detail'" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">
                <div v-if="course" class="bg-white overflow-hidden shadow-sm sm:rounded-lg ">
                    <div class="p-6 bg-white border-b border-gray-200 dark:bg-dark-bg-secondary dark:text-white">
                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="md:w-1/3">
                                <img v-if="course.first_video_thumbnail_url" :src="course.first_video_thumbnail_url" alt="Course Thumbnail" class="w-full h-auto rounded-lg shadow-md">
                                <div v-else class="w-full h-48 bg-gray-200 rounded-lg shadow-md flex items-center justify-center text-gray-500 dark:text-white">
                                    No Thumbnail Available
                                </div>
                            </div>
                            <div class="md:w-2/3">
                                <h1 class="text-3xl font-bold mb-2 dark:text-white">{{ course.title }}</h1>
                                <p class="text-lg text-gray-700 mb-1 dark:text-white truncate"><span class="font-semibold dark:text-white">Type:</span> {{ course.type || 'N/A' }}</p>
                                <p class="text-lg text-gray-700 mb-1 dark:text-white truncate"><span class="font-semibold dark:text-white">Industry:</span> {{ course.industry_name || 'N/A' }}</p>
                                <p class="text-lg text-gray-700 mb-1 dark:text-white truncate"><span class="font-semibold dark:text-white">Certificate:</span> {{ course.certificate_name || 'N/A' }}</p>
                                <p class="text-lg text-gray-700 mb-1 dark:text-white truncate"><span class="font-semibold dark:text-white">Author:</span> {{ course.author || 'N/A' }}</p>


                                <!-- Add more course details here as needed -->
                                <h2 class="text-2xl font-semibold mb-2 mt-6 dark:text-white">Description</h2>
                                <p class="text-gray-600 whitespace-pre-wrap dark:text-white">{{ course.description || 'No description available.' }}</p>

                                <h2 class="text-2xl font-semibold mb-2 mt-6 dark:text-white">Additional Description</h2>
                                <p class="text-gray-600 whitespace-pre-wrap dark:text-white">{{ course.additional_description || 'No additional description available.' }}</p>

                                <h2 class="text-2xl font-semibold mb-2 mt-6">Recomendations</h2>
                                <p class="text-gray-600 whitespace-pre-wrap dark:text-white">{{ course.recomendations || 'No recomendations available.' }}</p>

                                 <!-- Placeholder for video player or video list -->
                                <div class="mt-6">
                                    <h3 class="text-xl font-semibold">Course Content</h3>
                                    <!-- If you have a list of videos, you can display them here -->
                                    <p v-if="!course.videos || course.videos.length === 0" class="text-gray-500">No videos available for this course.</p>
                                    <ul v-else class="list-disc pl-5 mt-2 space-y-1 text-gray-600 dark:text-white">
                                        <li v-for="video in course.videos" :key="video.id">{{ video.title }}</li>
                                    </ul>
                                    
                                    <div v-if="course.videos && course.videos.length > 0" class="mt-4" style="display: flex; justify-content: flex-end; align-items: center;">
                                        
                                        <!-- Show "Buy Now" only to students who haven't purchased the course -->
                                        <Link v-if="user && user.type === 'student' && !isPurchased" 
                                              :href="route('cart', { course_id: course.id })" 
                                              class="bg-green-500 text-white px-6 py-2 rounded-md hover:bg-green-600 transition-colors">
                                            Buy Now for ${{ course.price }}
                                        </Link>
                                        
                                        <!-- Show "Play Course" to instructors, purchased students, and guests -->
                                        <Link v-else 
                                              :href="route('courses.play', { course: course.id, video: course.videos[0].id })" 
                                              class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition-colors">
                                            Play Course
                                        </Link>
                                    </div>
                                    <div v-else class="mt-4 text-right">
                                        <button class="bg-gray-400 text-white px-6 py-2 rounded-md cursor-not-allowed" disabled>
                                            Play Course
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 text-center">
                        <p class="text-xl text-gray-500">Loading course details or course not found...</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    course: Object, // Expects a single course object
    isPurchased: Boolean,
});

const page = usePage();
const user = computed(() => page.props.auth.user);

// You might want to fetch more detailed video information or other related data here
// using onMounted or by passing more data from the controller.
</script>

<style >
.main_sidebar {
    /* display: none ; */
}
/* Add any page-specific styles here */
</style>