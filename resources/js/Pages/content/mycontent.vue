<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import { defineProps } from 'vue'


const props = defineProps({
    skillBasedCourses: Array,
    topics: Array,
});

const visibleTopicCount = ref(9)

const visibleTopics = computed(() => {
  return props.topics.slice(0, visibleTopicCount.value)
})

function showMoreTopics() {
  visibleTopicCount.value += 9
}

function showLessTopics() {
    visibleTopicCount.value=9
}


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


        <div class="p-4">
            <div class="mx-auto max-w-7xl ">
                <div class="overflow-hidden sm:rounded-lg">
                    <div class="course-card-wrapper dark:bg-dark-bg-secondary">
                        <div class="course-card dark:bg-gray-800">
                            <div class="course-card-text dark:text-white">
                                Leadership & Management
                                <a href="/content" class="course-card-button dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">Explore</a>
                            </div>
                            <img src="/images/leadership_management_image.svg" alt="Leadership & Management"
                                class="course-card-image" />
                        </div>

                        <div class="course-card dark:bg-gray-800">
                            <div class="course-card-text dark:text-white">
                                Diversity & Equity
                                <a href="/content" class="course-card-button dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">Explore</a>
                            </div>
                            <img src="/images/diversity_image.svg" alt="Diversity & Equity" class="course-card-image" />
                        </div>

                        <div class="course-card dark:bg-gray-800">
                            <div class="course-card-text dark:text-white">
                                Productivity
                                <a href="/content" class="course-card-button dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">Explore</a>
                            </div>
                            <img src="/images/productivity_image.svg" alt="Productivity" class="course-card-image" />
                        </div>
                    </div>

                    <!-- Skills Section -->
                <div class="section_box dark:bg-dark-bg-secondary">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-xl font-bold dark:text-white">Because of Skills you Follow</h3>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-6 mt-6 mycontent_grid" style="gap: 25px; padding: 20px;">
                        <div v-for="(course, index) in displayedCourses" :key="`skill-${index}-${course.id}`" class="course-card-container">
                            <div class="linkedin-card dark:bg-gray-800">
                                <div class="flex flex-row">
                                    <Link :href="route('courses.show', { course: course.id })" class="linkedin-card-img-wrap">
                                    <img :src="getThumbnailSrc(course)" class="linkedin-card-img" alt="Course thumbnail" />
                                </Link>
                                <div style="width: 100%; padding: 10px;">
                                    <p class="linkedin-card-type dark:text-[#d1d5db]">{{ course.type }}</p>
                                    <p class="linkedin-card-title dark:text-white">{{ course.title }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 h-10 overflow-hidden text-ellipsis dark:text-[#d1d5db]">{{ course.description || 'No description available.' }}</p>
                                    
                                </div>
                                </div>
                               
                                <div class="linkedin-card-body dark:text-white">
                                   
                                    <div class="w-full bg-gray-200 rounded-full h-1.5 mb-4 dark:bg-gray-700">
                                        <div class="bg-blue-600 h-1.5 rounded-full" :style="{ width: course.progress + '%' }"></div>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-300 mb-4">{{ Math.round(course.progress) }}% complete</p>

                                    <div class="linkedin-card-footer mt-auto">
                                        <p class="linkedin-card-author dark:text-[#d1d5db]">By: {{ course.author || 'Placeholder' }}</p>
                                        <button @click.stop.prevent="toggleFavorite(course)" class="linkedin-card-fav-btn">
                                            <svg v-if="course.is_favorited" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-red-500">
                                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                            </svg>
                                            <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-600 dark:text-gray-400">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="flex items-center justify-between mt-4 space-x-2">
                                        <Link :href="route('courses.show', { course: course.id })" class="course-action-btn-details dark:bg-gray-700 dark:text-white dark:border-gray-600">
                                            Details
                                        </Link>
                                        
                                        <div v-if="course.is_purchased" class="flex-grow">
                                            <Link :href="course.first_video_id ? route('courses.play', { course: course.id, video: course.first_video_id }) : '#'" 
                                                  class="course-action-btn-play dark:bg-blue-600 dark:text-white w-full">
                                                Play Course
                                            </Link>
                                        </div>
                                        <div v-else class="flex-grow">
                                            <Link :href="route('cart', { course_id: course.id })" class="course-action-btn-buy dark:bg-gray-700 dark:text-white dark:border-gray-600 w-full">
                                                <span>Buy Now</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                                </svg>
                                            </Link>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                     <!-- Pagination Controls -->
                     <div v-if="totalPagesMyCourses > 1" class="flex justify-center items-center mt-8 space-x-2">
                            <button @click="prevPageMyCourses" :disabled="currentPageMyCourses === 1"
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-white bg-gray-200 dark:bg-gray-700 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 disabled:opacity-50">
                                Previous
                            </button>
                            <span class="dark:text-white">Page {{ currentPageMyCourses }} of {{ totalPagesMyCourses }}</span>
                            <button @click="nextPageMyCourses" :disabled="currentPageMyCourses === totalPagesMyCourses"
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-white bg-gray-200 dark:bg-gray-700 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 disabled:opacity-50">
                                Next
                            </button>
                        </div>
                </div>
                </div>
         
            <!-- Topics Section -->


  <div class="section_box flex justify-start dark:bg-dark-bg-secondary">
    <div class="w-full max-w-6xl">
      <h3 class="text-xl font-bold mb-4 text-start dark:text-white">Topics</h3>

      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-1">
        <div
          v-for="(topic, index) in visibleTopics"
          :key="`topic-${index}`"
          class="flex justify-start p-0">
          <p class="font-small truncate max-w-[200px] dark:text-white">{{ topic.name }}</p>
        </div>
      </div>

      <!-- Show More Button -->
    <div class="flex justify-center mt-4 space-x-4" v-if="topics.length > 9">
  <button
    v-if="visibleTopicCount < topics.length"
    @click="showMoreTopics"
    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-white rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition">
    Show More
  </button>

  <!-- Show Less Button (always shown if count > 9) -->
  <button
    v-if="visibleTopicCount > 9"
    @click="showLessTopics"
    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-white rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition">
    Show Less
       </button>
     </div>
    </div>
  </div>
 </div>
</div>
    </AuthenticatedLayout>
</template>

<style>
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
    justify-content: space-between;
    gap: 16px;
    background-color: white;
    padding: 20px;
    border-radius: 16px;
}
@media (max-width:1290px) {
    .course-card-wrapper {
        flex-wrap: wrap;
    }
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
    max-width: 388px;
    box-sizing: border-box;
    height: 249px;
}

.course-card-text {
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 24px;
    width: 100%;
    align-items: flex-start;
    font-size: 20px;
    font-weight: 600;
}
@media(max-width:1435px){
    .course-card-text{
        align-items: center;
    }
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

.linkedin-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: box-shadow 0.2s, transform 0.2s;
    border: 1px solid #e6e6e6;
    width: 100%;
}
.linkedin-card:hover {
    box-shadow: 0 8px 24px rgba(0,0,0,0.16);
    transform: translateY(-2px);
}
.linkedin-card-img-wrap {
    width: 55%;
    height: 100px;

    
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px;
    padding-right: 0px;
}
.linkedin-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    background-color: white;   
    border-radius: 10px;
}
.linkedin-card-body {
    padding: 16px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}
.linkedin-card-type {
    font-size: 12px;
    color: #6b7280;
    margin-bottom: 4px;
}
.linkedin-card-title {
    font-size: 16px;
    font-weight: 600;
    color: #222;
    /* margin-bottom: 8px; */
    height: 23px; /* 2 lines with 20px line-height */
    overflow: hidden;
}
.linkedin-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.linkedin-card-author {
    font-size: 12px;
    color: #6b7280;
}
.linkedin-card-fav-btn {
    background: none;
    border: none;
    padding: 4px;
    cursor: pointer;
    border-radius: 50%;
}

.course-action-btn-details, .course-action-btn-play, .course-action-btn-buy {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
    text-align: center;
    transition: background-color 0.3s, color 0.3s;
    flex-grow: 1;
}

.course-action-btn-details {
    background-color: transparent;
    border: 1px solid #7E7E7E;
    color: #333;
}

.course-action-btn-play {
    background-color: #0073b1;
    color: white;
    border: 1px solid transparent;
}

.course-action-btn-buy {
    background-color: transparent;
    border: 1px solid #7E7E7E;
    color: #333;
}

.course-action-btn-details:hover, .course-action-btn-buy:hover {
    background-color: #f0f0f0;
}

.course-action-btn-play:hover {
    background-color: #005a8c;
}
.course-card-container {
    display: flex; /* Ensures the card within takes up the full space */
}
@media (max-width: 1024px) {
    .course-card{
        flex-direction: row;
        height: 200px;
        max-width: 100%;
       
    }
    .course-card-text{
        align-items: flex-start;
        text-align: left;
        justify-content: flex-start;
    }
}
@media (max-width: 1024px) {
    .mycontent_grid{
       padding-left: 0px !important;
       padding-right: 0px !important;
    }
}
</style>