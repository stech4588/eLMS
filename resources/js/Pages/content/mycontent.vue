<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
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
                                        <p class="text-xs text-gray-500 mt-1">By: {{ course.author }}</p>
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
         


  <div class="section_box flex justify-start">
    <div class="w-full max-w-6xl">
      <h3 class="text-xl font-bold mb-4 text-center">Topics</h3>

      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
        <div
          v-for="(topic, index) in visibleTopics"
          :key="`topic-${index}`"
          class="flex justify-start p-4 rounded-lg shadow hover:shadow-md transition w-full max-w-[90%] sm:max-w-[80%] md:max-w-[90%]"
        >
          <p class="font-semibold">{{ topic.name }}</p>
        </div>
      </div>

      <!-- Show More Button -->
      <div class="flex justify-center mt-4" v-if="visibleTopicCount < topics.length">
        <button
          @click="showMoreTopics"
          class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition"
        >
          Show More
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