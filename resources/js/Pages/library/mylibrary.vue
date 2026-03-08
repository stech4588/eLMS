<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    savedCourses: {
        type: Array,
        default: () => [],
    },
    inProgressItems: {
        type: Array,
        default: () => [],
    },
    // Define other props if you pass more dynamic data, e.g., for 'In Progress'
});

const selectedOption = ref('In Progress');

const selectOption = (option) => {
    selectedOption.value = option;
};

const sidebarOptions = computed(() => [
    { name: 'In Progress', count: props.inProgressItems.length },
    { name: 'Saved', count: props.savedCourses.length },
    // { name: 'My Collections', count: 12 },
    // { name: 'Learning History', count: 1 },
]);

// Placeholder course data for non-Saved categories (can be replaced by props later)
const coursesData = {
    // 'In Progress' data will now come from props.inProgressItems
    // 'My Collections': [ /* ... */ ],
    // 'Learning History': [ /* ... */ ],
};

const currentCourses = computed(() => {
    if (selectedOption.value === 'Saved') {
        return props.savedCourses;
    }
    if (selectedOption.value === 'In Progress') {
        return props.inProgressItems;
    }
    return coursesData[selectedOption.value] || [];
});

</script>

<template>
    <Head title=" My Library" />

    <AuthenticatedLayout>
        
        <div class="bg-white dark:bg-dark-bg-secondary p-6 dark:text-white"
             style="gap: 20px; display: flex; flex-direction: column;">
            <div style="font-size: 36px; font-weight: 600;">
                My Library
            </div>
            <div class="library_main_div_header" style="display: flex; flex-direction: row; gap: 20px; width: 100%;">
                <!-- Weekly Goals Card -->
                <div class="library_main_div_header_card" style="border: 1px solid #7E7E7E; border-radius: 8px; padding: 16px; width: 33%;">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center">
                                <div class="p-2 mr-2 bg-gray-100 rounded-full">
                                    <!-- Placeholder for an icon -->
                                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class=" text-lg font-semibold">Weekly Goals <span
                                            class="text-xs text-gray-500 libaray_dark_text">(May12 to
                                            May18)</span>
                                    </p>
                                    <p class="text-sm text-gray-500 libaray_dark_text">2/120mins</p>
                                </div>
                            </div>
                        </div>
                        <button class="text-gray-500 hover:text-gray-700">
                            <!-- Placeholder for an edit icon -->
                            <img src="/images/pen_icon.svg" alt="diamond" class="w-6 h-6 dark_library_pen_icon">
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
                                    <img src="/images/diamond.svg" alt="diamond" class="w-6 h-6 dark_library_pen_icon">
                                </div>
                                <div>
                                    <p class=" text-lg font-semibold">Skills</p>
                                    <p class="text-sm text-gray-500 libaray_dark_text">28 Followed Skills</p>
                                </div>
                            </div>
                        </div>
                        <button class="text-gray-500 hover:text-gray-700">
                            <!-- Placeholder for an edit icon -->
                            <img src="/images/pen_icon.svg" alt="diamond" class="w-6 h-6 dark_library_pen_icon">
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
                                    <img src="/images/diamond.svg" alt="diamond" class="w-6 h-6 dark_library_pen_icon">
                                </div>
                                <div>
                                    <p class=" text-lg font-semibold">Skill Evaluations</p>
                                    <p class="text-sm text-gray-500 libaray_dark_text">2 Evaluations</p>
                                </div>
                            </div>
                        </div>
                        <button class="text-gray-500 hover:text-gray-700">
                            <!-- Placeholder for an arrow icon -->
                            <img src="/images/right_arrow_icon.svg" alt="diamond" class="w-6 h-6 dark_library_pen_icon">
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-12" style="padding: 23px;">
            <div class="mx-auto max-w-7xl">
                <!-- Header Cards -->

                <div class="flex library_main_div bg-white dark:bg-dark-bg" style="background-color: transparent;">
                    <!-- Sidebar -->
                    <div class="p-6 mr-6 bg-white rounded-lg library_main_div_left dark:bg-dark-bg-secondary" style="padding-left: 0px; padding-right: 0px; width: 25%;">
                        <nav>
                            <ul>
                                <li v-for="option in sidebarOptions" :key="option.name" class="mb-4 library_left_sidebar" style="font-size: 20px;">
                                    <a href="#"
                                       @click.prevent="selectOption(option.name)"
                                       :class="['block p-3 rounded dark:text-white', selectedOption === option.name ? 'font-semibold text-black-600 border-l-4 border-black dark:text-white' : 'text-black-600 hover:bg-[#c3e6fd]']" style="border-radius: 0px; padding-left: 20px;">
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
                                <div v-for="course in currentCourses" :key="course.id" class="p-6 bg-white rounded-lg shadow dark:bg-dark-bg-secondary dark:text-white">
                                    <div class="flex library_videos">
                                        <img :src="course.thumbnail" alt="Course Thumbnail" style="width: 200px; "
                                            class="mr-4 rounded  h-15"> <!-- Make sure w-30 and h-21 are valid Tailwind classes or use style bindings -->
                                        <div class="flex-grow">
                                            <p class="text-xs text-black-500" style="font-size: 11px;">
                                                {{ course.type }}
                                                <!-- <span v-if="selectedOption === 'In Progress' && course.course_title">
                                                     <strong>{{ course.course_title }}</strong>
                                                </span> -->
                                            </p>
                                            <h3 class="mb-1 text-lg font-semibold" style="font-size: 16px;">{{ course.course_title }}</h3>
                                            <p class="mb-2 text-sm text-black-500" style="font-size: 11px;">
                                                {{ course.title }}
                                            </p>
                                            <p class="mb-2 text-sm text-black-500" style="font-size: 11px;">
                                                By: {{ course.author }}
                                                <span v-if="selectedOption === 'In Progress'"> > Last activity: {{ course.updated }}</span>
                                                <span v-else-if="selectedOption === 'Saved'"> > Updated {{ course.updated }}</span>
                                                <!-- General case for other types if any -->
                                                <span v-else> > {{ course.updated }}</span>
                                            </p>
                                            <p v-if="selectedOption === 'Saved' && course.duration" class="mb-2 text-sm text-gray-600 dark:text-white" style="font-size: 11px;">Duration: {{ course.duration }}</p>
                                            <!-- Display Video duration if it's an In Progress Video -->
                                            <!-- <p v-if="selectedOption === 'In Progress' && course.duration" class="mb-2 text-sm text-gray-600" style="font-size: 11px;">Video Duration: {{ course.duration }}</p> -->
                                            
                                            <div class="" style="display: flex; justify-content: space-between; width: 100%;">
                                                <div v-if="selectedOption !== 'Saved'" class="flex items-center mb-2" style="width: 60%;">
                                                    <div class="w-full h-1 mr-2 bg-gray-200 rounded-full progress_bar_dark_main"
                                                        style="height: 2px;"> 
                                                        <div class="h-1 bg-black rounded-full progress_bar_dark"
                                                            :style="{ width: course.progress + '%', height: '2px' }"></div> 
                                                    </div>
                                                    <span class="text-xs text-gray-500" style="min-width: 90px;">{{ course.timeLeft }}</span>
                                                </div>
                                                <!-- Spacer for Saved tab to align buttons to the right -->
                                                <div v-else style="flex-grow: 1;"></div> 

                                                <div class="flex flex-row items-end " style="align-items: center; gap: 10px;">
                                                    <!-- <button class="text-gray-500 hover:text-gray-700">
                                                        <img src="/images/three_dot.svg" alt="options" class="w-4 h-4">
                                                    </button> -->
                                                    <Link 
                                                        v-if="selectedOption === 'In Progress' && course.course_id && course.video_id"
                                                        :href="route('courses.play', { course: course.course_id, video: course.video_id })"
                                                        class="px-4 py-2 text-sm text-gray-700 border border-gray-600 rounded hover:bg-gray-100"
                                                        style="border-radius: 30px;"
                                                    >
                                                        Continue
                                                    </Link>
                                                    <!-- Fallback or different button for 'Saved' items or if IDs are missing -->
                                                    <Link 
                                                        v-if="selectedOption === 'Saved' && course.id" 
                                                        :href="route('courses.show', { course: course.id })"
                                                        class="px-4 py-2 text-sm text-gray-700 border border-gray-600 rounded hover:bg-gray-100" 
                                                        style="border-radius: 30px;"
                                                        >
                                                        View Course
                                                    </Link>
                                                    <button 
                                                        v-else-if="selectedOption === 'Saved'" 
                                                        class="px-4 py-2 text-sm text-gray-400 border border-gray-400 rounded cursor-not-allowed"
                                                        style="border-radius: 30px;" disabled >
                                                        View Course
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                             <div v-else class="empty-library-state bg-white dark:bg-dark-bg-secondary p-6">
                                <img src="/images/nothing_to_see.png" alt="No items" class="empty-library-image" />
                                <p class="empty-library-message dark:text-white">
                                    You don't have any courses in {{ selectedOption.toLowerCase() }}.
                                </p>
                                <p class="empty-library-submessage dark:text-white">
                                    When you start a course you can find it here. Start watching videos that interest you.
                                </p>
                                <Link :href="route('dashboard')" class="empty-library-button">
                                    Show recommended courses
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="footer_upload_video dark:bg-dark-bg-secondary dark:text-white">
            <div class="footer-lang-select libaray_dark_text">
                Language(Eng)
            </div>
            <span class="footer-divider libaray_dark_text">•</span>
            <a href="#" class="footer-link libaray_dark_text">About</a>
            <span class="footer-divider libaray_dark_text">•</span>
            <a href="#" class="footer-link libaray_dark_text">Become an instructor</a>
            <span class="footer-divider libaray_dark_text">•</span>
            <a href="#" class="footer-link libaray_dark_text">Privacy Policy</a>
            <span class="footer-divider libaray_dark_text">•</span>
            <a href="#" class="footer-link libaray_dark_text">Accessibility</a>
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
.footer_upload_video {
    background-color: #477CAA;
    color: white;
}
@media (max-width: 770px) {
    .footer_upload_video{
        flex-direction: column;
        
    }
}

/* New styles for empty library state */
.empty-library-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
    text-align: center;
    background-color: #fff;
    border-radius: 8px;
    min-height: 400px; /* Ensure it takes up enough vertical space */
}

.empty-library-image {
    width: 250px; /* Adjust size as needed */
    height: auto;
    margin-bottom: 20px;
}

.empty-library-message {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
}

.empty-library-submessage {
    font-size: 14px;
    color: #666;
    margin-bottom: 20px;
    max-width: 400px;
}

.empty-library-button {
    background-color: #22c55e; /* primary green */
    color: #fff;
    padding: 10px 20px;
    border-radius: 20px;
    text-decoration: none;
    font-weight: 600;
    transition: background-color 0.2s;
}

.empty-library-button:hover {
    background-color: #004182;
}

/* Enhanced styles for the existing footer content */
.footer_upload_video {
    background-color: #477CAA;
    color: white; /* Crisp white background */
    padding: 20px 40px; /* Standard padding */
    border-top: 1px solid #e7e7e7; /* Very subtle top border */
    font-family: 'Arial', sans-serif; /* Common web-safe font */
    font-size: 13px; /* Standard dark grey for text */
    display: flex;
    justify-content: center; /* Center items */
    align-items: center;
    flex-wrap: wrap;
    gap: 10px 20px; /* Adjusted gap for spacing */
    min-height: 50px; /* Minimum height */
}

.footer-lang-select {
    color: #5f6368;
    font-weight: normal;
    white-space: nowrap;
    padding: 2px 0;
}
.dark .footer-lang-select{
    color: white;
}
.dark .footer-link{
    color: white;
}
.footer-link {
    color: #feffff;
    text-decoration: none;
    transition: color 0.2s, text-decoration 0.2s;
    white-space: nowrap;
    cursor: pointer;
    line-height: 1.2;
    padding: 2px 0;
}

.footer-link:hover {
    color: #16a34a; /* green hover */
    text-decoration: underline;
}

.footer-divider {
    font-size: 14px; /* Small dot */
    font-weight: bold;
    color: #cccccc; /* Lighter grey for dots */
    margin: 0 5px;
    line-height: 1;
    display: inline-block;
}

@media (max-width: 768px) {
    .footer_upload_video {
        flex-direction: column;
        align-items: flex-start;
        padding: 15px 20px; /* Reduced padding on small screens */
        gap: 8px; /* Tighter vertical gap */
    }
    .footer-divider {
        display: none;
    }
    .footer-link, .footer-lang-select {
        margin-bottom: 0;
    }
}
.dark .dark_library_pen_icon{
    filter: invert(1);
}
.dark .libaray_dark_text{
    color: white;
}
.dark .progress_bar_dark{
    background-color: white;
}
.dark .progress_bar_dark_main{
    background-color: #6c706f;
}
</style>