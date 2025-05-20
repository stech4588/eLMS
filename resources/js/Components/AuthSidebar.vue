<template>
    <button class="sidebar_openbutton" @click="toggleSidebar">
            <img src="/images/sidebar_icon.svg">
        </button>
    <aside class="main_sidebar bg-white shadow-md space-y-6" :class="{ 'sidebar-closed': !isSidebarOpen }">
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
        <button class="sidebar_closebutton" @click="toggleSidebar">
            <img src="/images/sidebar_icon.svg">
        </button>
        
        <div>
            <div >
                <Link  class="sidebar_subtitles" :class="{ 'active': page.url === '/dashboard' }" href="/dashboard"><img src="/images/home_icon.svg" alt="Logo"  /> Home</Link>
                <Link class="sidebar_subtitles" :class="{ 'active': page.url === '/careerJourney' }" href="/careerJourney"><img src="/images/career_icon.svg" alt="Career"  /> My Career Journey</Link>
            </div>
            
        </div>
         <div>
            <div ><div class="sidebar_titles">Learn</div>
                <Link class="sidebar_subtitles" :class="{ 'active': page.url === '/library' }" href="/library"><img src="/images/library_icon.svg" alt="Library"  /> My Library</Link>
                <Link class="sidebar_subtitles" :class="{ 'active': page.url === '/content' }" href="/content"><img src="/images/content_icon.svg" alt="Content"  /> Content</Link>
                <Link class="sidebar_subtitles" :class="{ 'active': page.url === '/coursess' }" href="/coursess"> <img src="/images/courses_icon.svg" alt="Courses"  /> My Courses</Link>
            </div>
            
        </div>
         <div>
            <div ><div class="sidebar_titles">Trending Topics</div>
                <Link class="sidebar_subtitles" :class="{ 'active': page.url === '/leadershipAndManagement' }" href="/leadershipAndManagement">Leadership & Management</Link>
                <Link class="sidebar_subtitles" :class="{ 'active': page.url === '/artificialIntelligence' }" href="/artificialIntelligence">Artificial Intelligence</Link>
                <Link class="sidebar_subtitles" :class="{ 'active': page.url === '/cyberSecurity' }" href="/cyberSecurity">Cyber Security</Link>
                <Link class="sidebar_subtitles" :class="{ 'active': page.url === '/Instructor' }" href="/Instructor">Become an Instructor</Link>
                <Link class="sidebar_subtitles" :class="{ 'active': page.url === '/help' }" href="/help">Help <img src="/images/help_icon.svg" alt="Help"  /></Link>
            </div>
            
        </div>
    </aside>
</template>

<script setup>
import { ref } from 'vue';
import SidebarItem from './SidebarItem.vue'
import ApplicationLogo from '@/Components/ApplicationLogo.vue'
import { usePage,Link } from '@inertiajs/vue3'
const page = usePage()
const isSidebarOpen = ref(false)

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value
}

const menu = [
    { title: 'Home', href: '/dashboard' },
    { title: 'My Career Journey', href: '/careerJourney' },
]

const learn = [
    { title: 'My Library', href: '/library' },
    { title: 'Content', href: '/content' },
    { title: 'My Courses', href: '/courses' },
]

const trendingTopic = [
    { title: 'Leadership & Management', href: '/leadershipManagement' },
    { title: 'Artificial Intelligence', href: '/artificialIntelligence' },
    { title: 'Cyber Security', href: '/cyberSecurity' },
    { title: 'Become an Instructor', href: '/instructor' },
    { title: 'Help', href: '/help' },
]
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
.active {
    background-color: #DBDBDB;
    border-radius: 4px;
    border-left: 5px solid #312f2f;
    border-radius: 0;
    padding-left: 19px;
}
.sidebar_titles{
    font-size: 13px;
    font-weight: 400;
    color: #000000;
    padding-top: 13px;
    padding-bottom: 13px;
    padding-left: 24px;
    padding-right: 10px;
    letter-spacing: 1px;
    
}
.main_sidebar{
    width: 320px;
   transition: left 0.3s ease-in-out, opacity 0.3s ease-in-out;
    overflow: hidden;
}
@media (max-width: 770px) {
.main_sidebar.sidebar-closed {
    left: 0;
    padding: 0;
    opacity: 0;
}}
@media (max-width: 770px) {
    .main_sidebar{
        position: absolute;
        height: 100vh;
        z-index: 1000;
        overflow-y: auto;
        
    }
    
}

.title_head{
    display: flex;
    
    
}
.sidebar_closebutton{
    display: none;
     cursor: pointer;
}

@media (max-width: 770px) {
    .sidebar_closebutton{
        display: block;
        padding-left: 24px;
        padding-top: 24px;
    }
    
}
.sidebar_openbutton{
    position: absolute;
    padding-left: 24px;
    padding-top: 24px;
}
@media (min-width: 770px) {
    .sidebar_openbutton{
        display: none;
       
    }
    
}

</style>
