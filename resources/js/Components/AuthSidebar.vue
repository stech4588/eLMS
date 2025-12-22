<template>
    <aside v-if="sidebarVisible" class="main_sidebar shadow-md" :class="{ 'is-collapsed': isCollapsed }">
        <div class="sidebar-inner">
            <section class="sidebar-group">
                <nav class="sidebar-nav">
                    <!-- Super Admin Links -->
                    <Link v-if="hasPermission('userView')" class="sidebar_subtitles"
                        :class="{ 'active': page.url === '/users' }" href="/users" :title="isCollapsed ? 'User Listing' : null">
                        <img class="sidebar_dark_icon" src="/images/user_listing.svg" alt="users" />
                        <span v-if="!isCollapsed">User Listing</span>
                    </Link>
                    <Link v-if="hasPermission('instructorListing')" class="sidebar_subtitles"
                        :class="{ 'active': page.url === '/admin/instructors' }" href="/admin/instructors" :title="isCollapsed ? 'Instructor Listing' : null">
                        <img class="sidebar_dark_icon" src="/images/instructor_listing_icon.svg" alt="instructors" />
                        <span v-if="!isCollapsed">Instructor Listing</span>
                    </Link>
                    <Link v-if="hasPermission('pricingUpdate')" class="sidebar_subtitles"
                        :class="{ 'active': page.url === '/admin/pricings' }" :href="route('pricings.index')" :title="isCollapsed ? 'Pricing' : null">
                        <img class="sidebar_dark_icon pricing-icon" src="/images/pricing_icon.svg" alt="Pricing" />
                        <span v-if="!isCollapsed">Pricing</span>
                    </Link>
                    <Link v-if="hasPermission('coursemanagement')" class="sidebar_subtitles"
                        :class="{ 'active': page.url === '/course-management' }" href="/course-management" :title="isCollapsed ? 'Course Management' : null">
                        <img class="sidebar_dark_icon" src="/images/course.svg" alt="Course management" />
                        <span v-if="!isCollapsed">Course Management</span>
                    </Link>
                    <Link v-if="hasPermission('metatagsUpdate')" class="sidebar_subtitles"
                        :class="{ 'active': page.url === '/metatags' }" href="/metatags" :title="isCollapsed ? 'Meta Tags' : null">
                        <img class="sidebar_dark_icon" src="/images/meta.svg" alt="Meta tags" />
                        <span v-if="!isCollapsed">Meta Tags</span>
                    </Link>
                    <Link v-if="hasPermission('communitySettingsView')" class="sidebar_subtitles"
                        :class="{ 'active': page.url === '/communitysettings' }" :href="route('communitysettings')" :title="isCollapsed ? 'Community Settings' : null">
                        <img class="sidebar_dark_icon" src="/images/career_icon.svg" alt="Community settings" />
                        <span v-if="!isCollapsed">Community Settings</span>
                    </Link>
                    <Link v-if="hasPermission('marketingmanagement')" class="sidebar_subtitles"
                        :class="{ 'active': page.url === '/admin/marketing' }" :href="route('admin.marketing.index')" :title="isCollapsed ? 'Marketing' : null">
                        <img class="sidebar_dark_icon" src="/images/meta.svg" alt="Marketing" />
                        <span v-if="!isCollapsed">Marketing</span>
                    </Link>

                    <!-- Student Links -->
                    <Link v-if="hasPermission('dashboardView')" class="sidebar_subtitles"
                        :class="{ 'active': page.url === '/dashboard' }" href="/dashboard" :title="isCollapsed ? 'Home' : null">
                        <img class="sidebar_dark_icon" src="/images/home_icon.svg" alt="Home" />
                        <span v-if="!isCollapsed">Home</span>
                    </Link>
                    <Link v-if="hasPermission('careerJourneyView')" class="sidebar_subtitles"
                        :class="{ 'active': page.url === '/my-career-journey' }" href="/my-career-journey" :title="isCollapsed ? 'My Career Journey' : null">
                        <img class="sidebar_dark_icon" src="/images/career_icon.svg" alt="My career journey" />
                        <span v-if="!isCollapsed">My Career Journey</span>
                    </Link>

                    <!-- Common Link v-if="hasPermission('groupsView')" -->
                    <Link v-if="hasPermission('communityView')" class="sidebar_subtitles"
                        :class="{ 'active': page.url === '/community' }" :href="route('community')" :title="isCollapsed ? 'Community' : null">
                        <img class="sidebar_dark_icon" src="/images/community_icon.svg" alt="Community" />
                        <span v-if="!isCollapsed">Community</span>
                    </Link>
                    <Link class="sidebar_subtitles" :class="{ 'active': page.url.startsWith('/groups') }"
                        :href="route('groups.index')" :title="isCollapsed ? 'Groups' : null">
                        <img class="sidebar_dark_icon" src="/images/groups_icon.svg" alt="Groups" />
                        <span v-if="!isCollapsed">Groups</span>
                    </Link>
                    <Link class="sidebar_subtitles" :class="{ 'active': page.url.startsWith('/jobs') }"
                        :href="route('jobs.index')" :title="isCollapsed ? 'Jobs' : null">
                        <img class="sidebar_dark_icon" src="/images/jobs_icon.svg" alt="Jobs" />
                        <span v-if="!isCollapsed">Jobs</span>
                    </Link>
                </nav>
            </section>

            <div class="sidebar-divider"></div>

            <section class="sidebar-group">
                <header class="sidebar-heading" v-if="!isCollapsed">Learn</header>
                <nav class="sidebar-nav">
                    <!-- Student Links -->
                    <Link v-if="hasPermission('libraryView')" class="sidebar_subtitles"
                        :class="{ 'active': page.url === '/library' }" href="/library" :title="isCollapsed ? 'My Library' : null">
                        <img class="sidebar_dark_icon" src="/images/library_icon.svg" alt="Library" />
                        <span v-if="!isCollapsed">My Library</span>
                    </Link>
                    <Link v-if="hasPermission('contentView')" class="sidebar_subtitles"
                        :class="{ 'active': page.url === '/content' }" href="/content" :title="isCollapsed ? 'Content' : null">
                        <img class="sidebar_dark_icon" src="/images/content_icon.svg" alt="Content" />
                        <span v-if="!isCollapsed">Content</span>
                    </Link>
                    <Link v-if="isStudent" class="sidebar_subtitles"
                        :class="{ 'active': page.url === '/billing' }" :href="route('billing.portal')" :title="isCollapsed ? 'Billing' : null">
                        <img class="sidebar_dark_icon" src="/images/pricing_icon.svg" alt="Billing" />
                        <span v-if="!isCollapsed">Billing</span>
                    </Link>

                    <!-- Instructor/Admin Links -->
                    <Link v-if="hasPermission('mycourses')" class="sidebar_subtitles"
                        :class="{ 'active': page.url === '/coursess' }" href="/coursess" :title="isCollapsed ? 'My Courses' : null">
                        <img class="sidebar_dark_icon" src="/images/courses_icon.svg" alt="Courses" />
                        <span v-if="!isCollapsed">My Courses</span>
                    </Link>
                    <Link v-if="hasPermission('addnewcourses')" class="sidebar_subtitles"
                        :class="{ 'active': page.url === '/addnewcourses' }" href="/addnewcourses" :title="isCollapsed ? 'Add New Courses' : null">
                        <img class="sidebar_dark_icon" src="/images/add_icon.svg" alt="Add new courses" />
                        <span v-if="!isCollapsed">Add New Courses</span>
                    </Link>
                </nav>
            </section>

            <div class="sidebar-divider"></div>

            <section class="sidebar-group sidebar-group--footer">
                <template v-if="isStudent">
                    <header class="sidebar-heading" v-if="!isCollapsed">Trending Topics</header>
                    <nav class="sidebar-nav">
                        <Link v-for="topic in trendingTopicsList" :key="topic.id" class="sidebar_subtitles"
                            :class="{ 'active': page.url === ('/topic/' + topic.name) }" :href="'/topic/' + topic.name"
                            :title="isCollapsed ? topic.name : null">
                            <span class="sidebar-topic-dot" aria-hidden="true"></span>
                            <span v-if="!isCollapsed">{{ topic.name }}</span>
                        </Link>
                    </nav>
                    <div v-if="trendingTopicsList.length && !isCollapsed" class="sidebar-divider subtle"></div>
                </template>
                <!-- Common link -->
                <nav class="sidebar-nav">
                    <Link class="sidebar_subtitles" :class="{ 'active': page.url === '/help' }" href="/help" :title="isCollapsed ? 'Help' : null">
                        <img class="sidebar_dark_icon" src="/images/help_icon.svg" alt="Help" />
                        <span v-if="!isCollapsed">Help</span>
                    </Link>
                </nav>
            </section>
        </div>
    </aside>
</template>

<script setup>
import { ref, watch, computed, onMounted } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import axios from 'axios';
import { hasPermission } from '@/permissions.js';

const props = defineProps({
    isCollapsed: Boolean,
});

const page = usePage();
const user = computed(() => page.props.auth.user);

const isStudent = computed(() => user.value?.type === 'student');

const sidebarVisible = ref(true);
const trendingTopicsList = ref([]);

watch(
    () => page.url,
    (newUrl) => {
        sidebarVisible.value = newUrl !== '/cart';
    },
    { immediate: true }
);

const fetchTrendingTopics = async () => {
    try {
        const response = await axios.get('/trending-topics-list');
        trendingTopicsList.value = response.data;
    } catch (error) {
        console.error("Error fetching trending topics:", error);
    }
};

onMounted(fetchTrendingTopics);
</script>

<style scoped>
.main_sidebar {
    width: 300px;
    position: fixed;
    top: 5rem;
    left: 0;
    height: calc(100vh - 5rem);
    z-index: 100;
    padding: 28px 0;
    background: linear-gradient(180deg, #f9fbff 0%, #e9f1ff 100%);
    border: 1px solid rgba(15, 33, 46, 0.08);
    box-shadow: 0 12px 32px rgba(15, 33, 46, 0.12);
    transition: width 0.3s ease, padding 0.3s ease, border-radius 0.3s ease;
    overflow: hidden; /* hide parent scrollbar; inner handles scrolling */
    flex-shrink: 0;
    color: #22354a;
    padding-bottom: 0px;
}

.dark .main_sidebar {
    background: linear-gradient(180deg, #162637 0%, #0b1a27 100%);
    border: 1px solid rgba(255, 255, 255, 0.05);
    box-shadow: 0 12px 32px rgba(11, 26, 37, 0.45);
    color: #dce6f3;
}

.sidebar-inner {
    height: 100%;
    display: flex;
    flex-direction: column;
    gap: 28px;
    padding: 0 14px 24px;
    overflow-y: auto; /* scroll content but hide scrollbar visuals */
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none; /* IE/Edge */
}

.sidebar-inner::-webkit-scrollbar {
    width: 0;
    height: 0;
}

.sidebar-group {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.sidebar-group--footer {
    margin-top: auto;
}

.sidebar-nav {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.sidebar_subtitles {
    font-size: 16px;
    font-weight: 500;
    color: #324a63;
    padding: 12px 20px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    gap: 16px;
    position: relative;
    transition: background-color 0.25s ease, color 0.25s ease, transform 0.25s ease;
    text-decoration: none;
}

.sidebar_subtitles::before {
    content: "";
    position: absolute;
    inset: 1px auto 1px 8px;
    width: 7px;
    border-radius:3px;
    background: #3e7be0;
    left: 1px;
    opacity: 0;
    transition: opacity 0.25s ease;
}

.sidebar_subtitles:hover {
    background: rgba(62, 123, 224, 0.12);
    transform: translateX(4px);
    color: #0f2947;
}

.sidebar_subtitles:hover::before {
    opacity: 0.5;
}

.sidebar_subtitles.active {
    background: rgba(62, 123, 224, 0.18);
    color: #0b2440;
    box-shadow: inset 0 0 0 1px rgba(62, 123, 224, 0.28);
}

.sidebar_subtitles.active::before {
    opacity: 1;
}

.dark .sidebar_subtitles {
    color: #d7e4f1;
}

.dark .sidebar_subtitles:hover {
    background: rgba(140, 195, 255, 0.12);
    color: #ffffff;
}

.dark .sidebar_subtitles.active {
    background: rgba(104, 198, 255, 0.22);
    color: #ffffff;
    box-shadow: inset 0 0 0 1px rgba(104, 198, 255, 0.35);
}

.dark .sidebar_subtitles::before {
    background: #58cfff;
}

.sidebar_subtitles span {
    flex: 1;
    min-width: 0;
    text-align: left;
}

.sidebar_dark_icon {
    width: 22px;
    height: 22px;
    object-fit: contain;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.35));
    flex-shrink: 0;
}

.dark .sidebar_dark_icon {
    filter: brightness(0) invert(1) drop-shadow(0 2px 6px rgba(8, 18, 27, 0.55));
}

.sidebar_dark_icon.pricing-icon {
    filter: none;
}

.dark .sidebar_dark_icon.pricing-icon {
    filter: brightness(0) invert(1);
}

.sidebar-topic-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: rgba(62, 123, 224, 0.7);
    flex-shrink: 0;
    box-shadow: 0 0 0 2px rgba(62, 123, 224, 0.18);
}

.dark .sidebar-topic-dot {
    background: rgba(140, 195, 255, 0.7);
    box-shadow: 0 0 0 2px rgba(140, 195, 255, 0.15);
}

.sidebar-heading {
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: rgba(60, 84, 112, 0.65);
    padding: 0 20px;
}

.dark .sidebar-heading {
    color: rgba(215, 228, 241, 0.58);
}

.sidebar-divider {
    height: 1px;
    border-radius: 999px;
    background: linear-gradient(90deg, rgba(15, 33, 46, 0) 0%, rgba(52, 92, 131, 0.28) 50%, rgba(15, 33, 46, 0) 100%);
}

.dark .sidebar-divider {
    background: linear-gradient(90deg, rgba(255, 255, 255, 0) 0%, rgba(142, 169, 193, 0.35) 50%, rgba(255, 255, 255, 0) 100%);
}

.sidebar-divider.subtle {
    opacity: 0.4;
}

.main_sidebar.is-collapsed {
    width: 92px;
    padding: 28px 0;
}

.main_sidebar.is-collapsed .sidebar_subtitles {
    justify-content: center;
    padding: 12px;
    gap: 0;
}

.main_sidebar.is-collapsed .sidebar_subtitles::before {
    display: none;
}

.main_sidebar.is-collapsed .sidebar_dark_icon {
    margin: 0;
}

.main_sidebar.is-collapsed .sidebar-heading {
    display: none;
}

.main_sidebar.is-collapsed .sidebar_subtitles:hover {
    transform: none;
}

.main_sidebar.is-collapsed .sidebar_subtitles {
    border-radius: 12px;
}

.main_sidebar.is-collapsed .sidebar-topic-dot {
    margin: 0;
}

@media (max-width: 1024px) {
    .main_sidebar {
        position: fixed;
        /* top: 0; */
        left: 0;
        /* height: 100vh; */
        height: calc(100vh - 5rem);
        z-index: 1000;
        border-radius: 0 24px 24px 0;
    }
}

@media (max-width: 1024px) {
    .main_sidebar.sidebar-closed {
        left: -100%;
        opacity: 0;
        pointer-events: none;
    }
}
</style>
