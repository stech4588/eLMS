<template>
    <aside v-if="sidebarVisible" class="main_sidebar bg-white dark:bg-dark-bg-secondary shadow-md space-y-6">
        <div style="">
            <div>
                <!-- Super Admin Links -->
                <Link v-if="hasPermission('userView')" class="sidebar_subtitles" :class="{ 'active': page.url === '/users' }" href="/users"><img class="sidebar_dark_icon" src="/images/user.svg" alt="users"  /> User Listing</Link>
                <Link v-if="hasPermission('instructorListing')" class="sidebar_subtitles" :class="{ 'active': page.url === '/admin/instructors' }" href="/admin/instructors"><img class="sidebar_dark_icon" src="/images/user.svg" alt="users"  />Instructor Listing</Link>
                <Link v-if="hasPermission('pricingUpdate')" class="sidebar_subtitles" :class="{ 'active': page.url === '/admin/pricings' }" :href="route('pricings.index')"><img class="sidebar_dark_icon" src="/images/user.svg" alt="users"  />Pricing</Link>
                <Link v-if="hasPermission('coursemanagement')" class="sidebar_subtitles" :class="{ 'active': page.url === '/course-management' }" href="/course-management"> <img class="sidebar_dark_icon" src="/images/course.svg" alt="users"  />Course Management</Link>
                <Link v-if="hasPermission('metatagsUpdate')" class="sidebar_subtitles" :class="{ 'active': page.url === '/metatags' }" href="/metatags"> <img class="sidebar_dark_icon" src="/images/meta.svg" alt="users"  />Meta Tags</Link>
                <Link v-if="hasPermission('communitySettingsView')" class="sidebar_subtitles" :class="{ 'active': page.url === '/communitysettings' }" :href="route('communitysettings')"><img class="sidebar_dark_icon" src="/images/career_icon.svg" alt="Career"  /> Community Settings</Link>

                <!-- Student Links -->
                <Link v-if="hasPermission('dashboardView')" class="sidebar_subtitles" :class="{ 'active': page.url === '/dashboard' }" href="/dashboard"><img class="sidebar_dark_icon" src="/images/home_icon.svg" alt="Logo"  /> Home</Link>
                <Link v-if="hasPermission('careerJourneyView')" class="sidebar_subtitles" :class="{ 'active': page.url === '/my-career-journey' }" href="/my-career-journey"><img class="sidebar_dark_icon" src="/images/career_icon.svg" alt="Career"  /> My Career Journey</Link>
                
                <!-- Common Link -->
                 <Link v-if="hasPermission('communityView')" class="sidebar_subtitles" :class="{ 'active': page.url === '/community' }" :href="route('community')"><img class="sidebar_dark_icon" src="/images/career_icon.svg" alt="Career"  /> Community</Link>
            </div>
        </div>
        <div>
            <div>
                <div class="sidebar_titles">Learn</div>
                <!-- Student Links -->
                <Link v-if="hasPermission('libraryView')" class="sidebar_subtitles" :class="{ 'active': page.url === '/library' }" href="/library"><img class="sidebar_dark_icon" src="/images/library_icon.svg" alt="Library"  /> My Library</Link>
                <Link v-if="hasPermission('contentView')" class="sidebar_subtitles" :class="{ 'active': page.url === '/content' }" href="/content"><img class="sidebar_dark_icon" src="/images/content_icon.svg" alt="Content"  /> Content</Link>
                
                <!-- Instructor/Admin Links -->
                <Link v-if="hasPermission('mycourses')" class="sidebar_subtitles" :class="{ 'active': page.url === '/coursess' }" href="/coursess"> <img class="sidebar_dark_icon" src="/images/courses_icon.svg" alt="Courses"  /> My Courses</Link>
                <Link v-if="hasPermission('addnewcourses')" class="sidebar_subtitles" :class="{ 'active': page.url === '/addnewcourses' }" href="/addnewcourses"> <img class="sidebar_dark_icon" src="/images/add_icon.svg" alt="Add"  />Add New Courses</Link>
            </div>
        </div>
        <div>
            <div v-if="isStudent">
                <div class="sidebar_titles">Trending Topics</div>
                <Link v-for="topic in trendingTopicsList" :key="topic.id" class="sidebar_subtitles"
                    :class="{ 'active': page.url === ('/topic/' + topic.name) }" :href="'/topic/' + topic.name">
                {{ topic.name }}
                </Link>
            </div>
            <!-- Common link -->
            <Link v-if="hasPermission('helpView')" class="sidebar_subtitles" :class="{ 'active': page.url === '/help' }" href="/help">Help <img class="sidebar_dark_icon" src="/images/help_icon.svg" alt="Help"  /></Link>
        </div>
    </aside>
</template>

<script setup>
import { ref, watch, computed, onMounted } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import axios from 'axios';
import { hasPermission } from '@/permissions.js';

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
.sidebar_subtitles {
    font-size: 20px;
    font-weight: 400;
    color: black;
    padding-top: 13px;
    padding-bottom: 13px;
    padding-left: 24px;
    padding-right: 10px;
    display: flex;
    gap: 6px;
    cursor: pointer;
}

.dark .sidebar_subtitles {
    color: white;
}
.dark .sidebar_dark_icon{
    filter: invert(1);
    width: 23px;
}
 .sidebar_dark_icon{
    width: 23px;
}
.active {
    background-color: #97d5ff;
    border-radius: 4px;
    border-left: 5px solid #312f2f;
    border-radius: 0;
    padding-left: 19px;
}

.dark .active {
    background-color: #1a1a1a;
    border-left: 5px solid #ffffff;
}

.sidebar_titles {
    font-size: 13px;
    font-weight: 400;
    color: #000000;
    padding-top: 13px;
    padding-bottom: 13px;
    padding-left: 24px;
    padding-right: 10px;
    letter-spacing: 1px;
    display: flex;
    justify-content: flex-start;
}

.dark .sidebar_titles {
    color: white;
}

.main_sidebar {
    width: 320px;
    transition: left 0.3s ease-in-out, opacity 0.3s ease-in-out;
    overflow: hidden;
    flex-shrink: 0;
}

@media (max-width: 770px) {
    .main_sidebar.sidebar-closed {
        left: 0;
        padding: 0;
        opacity: 0;
        display: none;
    }
}

@media (max-width: 770px) {
    .main_sidebar {
        position: absolute;
        height: 100vh;
        z-index: 1000;
        overflow-y: auto;

    }

}

@media (min-width: 770px) {
    .main_sidebar {

        min-height: 854px;
    }

}

.title_head {
    display: flex;


}

.sidebar_closebutton {
    display: none;
    cursor: pointer;
}

@media (max-width: 770px) {
    .sidebar_closebutton {
        display: block;
        padding-left: 24px;
        padding-top: 24px;
    }

}

.sidebar_openbutton {
    position: absolute;
    padding-left: 24px;
    padding-top: 24px;
}

@media (min-width: 770px) {
    .sidebar_openbutton {
        display: none;

    }

}

.logo {
    margin-top: 0 !important;
}
</style>
