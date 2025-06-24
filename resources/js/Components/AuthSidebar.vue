<template>

    <aside v-if="sidebarVisible" class="main_sidebar bg-white dark:bg-dark-bg-secondary shadow-md space-y-6">
        <!-- Optional Logo Section -->
        <!-- <div class="flex items-center justify-center">
            <Link :href="route('dashboard')">
                <ApplicationLogo class="h-10 w-auto text-indigo-600" />
            </Link>
        </div> -->

        <!-- <nav class="space-y-4">
            <div>
                
                <SidebarItem
                    v-for="item in menu"
                    :key="item.title"
                    :title="item.title"
                    :description="item.description"
                    :href="item.href"
                />
            </div>

            <div>
                <div>Learn</div>
                <SidebarItem
                    v-for="item in learn"
                    :key="item.title"
                    :title="item.title"
                    :description="item.description"
                    :href="item.href"
                />
            </div>

            <div>
                <div>Trending Topics</div>
                <SidebarItem
                    v-for="item in trendingTopic"
                    :key="item.title"
                    :title="item.title"
                    :description="item.description"
                    :href="item.href"
                />
            </div>
        </nav> -->
        <!-- <button class="sidebar_closebutton" @click="toggleSidebar">
            <img src="/images/sidebar_icon.svg">
        </button> -->
        <!-- <div class="logo">
            
                <Link  class="sidebar_subtitles"  href="/dashboard" > LOGO</Link>
                
            
            
        </div> -->
        <div style="">
            <div >
                <Link v-if="showUserListingLink" class="sidebar_subtitles" :class="{ 'active': page.url === '/users' }" href="/users"><img class="sidebar_dark_icon" src="/images/user.svg" alt="users"  /> User Listing</Link>
                <Link v-if="showUserListingLink" class="sidebar_subtitles" :class="{ 'active': page.url === '/admin/instructors' }" href="/admin/instructors"><img class="sidebar_dark_icon" src="/images/user.svg" alt="users"  />Instructor Listing</Link>
                
                <Link v-if="showUserListingLink" class="sidebar_subtitles" :class="{ 'active': page.url === '/course-management' }" href="/course-management"> <img class="sidebar_dark_icon" src="/images/course.svg" alt="users"  />Course Management</Link>
                <Link v-if="showUserListingLink" class="sidebar_subtitles" :class="{ 'active': page.url === '/metatags' }" href="/metatags"> <img class="sidebar_dark_icon" src="/images/meta.svg" alt="users"  />Meta Tags</Link>
                <Link v-if="!showUserListingLink" class="sidebar_subtitles" :class="{ 'active': page.url === '/dashboard' }" href="/dashboard"><img class="sidebar_dark_icon" src="/images/home_icon.svg" alt="Logo"  /> Home</Link>
                <Link v-if="!showUserListingLink" class="sidebar_subtitles" :class="{ 'active': page.url === '/my-career-journey' }" href="/my-career-journey"><img class="sidebar_dark_icon" src="/images/career_icon.svg" alt="Career"  /> My Career Journey</Link>
               
            </div>

        </div>
         <div>
            <div ><div class="sidebar_titles">Learn</div>
                <Link v-if="!showUserListingLink" class="sidebar_subtitles" :class="{ 'active': page.url === '/library' }" href="/library"><img class="sidebar_dark_icon" src="/images/library_icon.svg" alt="Library"  /> My Library</Link>
                <Link v-if="!showUserListingLink" class="sidebar_subtitles" :class="{ 'active': page.url === '/content' }" href="/content"><img class="sidebar_dark_icon" src="/images/content_icon.svg" alt="Content"  /> Content</Link>
                <Link v-if="showUserListingLink" class="sidebar_subtitles" :class="{ 'active': page.url === '/coursess' }" href="/coursess"> <img class="sidebar_dark_icon" src="/images/courses_icon.svg" alt="Courses"  /> My Courses</Link>
                <Link v-if="showUserListingLink" class="sidebar_subtitles" :class="{ 'active': page.url === '/addnewcourses' }" href="/addnewcourses"> <img class="sidebar_dark_icon" src="/images/add_icon.svg" alt="Add"  />Add New Courses</Link>
            </div>

        </div>
        <div>
            <div v-if="!showUserListingLink">
                <div class="sidebar_titles">Trending Topics</div>
                <!-- <Link class="sidebar_subtitles" :class="{ 'active': page.url === '/leadershipAndManagement' }" href="/leadershipAndManagement">Leadership & Management</Link>
                <Link class="sidebar_subtitles" :class="{ 'active': page.url === '/artificialIntelligence' }" href="/artificialIntelligence">Artificial Intelligence</Link>
                <Link class="sidebar_subtitles" :class="{ 'active': page.url === '/cyberSecurity' }" href="/cyberSecurity">Cyber Security</Link> -->
                <Link v-for="topic in trendingTopicsList" :key="topic.id" class="sidebar_subtitles"
                    :class="{ 'active': page.url === ('/topic/' + topic.name) }" :href="'/topic/' + topic.name">
                {{ topic.name }}
                </Link>
                <!-- <Link class="sidebar_subtitles" :class="{ 'active': page.url === '/Instructor' }" href="/Instructor">Become an Instructor</Link> -->

            </div>
            <Link class="sidebar_subtitles" :class="{ 'active': page.url === '/help' }" href="/help">Help <img class="sidebar_dark_icon" src="/images/help_icon.svg" alt="Help"  /></Link>
            
        </div>
    </aside>
</template>

<script setup>
import { ref, watch } from 'vue';
import SidebarItem from './SidebarItem.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { usePage, Link } from '@inertiajs/vue3';
import axios from 'axios';

const page = usePage();

const showUserListingLink = ref(false);
const sidebarVisible = ref(true);
const trendingTopicsList = ref([]);

// 👇 Watch route changes to show/hide sidebar
watch(
    () => page.url,
    (newUrl) => {
        sidebarVisible.value = newUrl !== '/cart';
    },
    { immediate: true }
);

// 👇 Fetch permissions for User Listing link
const fetchPermissions = async () => {
    try {
        const response = await axios.post('/check-permissions', {
            permissions: ['userAdd']
        });
        if (response.data?.permissions?.userAdd) {
            showUserListingLink.value = true;
        }
    } catch (error) {
        console.error("Error checking permissions:", error);
    }
};

// 👇 Fetch trending topics
const fetchTrendingTopics = async () => {
    try {
        const response = await axios.get('/trending-topics-list');
        trendingTopicsList.value = response.data;
    } catch (error) {
        console.error("Error fetching trending topics:", error);
    }
};

// Run on mount
fetchPermissions();
fetchTrendingTopics();

// Static menu arrays (if needed elsewhere)
const menu = [
    { title: 'Home', href: '/dashboard' },
    { title: 'My Career Journey', href: '/careerJourney' },
];

const learn = [
    { title: 'My Library', href: '/library' },
    { title: 'Content', href: '/content' },
    { title: 'My Courses', href: '/courses' },
];

const trendingTopic = [
    { title: 'Leadership & Management', href: '/leadershipManagement' },
    { title: 'Artificial Intelligence', href: '/artificialIntelligence' },
    { title: 'Cyber Security', href: '/cyberSecurity' },
    { title: 'Become an Instructor', href: '/instructor' },
    { title: 'Help', href: '/help' },
];

// Example for a static dropdown
const staticDropdowns = ref({
    // 'Users': [
    //     { title: 'My Career Journey', href: '/careerJourney' },
    //     { title: 'My Public Profile', href: '/profile' }
    // ]
});

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
