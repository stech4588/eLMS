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

const nextUp = computed(() => {
    if (!props.inProgressItems?.length) return null;
    return props.inProgressItems[0];
});

</script>

<template>
    <Head title=" My Library" />

    <AuthenticatedLayout>
        <div class="page-with-footer-wrap">
        <div class="library-page-content library-shell">
            <div class="library-header">
                <div class="library-title">My Library</div>
                <div class="library_main_div_header">
                <!-- In Progress -->
                <div class="library_main_div_header_card library-stat-card">
                    <div class="library-stat-top">
                        <div class="library-stat-left">
                            <span class="library-stat-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M12 8v5l3 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0Z" stroke="currentColor" stroke-width="2"/>
                                </svg>
                            </span>
                            <div class="library-stat-label">In Progress</div>
                        </div>
                        <div class="library-stat-value">{{ props.inProgressItems.length }}</div>
                    </div>
                    <div class="library-stat-sub">
                        Continue learning from where you left off.
                    </div>
                    <div class="library-stat-extra">
                        <span class="library-chip">Tip: 10 mins daily</span>
                        <span class="library-chip">Build your streak</span>
                    </div>
                </div>

                <!-- Saved -->
                <div class="library_main_div_header_card library-stat-card">
                    <div class="library-stat-top">
                        <div class="library-stat-left">
                            <span class="library-stat-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M7 3h10a1 1 0 011 1v17l-6-3-6 3V4a1 1 0 011-1Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            <div class="library-stat-label">Saved</div>
                        </div>
                        <div class="library-stat-value">{{ props.savedCourses.length }}</div>
                    </div>
                    <div class="library-stat-sub">
                        Your bookmarked courses to watch later.
                    </div>
                    <div class="library-stat-extra">
                        <span class="library-chip">Save from Content</span>
                        <span class="library-chip">Watch anytime</span>
                    </div>
                </div>

                <!-- Next up -->
                <div class="library_main_div_header_card library-stat-card">
                    <div class="library-stat-top">
                        <div class="library-stat-left">
                            <span class="library-stat-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M7 4v16l13-8-13-8Z" fill="currentColor"/>
                                </svg>
                            </span>
                            <div class="library-stat-label">Next up</div>
                        </div>
                        <div class="library-stat-value">Continue</div>
                    </div>
                    <div v-if="nextUp" class="library-nextup">
                        <div class="library-nextup-title">{{ nextUp.course_title }}</div>
                        <div class="library-nextup-sub">{{ nextUp.title }}</div>
                        <Link
                            v-if="nextUp.course_id && nextUp.video_id"
                            :href="route('courses.play', { course: nextUp.course_id, video: nextUp.video_id })"
                            class="library-nextup-btn"
                        >
                            <span class="library-nextup-btn-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M7 4v16l13-8-13-8Z" fill="currentColor"/>
                                </svg>
                            </span>
                            Resume
                        </Link>
                    </div>
                    <div v-else class="library-stat-sub">
                        Start a course to see your next lesson here.
                    </div>
                </div>
            </div>
            </div>
        </div>

        <div class="library-body">
                <div class="library_main_div">
                    <!-- Sidebar -->
                    <div class="library_main_div_left">
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
                        <div class="bg-white rounded-lg library-right-surface">
                            <!-- <div class="p-6">
                                <h3 class="mb-4 text-xl font-semibold">Showing: {{ selectedOption }}</h3>
                                 Conditional rendering based on selectedOption will go here
                            </div> -->
                            <!-- Loop through currentCourses -->
                            <div v-if="currentCourses.length > 0" class="library-courses-list">
                                <div v-for="course in currentCourses" :key="course.id" class="library-course-card dark:bg-dark-bg-secondary dark:text-white">
                                    <div class="library-course-media">
                                        <img :src="course.thumbnail" alt="Course Thumbnail" class="library-course-thumb" />
                                    </div>

                                    <div class="library-course-body">
                                        <div class="library-course-meta">
                                            <span class="library-pill">{{ course.type }}</span>
                                            <span class="library-course-updated">
                                                <template v-if="selectedOption === 'In Progress'">Last activity: {{ course.updated }}</template>
                                                <template v-else-if="selectedOption === 'Saved'">Updated {{ course.updated }}</template>
                                                <template v-else>{{ course.updated }}</template>
                                            </span>
                                        </div>

                                        <h3 class="library-course-title">
                                            {{ course.course_title }}
                                        </h3>
                                        <p class="library-course-subtitle">
                                            {{ course.title }}
                                        </p>
                                        <p class="library-course-author">
                                            By: {{ course.author }}
                                            <span v-if="selectedOption === 'Saved' && course.duration" class="library-course-duration">
                                                • Duration: {{ course.duration }}
                                            </span>
                                        </p>

                                        <div class="library-course-footer">
                                            <div v-if="selectedOption !== 'Saved'" class="library-progress">
                                                <div class="library-progress-bar">
                                                    <div class="library-progress-fill" :style="{ width: (course.progress || 0) + '%' }"></div>
                                                </div>
                                                <span class="library-progress-text">{{ course.timeLeft }}</span>
                                            </div>

                                            <div class="library-actions">
                                                <Link
                                                    v-if="selectedOption === 'In Progress' && course.course_id && course.video_id"
                                                    :href="route('courses.play', { course: course.course_id, video: course.video_id })"
                                                    class="library-btn-primary"
                                                >
                                                    Continue
                                                </Link>

                                                <Link
                                                    v-else-if="selectedOption === 'Saved' && course.id"
                                                    :href="route('courses.show', { course: course.id })"
                                                    class="library-btn-secondary"
                                                >
                                                    View Course
                                                </Link>

                                                <button
                                                    v-else-if="selectedOption === 'Saved'"
                                                    class="library-btn-disabled"
                                                    disabled
                                                >
                                                    View Course
                                                </button>
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
        </div><!-- .library-page-content -->

        <!-- <footer class="footer_upload_video footer-stick-bottom dark:bg-dark-bg-secondary dark:text-white">
        <!-- <footer class="footer_upload_video footer-stick-bottom dark:bg-dark-bg-secondary dark:text-white">
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
        </footer> -->
        </div><!-- .page-with-footer-wrap -->
    </AuthenticatedLayout>
</template>

<style>
.home_page_style {
    padding: 0px;
}
.library-shell{
    flex: 1;
    padding: 20px 16px 8px;
    max-width: 1280px;
    margin: 0 auto;
}
.library-title{
    font-size: 34px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 14px;
    line-height: 1.15;
}
.dark .library-title{ color: #fff; }
.library-header{
    display: flex;
    flex-direction: column;
    gap: 14px;
}
.library-body{
    max-width: 1280px;
    margin: 0 auto;
    padding: 8px 16px 18px;
}
.library_left_sidebar {
        font-weight: 600;
        color: black !important;
    }
.library_main_div_header{
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 14px;
    width: 100%;
}
.library_main_div_header_card{
    border: 1px solid rgba(15, 23, 42, 0.12);
    border-radius: 12px;
    padding: 14px 16px;
    background: #ffffff;
    box-shadow: 0 6px 18px rgba(15, 32, 45, 0.05);
    transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
}
.library_main_div_header_card:hover{
    transform: translateY(-1px);
    box-shadow: 0 12px 28px rgba(15, 32, 45, 0.08);
    border-color: rgba(28, 53, 94, 0.18);
}
.dark .library_main_div_header_card{
    background: #142233;
    border-color: #1f2d40;
    box-shadow: none;
}
.dark .library_main_div_header_card:hover{
    box-shadow: none;
    border-color: rgba(88, 207, 255, 0.22);
}
.library-stat-card{
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.library-stat-left{
    display: inline-flex;
    align-items: center;
    gap: 10px;
}
.library-stat-icon{
    width: 34px;
    height: 34px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #1C355E;
    background: rgba(28, 53, 94, 0.08);
    border: 1px solid rgba(28, 53, 94, 0.15);
    flex: 0 0 auto;
}
.library-stat-icon svg{
    width: 18px;
    height: 18px;
}
.dark .library-stat-icon{
    color: #58cfff;
    background: rgba(88, 207, 255, 0.10);
    border-color: rgba(88, 207, 255, 0.22);
}
.library-stat-top{
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    gap: 10px;
}
.library-stat-label{
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
}
.dark .library-stat-label{ color: #ffffff; }
.library-stat-value{
    font-size: 22px;
    font-weight: 900;
    color: #1C355E;
}
.dark .library-stat-value{ color: #58cfff; }
.library-stat-sub{
    font-size: 12px;
    color: #64748b;
    line-height: 1.35;
}
.dark .library-stat-sub{ color: rgba(229, 242, 255, 0.75); }
.library-nextup-title{
    font-weight: 800;
    font-size: 13px;
    color: #0f172a;
    margin-bottom: 2px;
}
.dark .library-nextup-title{ color: #ffffff; }
.library-nextup-sub{
    font-size: 12px;
    color: #64748b;
    margin-bottom: 10px;
}
.dark .library-nextup-sub{ color: rgba(229, 242, 255, 0.75); }
.library-nextup-btn{
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 9px 14px;
    border-radius: 999px;
    background: #1C355E;
    color: #fff;
    font-weight: 800;
    font-size: 12px;
    text-decoration: none;
    width: fit-content;
    gap: 8px;
}
.library-nextup-btn:hover{ background: #254a7a; }
.library-nextup-btn-icon{
    width: 16px;
    height: 16px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.library-nextup-btn-icon svg{
    width: 16px;
    height: 16px;
}

.library-stat-extra{
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 2px;
}

.library-chip{
    display: inline-flex;
    align-items: center;
    padding: 6px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    color: #334155;
    background: rgba(15, 23, 42, 0.04);
    border: 1px solid rgba(15, 23, 42, 0.10);
}

.dark .library-chip{
    color: rgba(229, 242, 255, 0.85);
    background: rgba(229, 242, 255, 0.08);
    border-color: rgba(229, 242, 255, 0.16);
}
.library_main_div{
    display: grid;
    grid-template-columns: 260px 1fr;
    gap: 16px;
    align-items: start;
}
.library_main_div_left{
    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, 0.12);
    border-radius: 12px;
    padding: 14px 0;
}
.dark .library_main_div_left{
    background: #142233;
    border-color: #1f2d40;
}
.library-right-surface{
    width: 100%;
    background: transparent;
}
.dark .library-right-surface{
    background: transparent;
}
.library_main_div_right{
    width: 100%;
}
@media (max-width: 1150px) {
    .library_left_sidebar {
        font-size: 15px !important;
    }
}
/* Ensure right panel uses full available width */
.library_main_div_right{
    width: 100% !important;
}
@media (max-width: 1075px) {
    .library_main_div_right{
        width: 100%;
    }
}
@media (max-width: 1075px) {
    .library_main_div {
        grid-template-columns: 1fr;
        gap: 14px;
    }
}
@media (max-width: 1075px) {
    .library_main_div_left {
        width: 100% !important;
    }
}
@media (max-width: 1075px) {
    .library_main_div_header {
        grid-template-columns: 1fr !important;
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
.page-with-footer-wrap {
    display: flex;
    flex-direction: column;
    min-height: calc(100vh - 5rem);
}
.library-page-content { flex: 0 0 auto; }
.footer-stick-bottom { margin-top: auto; }
.footer_upload_video {
    background-color: #1C355E;
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
    min-height: 240px;
}

.empty-library-image {
    width: 220px;
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
    background-color: #1C355E; /* primary green */
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
    color: #254a7a; /* green hover */
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

/* Library course card design */
.library-courses-list{
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.library-course-card{
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 16px;
    padding: 14px;
    border-radius: 14px;
    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, 0.10);
    box-shadow: 0 10px 26px rgba(15, 32, 45, 0.06);
    width: 100%;
}

.dark .library-course-card{
    background: #142233;
    border-color: #1f2d40;
    box-shadow: none;
}

.library-course-thumb{
    width: 100%;
    height: 160px;
    object-fit: cover;
    border-radius: 12px;
    display: block;
}

.library-course-body{
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.library-course-meta{
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    flex-wrap: wrap;
}

.library-pill{
    display: inline-flex;
    align-items: center;
    padding: 6px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    color: #0f172a;
    background: rgba(37, 74, 122, 0.10);
    border: 1px solid rgba(37, 74, 122, 0.20);
}

.dark .library-pill{
    color: #e5f2ff;
    background: rgba(88, 207, 255, 0.14);
    border-color: rgba(88, 207, 255, 0.25);
}

.library-course-updated{
    font-size: 12px;
    color: #64748b;
}

.dark .library-course-updated{
    color: rgba(229, 242, 255, 0.75);
}

.library-course-title{
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.25;
    margin: 0;
}

.dark .library-course-title{ color: #ffffff; }

.library-course-subtitle{
    font-size: 13px;
    color: #334155;
    margin: 0;
}
.dark .library-course-subtitle{ color: rgba(229, 242, 255, 0.85); }

.library-course-author{
    font-size: 12px;
    color: #64748b;
    margin: 0;
}
.dark .library-course-author{ color: rgba(229, 242, 255, 0.75); }

.library-course-duration{
    color: inherit;
}

.library-course-footer{
    margin-top: 6px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}

.library-progress{
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 240px;
    flex: 1;
}

.library-progress-bar{
    flex: 1;
    height: 6px;
    background: rgba(15, 23, 42, 0.10);
    border-radius: 999px;
    overflow: hidden;
}

.dark .library-progress-bar{
    background: rgba(229, 242, 255, 0.14);
}

.library-progress-fill{
    height: 100%;
    background: #1C355E;
    border-radius: 999px;
}

.dark .library-progress-fill{
    background: #58cfff;
}

.library-progress-text{
    font-size: 12px;
    color: #64748b;
    min-width: 72px;
    text-align: right;
}
.dark .library-progress-text{ color: rgba(229, 242, 255, 0.75); }

.library-actions{
    display: flex;
    gap: 10px;
    align-items: center;
}

.library-btn-primary,
.library-btn-secondary,
.library-btn-disabled{
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 16px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 800;
    text-decoration: none;
    transition: transform 0.15s ease, background-color 0.15s ease, border-color 0.15s ease;
    white-space: nowrap;
}

.library-btn-primary{
    background: #1C355E;
    color: #ffffff;
    border: 1px solid transparent;
}
.library-btn-primary:hover{ background: #254a7a; transform: translateY(-1px); }

.library-btn-secondary{
    background: transparent;
    color: #1C355E;
    border: 1px solid rgba(28, 53, 94, 0.35);
}
.library-btn-secondary:hover{ background: rgba(28, 53, 94, 0.08); transform: translateY(-1px); }

.library-btn-disabled{
    background: transparent;
    color: rgba(100, 116, 139, 0.8);
    border: 1px solid rgba(100, 116, 139, 0.35);
    cursor: not-allowed;
}

@media (max-width: 800px) {
    .library-course-card{
        grid-template-columns: 1fr;
    }
    .library-course-thumb{
        height: 180px;
    }
    .library-progress{
        min-width: 0;
        width: 100%;
    }
    .library-progress-text{
        text-align: left;
    }
}

@media (min-width: 1024px) {
    .library-course-card{
        grid-template-columns: 320px 1fr;
        gap: 18px;
        padding: 16px;
    }
    .library-course-thumb{
        height: 180px;
    }
}
</style>