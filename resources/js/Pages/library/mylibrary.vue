<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const selectedOption = ref('In Progress');

const selectOption = (option) => {
    selectedOption.value = option;
};

const sidebarOptions = [
    { name: 'In Progress', count: 31 },
    { name: 'Saved', count: 22 },
    { name: 'My Collections', count: 12 },
    { name: 'Learning History', count: 1 },
];

// Placeholder course data for each category
const coursesData = {
    'In Progress': [
        { id: 1, type: 'Course', title: 'Advanced Vue 3 Techniques', author: 'Jane Doe', updated: 'August 2024', progress: 70, timeLeft: '30min 10s left', thumbnail: '/images/skill_section_thumbnail.svg' },
        { id: 2, type: 'Video', title: 'Understanding Reactivity in Vue', author: 'John Smith', updated: 'July 2024', progress: 45, timeLeft: '15min 5s left', thumbnail: '/images/skill_section_thumbnail.svg' },
    ],
    'Saved': [
        { id: 3, type: 'Course', title: 'Tailwind CSS Masterclass', author: 'Alice Brown', updated: 'June 2024', progress: 0, timeLeft: '1h 20min left', thumbnail: '/images/skill_section_thumbnail.svg' },
    ],
    'My Collections': [
        { id: 4, type: 'Playlist', title: 'JavaScript Deep Dive', author: 'Collection Curator', updated: 'May 2024', progress: 10, timeLeft: '5h 0min left', thumbnail: '/images/skill_section_thumbnail.svg' },
        { id: 5, type: 'Video', title: 'Node.js for Beginners', author: 'Bob Green', updated: 'July 2024', progress: 90, timeLeft: '5min 0s left', thumbnail: '/images/skill_section_thumbnail.svg' },
    ],
    'Learning History': [
        { id: 6, type: 'Course', title: 'Introduction to Python', author: 'Chris White', updated: 'January 2024', progress: 100, timeLeft: 'Completed', thumbnail: '/images/skill_section_thumbnail.svg' },
    ],
};

const currentCourses = computed(() => {
    return coursesData[selectedOption.value] || [];
});

</script>

<template>
    <Head title=" My Library" />

    <AuthenticatedLayout>
        <!-- <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                 My Library
            </h2>
        </template> -->
        <div style="background-color: white; padding: 32px 40px; gap: 20px; display: flex; flex-direction: column;">
            <div style="font-size: 36px; font-weight: 600;">
                My Library
            </div>
            <div class="library_main_div_header" style="display: flex; flex-direction: row; gap: 20px; width: 100%;">
                <!-- Weekly Goals Card -->
                <div class="library_main_div_header_card" style="border: 1px solid #7E7E7E; border-radius: 8px; padding: 16px; width: 33%;">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center">
                                <div class="p-2 mr-2 bg-blue-100 rounded-full">
                                    <!-- Placeholder for an icon -->
                                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class=" text-lg font-semibold">Weekly Goals <span
                                            class="text-xs text-gray-500">(May12 to
                                            May18)</span>
                                    </p>
                                    <p class="text-sm text-gray-500">2/120mins</p>
                                </div>
                            </div>
                        </div>
                        <button class="text-gray-500 hover:text-gray-700">
                            <!-- Placeholder for an edit icon -->
                            <img src="/images/pen_icon.svg" alt="diamond" class="w-6 h-6">
                        </button>
                    </div>
                </div>
                <!-- Skills Card -->
                <div class="library_main_div_header_card" style="border: 1px solid #7E7E7E; border-radius: 8px; padding: 16px; width: 33%;">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center" style="gap: 10px;">
                                <div class="">
                                    <!-- Placeholder for an icon -->
                                    <img src="/images/diamond.svg" alt="diamond" class="w-6 h-6">
                                </div>
                                <div>
                                    <p class=" text-lg font-semibold">Skills</p>
                                    <p class="text-sm text-gray-500">28 Followed Skills</p>
                                </div>
                            </div>
                        </div>
                        <button class="text-gray-500 hover:text-gray-700">
                            <!-- Placeholder for an edit icon -->
                            <img src="/images/pen_icon.svg" alt="diamond" class="w-6 h-6">
                        </button>
                    </div>
                </div>
                <!-- Skill Evaluations Card -->
                <div class="library_main_div_header_card" style="border: 1px solid #7E7E7E; border-radius: 8px; padding: 16px; width: 33%;">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center" style="gap: 10px;">
                                <div class="">
                                    <!-- Placeholder for an icon -->
                                    <img src="/images/diamond.svg" alt="diamond" class="w-6 h-6">
                                </div>
                                <div>
                                    <p class=" text-lg font-semibold">Skill Evaluations</p>
                                    <p class="text-sm text-gray-500">2 Evaluations</p>
                                </div>
                            </div>
                        </div>
                        <button class="text-gray-500 hover:text-gray-700">
                            <!-- Placeholder for an arrow icon -->
                            <img src="/images/right_arrow_icon.svg" alt="diamond" class="w-6 h-6">
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Header Cards -->

                <div class="flex library_main_div" >
                    <!-- Sidebar -->
                    <div class=" p-6 mr-6 bg-white rounded-lg library_main_div_left" style="padding-left: 0px; padding-right: 0px; width: 25%;">
                        <nav>
                            <ul>
                                <li v-for="option in sidebarOptions" :key="option.name" class="mb-4 library_left_sidebar" style="font-size: 20px;">
                                    <a href="#"
                                       @click.prevent="selectOption(option.name)"
                                       :class="['block p-3 rounded', selectedOption === option.name ? 'font-semibold text-black-600 bg-[#97d5ff] border-l-4 border-black' : 'text-black-600 hover:bg-[#c3e6fd]']" style="border-radius: 0px; padding-left: 20px;">
                                        {{ option.name }} ({{ option.count }})
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                    <!-- Main Content Area -->
                    <div class=" library_main_div_right">
                        <!-- Course List -->
                        <div class="bg-white rounded-lg">
                            <!-- <div class="p-6">
                                <h3 class="mb-4 text-xl font-semibold">Showing: {{ selectedOption }}</h3>
                                 Conditional rendering based on selectedOption will go here
                            </div> -->
                            <!-- Loop through currentCourses -->
                            <div v-if="currentCourses.length > 0" class="space-y-6">
                                <div v-for="course in currentCourses" :key="course.id" class="p-6 bg-white rounded-lg shadow">
                                    <div class="flex library_videos">
                                        <img :src="course.thumbnail" alt="Course Thumbnail"
                                            class="mr-4 rounded w-30 h-21"> <!-- Make sure w-30 and h-21 are valid Tailwind classes or use style bindings -->
                                        <div class="flex-grow">
                                            <p class="text-xs text-black-500" style="font-size: 11px;">{{ course.type }}</p>
                                            <h3 class="mb-1 text-lg font-semibold" style="font-size: 16px;">{{ course.title }}</h3>
                                            <p class="mb-2 text-sm text-black-500" style="font-size: 11px;">By: {{ course.author }} > Updated {{ course.updated }}</p>
                                            <div class="" style="display: flex; justify-content: space-between; width: 100%;">
                                                <div class="flex items-center mb-2" style="width: 60%;">
                                                    <div class="w-full h-1 mr-2 bg-gray-200 rounded-full"
                                                        style="height: 2px;"> <!-- Removed fixed width: 50% to allow progress to fill -->
                                                        <div class="h-1 bg-black rounded-full"
                                                            :style="{ width: course.progress + '%', height: '2px' }"></div> <!-- Use :style for dynamic width -->
                                                    </div>
                                                    <span class="text-xs text-gray-500" style="min-width: 90px;">{{ course.timeLeft }}</span>
                                                </div>
                                                <div class="flex flex-row items-end " style="align-items: center; gap: 10px;">
                                                    <button class="text-gray-500 hover:text-gray-700">
                                                        <img src="/images/three_dot.svg" alt="options" class="w-4 h-4">
                                                    </button>
                                                    <button
                                                        class="px-4 py-2 text-sm text-blue-500 border border-blue-500 rounded hover:bg-blue-50" style="border-radius: 30px;">Share</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                             <div v-else class="p-6 text-center text-gray-500">
                                No items to display in {{ selectedOption }}.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="footer_upload_video" style="background-color: white; display: flex; justify-content: space-between; padding: 20px; align-items: baseline; ">
            <div>
                Language(Eng)
            </div>
            <div style="font-size: 40px; font-weight: 400;">.</div>
            <div>
                About
            </div>
            <div style="font-size: 40px; font-weight: 400;">.</div>
            <div>
               Become an instructor
            </div>
            <div style="font-size: 40px; font-weight: 400;">.</div>
            <div>
                Privacy Policy
            </div>
            <div style="font-size: 40px; font-weight: 400;">.</div>
            <div>
               Accessibility
            </div>
        </footer>
    </AuthenticatedLayout>
</template>

<style>
.home_page_style {
    padding: 0px;
}
.library_left_sidebar {
        font-weight: 600;
        color: black !important;
    }
@media (max-width: 1150px) {
    .library_left_sidebar {
        font-size: 15px !important;
    }
}
.library_main_div_right{
    width: 75%;
}
@media (max-width: 1075px) {
    .library_main_div_right{
        width: 100%;
    }
}
@media (max-width: 1075px) {
    .library_main_div {
        flex-direction: column;
        gap: 20px;
    }
}
@media (max-width: 1075px) {
    .library_main_div_left {
        width: 100% !important;
    }
}
@media (max-width: 1075px) {
    .library_main_div_header {
        flex-direction: column !important;
        /* width: 100% !important; */
    }
}
@media (max-width: 1075px) {
    .library_main_div_header_card {
        width: 100% !important;
    }
}
@media (max-width: 800px) {
    .library_videos {
        flex-direction: column;
    }
}
@media (max-width: 770px) {
    .footer_upload_video{
        flex-direction: column;
        
    }
}
</style>