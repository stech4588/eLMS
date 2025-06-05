<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="bg-gray-100"
            style="padding: 0px;margin: 13px; margin-top: 0px; justify-content: center; display: flex;background-color: #97d5ff; border-radius: 16px;">



            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-10 "
                style="padding: 0px;margin: 0px;  width: 100%; background-color: white; border-radius: 16px;">
                <div class="main_filter_container" style="background-color: white; display: flex; justify-content: space-between; padding: 10px; border-radius: 16px; padding-top: 25px;">
                    <div style=" display: flex; align-items: center; ">
                        <img src="/images/search_icon.svg" alt="Search" class="search_icon" style="position: absolute; margin-left: 10px;  "/>
                        <input class="search_input" type="text" placeholder="Search Courses..." v-model="searchQuery"
                            style="border-radius: 10px; border: 1px solid #7E7E7E; padding: 10px; padding-left: 30px; " />
                    </div>
                    <div class="filter-container">
                        <div class="filter_select_container" style="display: flex; gap: 10px; flex-wrap: wrap;justify-content: end;">
                            <!-- Topics Dropdown -->
                            <div class="dropdown_dashboard" ref="topicDropdownRef" style="position: relative; ">
                                <button @click="toggleTopicDropdown" class="btn btn-outline-secondary dropdown-toggle" type="button" style="width: 100%; display: flex; justify-content: space-between; align-items: center; border-radius: 4px; border: 1px solid #7E7E7E; padding: 0.375rem 0.75rem;">
                                    <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ selectedTopicText || 'Topics' }}</span>
                                    <img src="/images/dropdown_arrow.svg" alt="Dropdown Arrow" class="dropdown_arrow" />
                                </button>
                                <ul v-if="isTopicDropdownOpen" class="dropdown-menu show" style="position: absolute; top: 100%; overflow-y: auto; max-height: 200px; left: 0; width: 100%; z-index: 1000; min-width: auto; padding: 0.5rem 0; margin: 0.125rem 0 0; font-size: 1rem; color: #212529; text-align: left; list-style: none; background-color: #fff; background-clip: padding-box; border: 1px solid rgba(0,0,0,.15); border-radius: 0.25rem;">
                                    <li><a class="dropdown-item" href="#" @click.prevent="handleTopicSelect({ value: '', text: 'Topics' })" style="display: block; width: 100%; padding: 0.25rem 1.5rem; clear: both; font-weight: 400; color: #212529; text-align: inherit; white-space: nowrap; background-color: transparent; border: 0;">Topics (All)</a></li>
                                    <li v-for="option in topicOptions" :key="option.value">
                                        <a class="dropdown-item" href="#" @click.prevent="handleTopicSelect(option)" style="display: block; width: 100%; padding: 0.25rem 1.5rem; clear: both; font-weight: 400; color: #212529; text-align: inherit; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; background-color: transparent; border: 0;">
                                            {{ option.text }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!-- Course Type Dropdown -->
                            <div class="dropdown_dashboard" ref="courseTypeDropdownRef" style="position: relative; ">
                                <button @click="toggleCourseTypeDropdown" class="btn btn-outline-secondary dropdown-toggle" type="button" style="width: 100%; display: flex; justify-content: space-between; align-items: center; border-radius: 4px; border: 1px solid #7E7E7E; padding: 0.375rem 0.75rem;">
                                    <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ selectedCourseTypeText || 'Course Type' }}</span>
                                    <img src="/images/dropdown_arrow.svg" alt="Dropdown Arrow" class="dropdown_arrow" />
                                </button>
                                <ul v-if="isCourseTypeDropdownOpen" class="dropdown-menu show" style="position: absolute;overflow: hidden; top: 100%; left: 0; width: 100%; z-index: 1000; min-width: auto; padding: 0.5rem 0; margin: 0.125rem 0 0; font-size: 1rem; color: #212529; text-align: left; list-style: none; background-color: #fff; background-clip: padding-box; border: 1px solid rgba(0,0,0,.15); border-radius: 0.25rem;">
                                    <li><a class="dropdown-item" href="#" @click.prevent="handleCourseTypeSelect({ value: '', text: 'Course Type' })" style="display: block; width: 100%; padding: 0.25rem 1.5rem; clear: both; font-weight: 400; color: #212529; text-align: inherit; white-space: nowrap; background-color: transparent; border: 0;">Course Type (All)</a></li>
                                    <li v-for="option in courseTypeOptions" :key="option.value">
                                        <a class="dropdown-item" href="#" @click.prevent="handleCourseTypeSelect(option)" style="display: block; width: 100%; padding: 0.25rem 1.5rem; clear: both; font-weight: 400; color: #212529; text-align: inherit; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; background-color: transparent; border: 0;">
                                            {{ option.text }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!-- Certificate Dropdown -->
                            <div class="dropdown_dashboard" ref="certificateDropdownRef" style="position: relative; ;">
                                <button @click="toggleCertificateDropdown" class="btn btn-outline-secondary dropdown-toggle" type="button" style="width: 100%; display: flex; justify-content: space-between; align-items: center; border-radius: 4px; border: 1px solid #7E7E7E; padding: 0.375rem 0.75rem;">
                                    <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ selectedCertificateText || 'Certificate' }}</span>
                                    <img src="/images/dropdown_arrow.svg" alt="Dropdown Arrow" class="dropdown_arrow" />
                                </button>
                                <ul v-if="isCertificateDropdownOpen" class="dropdown-menu show" style="position: absolute; overflow: hidden; top: 100%; left: 0; width: 100%; z-index: 1000; min-width: auto; padding: 0.5rem 0; margin: 0.125rem 0 0; font-size: 1rem; color: #212529; text-align: left; list-style: none; background-color: #fff; background-clip: padding-box; border: 1px solid rgba(0,0,0,.15); border-radius: 0.25rem;">
                                    <li><a class="dropdown-item" href="#" @click.prevent="handleCertificateSelect({ value: '', text: 'Certificate' })" style="display: block; width: 100%; padding: 0.25rem 1.5rem; clear: both; font-weight: 400; color: #212529; text-align: inherit; white-space: nowrap; background-color: transparent; border: 0;">Certificate (All)</a></li>
                                    <li v-for="option in certificateOptions" :key="option.value">
                                        <a class="dropdown-item" href="#" @click.prevent="handleCertificateSelect(option)" style="display: block; width: 100%; padding: 0.25rem 1.5rem; clear: both; font-weight: 400; color: #212529; text-align: inherit; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; background-color: transparent; border: 0;">
                                            {{ option.text }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!-- Course Industry Dropdown -->
                            <div class="dropdown_dashboard" ref="courseIndustryDropdownRef" style="position: relative; ">
                                <button @click="toggleCourseIndustryDropdown" class="btn btn-outline-secondary dropdown-toggle" type="button" style="width: 100%; display: flex; justify-content: space-between; align-items: center; border-radius: 4px; border: 1px solid #7E7E7E; padding: 0.375rem 0.75rem;">
                                    <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ selectedCourseIndustryText || 'Course Industry' }}</span>
                                    <img src="/images/dropdown_arrow.svg" alt="Dropdown Arrow" class="dropdown_arrow" />
                                </button>
                                <ul v-if="isCourseIndustryDropdownOpen" class="dropdown-menu show" style="position: absolute; overflow: hidden; top: 100%; left: 0; width: 100%; z-index: 1000; min-width: auto; padding: 0.5rem 0; margin: 0.125rem 0 0; font-size: 1rem; color: #212529; text-align: left; list-style: none; background-color: #fff; background-clip: padding-box; border: 1px solid rgba(0,0,0,.15); border-radius: 0.25rem;">
                                    <li><a class="dropdown-item" href="#" @click.prevent="handleCourseIndustrySelect({ value: '', text: 'Course Industry' })" style="display: block; width: 100%; padding: 0.25rem 1.5rem; clear: both; font-weight: 400; color: #212529; text-align: inherit; white-space: nowrap; background-color: transparent; border: 0;">Course Industry (All)</a></li>
                                    <li v-for="option in courseIndustryOptions" :key="option.value">
                                        <a class="dropdown-item" href="#" @click.prevent="handleCourseIndustrySelect(option)" style="display: block; width: 100%; padding: 0.25rem 1.5rem; clear: both; font-weight: 400; color: #212529; text-align: inherit; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; background-color: transparent; border: 0;">
                                            {{ option.text }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>


                </div>
                <!-- Skills Section -->
                <div class="section_box">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-xl font-bold section-title">Because of Skills you Follow</h3>
                        <div class="space-x-2 main_left_right_button">
                            <button class="swiper-button-prev1 left_right_button"><img
                                    src="/images/left_side_icon.svg" /></button>
                            <button class="swiper-button-next1 left_right_button"><img
                                    src="/images/right_side_icon.svg" /></button>
                        </div>
                    </div>
                    <!-- Display Selected Filters -->
                    <div class="selected-filters-container mb-4" v-if="hasActiveFilters">
                        <span v-if="selectedTopicText" class="selected-filter-tag">
                            <span class="filter-text">{{ selectedTopicText }}</span>
                            <button @click="clearSelectedTopic" class="remove-filter-btn">&times;</button>
                        </span>
                        <span v-if="selectedCourseTypeText" class="selected-filter-tag">
                            <span class="filter-text">{{ selectedCourseTypeText }}</span>
                            <button @click="clearSelectedCourseTypeFilter" class="remove-filter-btn">&times;</button>
                        </span>
                        <span v-if="selectedCertificateText" class="selected-filter-tag">
                            <span class="filter-text">{{ selectedCertificateText }}</span>
                            <button @click="clearSelectedCertificate" class="remove-filter-btn">&times;</button>
                        </span>
                        <span v-if="selectedCourseIndustryText" class="selected-filter-tag">
                            <span class="filter-text">{{ selectedCourseIndustryText }}</span>
                            <button @click="clearSelectedCourseIndustry" class="remove-filter-btn">&times;</button>
                        </span>
                    </div>

                    <!-- <div class="flex gap-3 mb-4 main_skill_buttons">
                        <button @click="selectedCourseType = 'java'" :class="[
                            'px-4 py-1 border rounded-full skill_buttons',
                            selectedCourseType === 'java' ? 'bg-[#148ad9] text-white border-none' : ''
                        ]">
                            Java
                        </button>
                        <button @click="selectedCourseType = 'database'" :class="[
                            'px-4 py-1 border rounded-full skill_buttons',
                            selectedCourseType === 'database' ? 'bg-[#148ad9] text-white border-none' : ''
                        ]">
                            Database Development
                        </button>
                    </div> -->

                    <swiper :modules="modules" :navigation="{
                        nextEl: '.swiper-button-next1',
                        prevEl: '.swiper-button-prev1',
                    }" :slides-per-view="'auto'" :space-between="16" class="mySwiper">
                        <div class="list_of_courses"
                            style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); justify-content: space-around; align-items: center; gap: 20px; ">
                            <swiper-slide v-for="(course, index) in paginatedSkillBasedCourses" :key="`skill-${index}-${course.id}`" class=""
                                style="width: ">
                                <Link :href="route('courses.show', { course: course.id })">
                                    <div class="shrink-0 bg-white rounded-lg overflow-hidden">
                                        <img :src="getThumbnailSrc(course)" class="w-full  object-cover"
                                            alt="Course thumbnail" />
                                        <div class="p-2">
                                            <p class="text-xs text-gray-500">{{ course.type }}</p>
                                            <p class="text-sm font-semibold leading-tight title_hidden">{{ course.title }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">By: {{ course.author || "Placeholder Author" }}</p>
                                        </div>
                                    </div>
                                </Link>
                            </swiper-slide>
                        </div>
                    </swiper>
                    <div v-if="totalPagesSkills > 1" class="flex justify-center items-center mt-4 space-x-2">
                        <button @click="prevPageSkills" :disabled="currentPageSkills === 1"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-[#97d5ff] rounded-md hover:bg-[#63c0ff] disabled:opacity-50">
                            Previous
                        </button>
                        <span>Page {{ currentPageSkills }} of {{ totalPagesSkills }}</span>
                        <button @click="nextPageSkills" :disabled="currentPageSkills === totalPagesSkills"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-[#97d5ff] rounded-md hover:bg-[#63c0ff] disabled:opacity-50">
                            Next
                        </button>
                    </div>
                </div>

                <!-- New releases Section -->
                <!-- <div class="section_box">
                    <div class="flex justify-between items-center mb-2 release_top_section">
                        <h3 class="text-xl font-bold">New Releases</h3>
                        <div class="space-x-2 main_left_right_button">
                            <button class="swiper-button-prev2 left_right_button"><img
                                    src="/images/left_side_icon.svg" /></button>
                            <button class="swiper-button-next2 left_right_button"><img
                                    src="/images/right_side_icon.svg" /></button>
                        </div>
                    </div>

                    <swiper :modules="modules" :navigation="{
                        nextEl: '.swiper-button-next2',
                        prevEl: '.swiper-button-prev2',
                    }" :slides-per-view="'auto'" :space-between="16" class="mySwiper">
                        <swiper-slide v-for="(course, index) in paginatedNewReleaseCourses"
                            :key="`release-${course.id}-${index}`" class="w-40">
                            <div class="shrink-0 bg-white rounded-lg overflow-hidden">
                                <img :src="getThumbnailSrc(course)" class="w-full h-24 object-cover"
                                    alt="Course thumbnail" />
                                <div class="p-2">
                                    <p class="text-xs text-gray-500">{{ course.type }}</p>
                                    <p class="text-sm font-semibold leading-tight title_hidden">{{ course.title }}</p>
                                    
                                </div>
                            </div>
                        </swiper-slide>
                    </swiper>
                    
                    <div v-if="totalPages > 1" class="flex justify-center items-center mt-4 space-x-2">
                        <button @click="prevPage" :disabled="currentPage === 1"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 disabled:opacity-50">
                            Previous
                        </button>
                        <span>Page {{ currentPage }} of {{ totalPages }}</span>
                        <button @click="nextPage" :disabled="currentPage === totalPages"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 disabled:opacity-50">
                            Next
                        </button>
                    </div>
                </div> -->

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


                <!-- 30min or Less Section -->
                <!-- <div class="section_box">
                    <div class="flex justify-between items-center mb-2 release_top_section">
                        <h3 class="text-xl font-bold">30min or Less</h3>
                        <div class="space-x-2 main_left_right_button">
                            <button class="swiper-button-prev3 left_right_button"><img
                                    src="/images/left_side_icon.svg" /></button>
                            <button class="swiper-button-next3 left_right_button"><img
                                    src="/images/right_side_icon.svg" /></button>
                        </div>
                    </div>

                    <swiper :modules="modules" :navigation="{
                        nextEl: '.swiper-button-next3',
                        prevEl: '.swiper-button-prev3',
                    }" :slides-per-view="'auto'" :space-between="16" class="mySwiper">
                        <swiper-slide v-for="(newRelease, index) in displayedNewRelease" :key="`min-${index}`"
                            class="w-40">
                            <div class="shrink-0 bg-white rounded-lg overflow-hidden">
                                <img src="/images/release_thumbnail.svg" class="w-full h-24 object-cover"
                                    alt="Course thumbnail" />
                                <div class="p-2">
                                    <p class="text-xs text-gray-500">{{ newRelease.type }}</p>
                                    <p class="text-sm font-semibold leading-tight title_hidden">{{ newRelease.title }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">By: {{ newRelease.author }}</p>
                                </div>
                            </div>
                        </swiper-slide>
                    </swiper>
                </div> -->
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import 'swiper/css/navigation';
import { Navigation } from 'swiper/modules'; // Only Navigation for this Swiper setup, ensure Pagination is imported if used by other swipers

// Define props to receive data from the controller
const props = defineProps({
    courseTypes: Array,
    topics: Array,
    courseCertificates: Array,
    courseIndustries: Array,
    skillBasedCourses: Array,
    allNewReleaseCourses: Array,
});

// Function to get thumbnail URL, with fallback
const getThumbnailSrc = (course) => {
    return course.first_video_thumbnail_url ? course.first_video_thumbnail_url : '/images/skill_section_thumbnail.svg';
};

// Refs for selected values - assuming these already exist
const selectedTopics = ref([]);
const selectedCourseTypes = ref([]);
const selectedCertificates = ref([]);
const selectedCourseIndustries = ref([]);
const searchQuery = ref(''); // Added for search input

// Dropdown states
const isTopicDropdownOpen = ref(false);
const topicDropdownRef = ref(null); // Ref for the Topics dropdown element
const isCourseTypeDropdownOpen = ref(false);
const courseTypeDropdownRef = ref(null);
const isCertificateDropdownOpen = ref(false);
const certificateDropdownRef = ref(null);
const isCourseIndustryDropdownOpen = ref(false);
const courseIndustryDropdownRef = ref(null);

// Computed properties to format data for select options
const topicOptions = computed(() => {
    return props.topics ? props.topics.map(topic => ({ value: topic.id, text: topic.name })) : [];
});

const courseTypeOptions = computed(() => {
    return props.courseTypes ? props.courseTypes.map(ct => ({ value: ct.id, text: ct.name })) : [];
});

const certificateOptions = computed(() => {
    return props.courseCertificates ? props.courseCertificates.map(cert => ({ value: cert.id, text: cert.name })) : [];
});

const courseIndustryOptions = computed(() => {
    return props.courseIndustries ? props.courseIndustries.map(industry => ({ value: industry.id, text: industry.name })) : [];
});

// Computed properties to get text of selected options
const selectedTopicText = computed(() => {
    if (selectedTopics.value.length === 0) return '';
    const selectedTexts = selectedTopics.value.map(value => {
        const found = topicOptions.value.find(opt => opt.value === value);
        return found ? found.text : '';
    }).filter(text => text);
    return selectedTexts.join(', ');
});

const selectedCourseTypeText = computed(() => {
    if (selectedCourseTypes.value.length === 0) return '';
    const selectedTexts = selectedCourseTypes.value.map(value => {
        const found = courseTypeOptions.value.find(opt => opt.value === value);
        return found ? found.text : '';
    }).filter(text => text);
    return selectedTexts.join(', ');
});

const selectedCertificateText = computed(() => {
    if (selectedCertificates.value.length === 0) return '';
    const selectedTexts = selectedCertificates.value.map(value => {
        const found = certificateOptions.value.find(opt => opt.value === value);
        return found ? found.text : '';
    }).filter(text => text);
    return selectedTexts.join(', ');
});

const selectedCourseIndustryText = computed(() => {
    if (selectedCourseIndustries.value.length === 0) return '';
    const selectedTexts = selectedCourseIndustries.value.map(value => {
        const found = courseIndustryOptions.value.find(opt => opt.value === value);
        return found ? found.text : '';
    }).filter(text => text);
    return selectedTexts.join(', ');
});

const hasActiveFilters = computed(() => {
    return !!(selectedTopics.value.length || selectedCourseTypes.value.length || selectedCertificates.value.length || selectedCourseIndustries.value.length);
});

// Methods to clear filters
const clearSelectedTopic = () => {
    selectedTopics.value = [];
    isTopicDropdownOpen.value = false;
};

const clearSelectedCourseTypeFilter = () => {
    selectedCourseTypes.value = [];
    isCourseTypeDropdownOpen.value = false;
};

const clearSelectedCertificate = () => {
    selectedCertificates.value = [];
    isCertificateDropdownOpen.value = false;
};

const clearSelectedCourseIndustry = () => {
    selectedCourseIndustries.value = [];
    isCourseIndustryDropdownOpen.value = false;
};

const modules = [Navigation]; // Only Navigation is globally registered now unless other swipers need Pagination

// Pagination for Skills Section
const currentPageSkills = ref(1);
const itemsPerPageSkills = ref(9);

// Computed property that applies all filters (dropdowns and search)
const displayedCourses = computed(() => {
    let coursesToDisplay = props.skillBasedCourses || [];

    // Apply dropdown filters
    if (selectedTopics.value.length > 0) {
        coursesToDisplay = coursesToDisplay.filter(course => selectedTopics.value.includes(course.topic_id));
    }
    if (selectedCourseTypes.value.length > 0) {
        coursesToDisplay = coursesToDisplay.filter(course => selectedCourseTypes.value.includes(course.course_type_id));
    }
    if (selectedCertificates.value.length > 0) {
        coursesToDisplay = coursesToDisplay.filter(course => selectedCertificates.value.includes(course.certificate_id));
    }
    if (selectedCourseIndustries.value.length > 0) {
        coursesToDisplay = coursesToDisplay.filter(course => selectedCourseIndustries.value.includes(course.industry_id));
    }

    // Then apply search query filter
    if (searchQuery.value && searchQuery.value.trim() !== '') {
        const lowerSearchQuery = searchQuery.value.toLowerCase().trim();
        coursesToDisplay = coursesToDisplay.filter(course => {
            const titleMatch = course.title && course.title.toLowerCase().includes(lowerSearchQuery);
            const typeMatch = course.type && course.type.toLowerCase().includes(lowerSearchQuery);
            const authorMatch = course.author && typeof course.author === 'string' && course.author.toLowerCase().includes(lowerSearchQuery);
            return titleMatch || typeMatch || authorMatch;
        });
    }
    return coursesToDisplay;
});

// Computed properties for Skills Section Pagination
const totalFilteredCoursesCount = computed(() => displayedCourses.value.length);

const totalPagesSkills = computed(() => {
    if (totalFilteredCoursesCount.value === 0) return 1; // Avoid division by zero, ensure at least 1 page
    return Math.ceil(totalFilteredCoursesCount.value / itemsPerPageSkills.value);
});

const paginatedSkillBasedCourses = computed(() => {
    const start = (currentPageSkills.value - 1) * itemsPerPageSkills.value;
    const end = start + itemsPerPageSkills.value;
    return displayedCourses.value.slice(start, end); // Paginate the already filtered list
});

// Methods for Skills Section Pagination
function nextPageSkills() {
    if (currentPageSkills.value < totalPagesSkills.value) {
        currentPageSkills.value++;
    }
}

function prevPageSkills() {
    if (currentPageSkills.value > 1) {
        currentPageSkills.value--;
    }
}

// Dropdown toggle methods
const toggleTopicDropdown = () => {
    isTopicDropdownOpen.value = !isTopicDropdownOpen.value;
};

const toggleCourseTypeDropdown = () => {
    isCourseTypeDropdownOpen.value = !isCourseTypeDropdownOpen.value;
};

const toggleCertificateDropdown = () => {
    isCertificateDropdownOpen.value = !isCertificateDropdownOpen.value;
};

const toggleCourseIndustryDropdown = () => {
    isCourseIndustryDropdownOpen.value = !isCourseIndustryDropdownOpen.value;
};

// Dropdown select methods
const handleTopicSelect = (topic) => {
    const index = selectedTopics.value.indexOf(topic.value);
    if (index === -1) {
        selectedTopics.value.push(topic.value);
    } else {
        selectedTopics.value.splice(index, 1);
    }
};

const handleCourseTypeSelect = (option) => {
    const index = selectedCourseTypes.value.indexOf(option.value);
    if (index === -1) {
        selectedCourseTypes.value.push(option.value);
    } else {
        selectedCourseTypes.value.splice(index, 1);
    }
};

const handleCertificateSelect = (option) => {
    const index = selectedCertificates.value.indexOf(option.value);
    if (index === -1) {
        selectedCertificates.value.push(option.value);
    } else {
        selectedCertificates.value.splice(index, 1);
    }
};

const handleCourseIndustrySelect = (option) => {
    const index = selectedCourseIndustries.value.indexOf(option.value);
    if (index === -1) {
        selectedCourseIndustries.value.push(option.value);
    } else {
        selectedCourseIndustries.value.splice(index, 1);
    }
};

// Close dropdown on outside click
const handleClickOutside = (event) => {
    if (topicDropdownRef.value && !topicDropdownRef.value.contains(event.target)) {
        isTopicDropdownOpen.value = false;
    }
    if (courseTypeDropdownRef.value && !courseTypeDropdownRef.value.contains(event.target)) {
        isCourseTypeDropdownOpen.value = false;
    }
    if (certificateDropdownRef.value && !certificateDropdownRef.value.contains(event.target)) {
        isCertificateDropdownOpen.value = false;
    }
    if (courseIndustryDropdownRef.value && !courseIndustryDropdownRef.value.contains(event.target)) {
        isCourseIndustryDropdownOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});

// Watch for filter changes to reset current page for skills section
watch([selectedTopics, selectedCourseTypes, selectedCertificates, selectedCourseIndustries, searchQuery], () => {
    currentPageSkills.value = 1;
});

// Pagination for New Releases
const currentPage = ref(1);
const itemsPerPage = ref(9);

const totalPages = computed(() => {
    if (!props.allNewReleaseCourses || props.allNewReleaseCourses.length === 0) return 1;
    return Math.ceil(props.allNewReleaseCourses.length / itemsPerPage.value);
});

const paginatedNewReleaseCourses = computed(() => {
    if (!props.allNewReleaseCourses) return [];
    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return props.allNewReleaseCourses.slice(start, end);
});

function nextPage() {
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
    }
}

function prevPage() {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
}

const selectedCourseType = ref('java'); // default selected for other sections if still used

function showCourses(type) {
    selectedCourseType.value = type;
}

const courseJavaList = [
    {
        title: 'Spring Boost: Test-Driven Development',
        author: 'Syed Usman',
        type: 'Course'
    },
    {
        title: 'Java for Beginners',
        author: 'Ayesha Nadeem',
        type: 'Course'
    },
    {
        title: 'Spring Boost: Test-Driven Development',
        author: 'Syed Usman',
        type: 'Course'
    },
    {
        title: 'Java for Beginners',
        author: 'Ayesha Nadeem',
        type: 'Course'
    },
    {
        title: 'Spring Boost: Test-Driven Development',
        author: 'Syed Usman',
        type: 'Course'
    },
    {
        title: 'Java for Beginners',
        author: 'Ayesha Nadeem',
        type: 'Course'
    },
    {
        title: 'Spring Boost: Test-Driven Development',
        author: 'Syed Usman',
        type: 'Course'
    },
    {
        title: 'Java for Beginners',
        author: 'Ayesha Nadeem',
        type: 'Course'
    }
];

const courseDatabaseList = [
    {
        title: 'Summer Boost: Test-Driven Development',
        author: 'Syed Daniyal',
        type: 'Course'
    },
    {
        title: 'Database Design Basics',
        author: 'Talha Yousuf',
        type: 'Course'
    }
];

const activeFilters = computed(() => {
    return [
        { value: selectedTopics.value.join(','), text: selectedTopicText },
        { value: selectedCourseTypes.value.join(','), text: selectedCourseTypeText },
        { value: selectedCertificates.value.join(','), text: selectedCertificateText },
        { value: selectedCourseIndustries.value.join(','), text: selectedCourseIndustryText },
    ].filter(filter => filter.text);
});

function clearFilter(value) {
    if (value.startsWith('topics')) {
        clearSelectedTopic();
    } else if (value.startsWith('courseTypes')) {
        clearSelectedCourseTypeFilter();
    } else if (value.startsWith('certificates')) {
        clearSelectedCertificate();
    } else if (value.startsWith('courseIndustries')) {
        clearSelectedCourseIndustry();
    }
}
</script>

<style>
.section_box {
    background-color: white;
    padding: 20px;
    border-radius: 16px;
}

.home_page_style {
    background-color: #76c3f1;
}

.skill_buttons {
    font-weight: 600;
    border-color: black;
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

.release_top_section {
    margin-bottom: 35px;
}

@media (max-width: 430px) {
    .main_left_right_button {
        display: flex;
    }
}

.mySwiper {
    width: 100%;
    padding: 10px 0;
}

.swiper-slide {
    /* width: 150px; */
    margin-right: 24px !important;
    width: 100% !important;
}

.title_hidden {
    height: 18px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    width: 100%;
    display: block;
}

.filter-container {
    display: flex;

}

.filter-container select {

    border-radius: 4px;
    border: 1px solid #7E7E7E;
}



.course-card-wrapper {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
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

@media(max-width:1435px){
    .course-card{
        max-width: 100%;
    }
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

@media (max-width: 940px) {
    .list_of_courses {

        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;

    }
}
@media (max-width: 540px) {
    .list_of_courses {

        grid-template-columns: repeat(1, minmax(0, 1fr)) !important;

    }
}

@media (max-width: 540px) {
    .main_filter_container {
        flex-direction: column;
        gap: 10px;
    }
    .filter_select_container{
        justify-content: center !important;
        flex-direction: column !important;
        display: flex !important;
        width: 100% !important;
    }
    .search_input{
        width: 100% !important;
    }
}

.swiper-slide:hover{
    cursor: pointer;
    transform: scale(1.05);
    transition: transform 0.3s ease;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.selected-filters-container {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    align-items: center;
    width: 100%;
}

.selected-filter-tag {
    background-color: #97d5ff;
    color: #333;
    padding: 4px 8px;
    border-radius: 16px;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    max-width: 200px;
}

.filter-text {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: calc(100% - 25px); /* Leave space for the close button */
}

.remove-filter-btn {
    background: none;
    border: none;
    color: #333;
    margin-left: 6px;
    cursor: pointer;
    font-weight: bold;
    font-size: 1rem;
    padding: 0;
    line-height: 1;
    flex-shrink: 0;
}

.remove-filter-btn:hover {
    color: #000;
}

/* Added styles for select options */
.filter-container select option:hover,
.filter-container select option:focus {
    background-color: #97d5ff !important;
    color: #000000 !important; /* Black text for better contrast */
    border: none !important;
    outline: none !important;
}

/* Style for the currently selected option in the dropdown list (though support is very limited) */
.filter-container select option:checked {
    background-color: #97d5ff !important;
    color: #000000 !important;
    border: none !important;
    outline: none !important;
}

/* Styles for custom dropdown items on hover */
.dropdown-menu .dropdown-item:hover {
    background-color: #97d5ff !important;
    color: #000000 !important;
}

.dropdown_dashboard{
    width: 200px;
}

.section-title {
    max-width: 70%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>