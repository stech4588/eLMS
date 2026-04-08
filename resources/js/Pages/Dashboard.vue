<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="dark:bg-dark-bg-primary p-6 dashboard_main_container"
            style="margin: 13px; margin-top: 0px; justify-content: center; display: flex;">



            <div class="dashboard-surface mx-auto space-y-10">
                <!-- <div class="main_filter_container filter-panel">
                    <div class="filter-search-row">
                        <div class="filter-search">
                        <img src="/images/search_icon.svg" alt="Search" class="search_icon filter-search__icon" />
                        <input class="search_input filter-search__input dark:text-white" type="text" placeholder="Search Courses..." v-model="searchQuery" />
                        </div>
                    </div>
                    <div class="filter-container">
                        <div class="filter_select_container filter-select-group">
                            <div class="dropdown_dashboard filter-select" ref="topicDropdownRef">
                                <button @click="toggleTopicDropdown" class="filter-select__trigger" type="button">
                                    <span class="filter-select__label">{{ selectedTopicText || 'Topics' }}</span>
                                    <img src="/images/dropdown_arrow.svg" alt="Dropdown Arrow" class="dropdown_arrow" />
                                </button>
                                <ul v-if="isTopicDropdownOpen" class="dropdown-menu show dark_home_dropdown">
                                    <li><a class="dropdown-item dark:text-white" href="#" @click.prevent="handleTopicSelect({ value: '', text: 'Topics' })" style="display: block; width: 100%; padding: 0.25rem 1.5rem; clear: both; font-weight: 400; text-align: inherit; white-space: nowrap; background-color: transparent; border: 0;">Topics (All)</a></li>
                                    <li v-for="option in topicOptions" :key="option.value">
                                        <a class="dropdown-item dark:text-white" href="#" @click.prevent="handleTopicSelect(option)" style="display: block; width: 100%; padding: 0.25rem 1.5rem; clear: both; font-weight: 400; text-align: inherit; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; background-color: transparent; border: 0;">
                                            {{ option.text }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="dropdown_dashboard filter-select" ref="courseTypeDropdownRef">
                                <button @click="toggleCourseTypeDropdown" class="filter-select__trigger" type="button">
                                    <span class="filter-select__label">{{ selectedCourseTypeText || 'Course Type' }}</span>
                                    <img src="/images/dropdown_arrow.svg" alt="Dropdown Arrow" class="dropdown_arrow" />
                                </button>
                                <ul v-if="isCourseTypeDropdownOpen" class="dropdown-menu show dark_home_dropdown">
                                    <li><a class="dropdown-item dark:text-white" href="#" @click.prevent="handleCourseTypeSelect({ value: '', text: 'Course Type' })" style="display: block; width: 100%; padding: 0.25rem 1.5rem; clear: both; font-weight: 400; text-align: inherit; white-space: nowrap; background-color: transparent; border: 0;">Course Type (All)</a></li>
                                    <li v-for="option in courseTypeOptions" :key="option.value">
                                        <a class="dropdown-item dark:text-white" href="#" @click.prevent="handleCourseTypeSelect(option)" style="display: block; width: 100%; padding: 0.25rem 1.5rem; clear: both; font-weight: 400; text-align: inherit; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; background-color: transparent; border: 0;">
                                            {{ option.text }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="dropdown_dashboard filter-select" ref="certificateDropdownRef">
                                <button @click="toggleCertificateDropdown" class="filter-select__trigger" type="button">
                                    <span class="filter-select__label">{{ selectedCertificateText || 'Certificate' }}</span>
                                    <img src="/images/dropdown_arrow.svg" alt="Dropdown Arrow" class="dropdown_arrow" />
                                </button>
                                <ul v-if="isCertificateDropdownOpen" class="dropdown-menu show dark_home_dropdown">
                                    <li><a class="dropdown-item dark:text-white" href="#" @click.prevent="handleCertificateSelect({ value: '', text: 'Certificate' })" style="display: block; width: 100%; padding: 0.25rem 1.5rem; clear: both; font-weight: 400; text-align: inherit; white-space: nowrap; background-color: transparent; border: 0;">Certificate (All)</a></li>
                                    <li v-for="option in certificateOptions" :key="option.value">
                                        <a class="dropdown-item dark:text-white" href="#" @click.prevent="handleCertificateSelect(option)" style="display: block; width: 100%; padding: 0.25rem 1.5rem; clear: both; font-weight: 400; text-align: inherit; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; background-color: transparent; border: 0;">
                                            {{ option.text }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="dropdown_dashboard filter-select" ref="courseIndustryDropdownRef">
                                <button @click="toggleCourseIndustryDropdown" class="filter-select__trigger" type="button">
                                    <span class="filter-select__label">{{ selectedCourseIndustryText || 'Course Industry' }}</span>
                                    <img src="/images/dropdown_arrow.svg" alt="Dropdown Arrow" class="dropdown_arrow" />
                                </button>
                                <ul v-if="isCourseIndustryDropdownOpen" class="dropdown-menu show dark_home_dropdown">
                                    <li><a class="dropdown-item dark:text-white" href="#" @click.prevent="handleCourseIndustrySelect({ value: '', text: 'Course Industry' })" style="display: block; width: 100%; padding: 0.25rem 1.5rem; clear: both; font-weight: 400; text-align: inherit; white-space: nowrap; background-color: transparent; border: 0;">Course Industry (All)</a></li>
                                    <li v-for="option in courseIndustryOptions" :key="option.value">
                                        <a class="dropdown-item dark:text-white" href="#" @click.prevent="handleCourseIndustrySelect(option)" style="display: block; width: 100%; padding: 0.25rem 1.5rem; clear: both; font-weight: 400; text-align: inherit; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; background-color: transparent; border: 0;">
                                            {{ option.text }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>


                </div> -->
                <!-- My Programs - single column row layout -->
                <div class="my-programs-section">
                    <h2 class="my-programs-heading">All Courses</h2>
                    <!-- Display Selected Filters -->
                    <div class="selected-filters-container mb-4" v-if="hasActiveFilters">
                        <span v-for="topic in selectedTopicFilters" :key="`topic-${topic.value}`" class="selected-filter-tag">
                            <span class="filter-text">{{ topic.text }}</span>
                            <button @click="removeSelectedTopic(topic.value)" class="remove-filter-btn">&times;</button>
                        </span>
                        <span v-for="courseType in selectedCourseTypeFilters" :key="`courseType-${courseType.value}`" class="selected-filter-tag">
                            <span class="filter-text">{{ courseType.text }}</span>
                            <button @click="removeSelectedCourseType(courseType.value)" class="remove-filter-btn">&times;</button>
                        </span>
                        <span v-for="certificate in selectedCertificateFilters" :key="`certificate-${certificate.value}`" class="selected-filter-tag">
                            <span class="filter-text">{{ certificate.text }}</span>
                            <button @click="removeSelectedCertificate(certificate.value)" class="remove-filter-btn">&times;</button>
                        </span>
                        <span v-for="industry in selectedCourseIndustryFilters" :key="`industry-${industry.value}`" class="selected-filter-tag">
                            <span class="filter-text">{{ industry.text }}</span>
                            <button @click="removeSelectedCourseIndustry(industry.value)" class="remove-filter-btn">&times;</button>
                        </span>
                    </div>

                    <div class="my-programs-list">
                        <div v-for="(course, index) in displayedCourses" :key="`skill-${index}-${course.id}`" class="my-program-row">
                            <button
                                type="button"
                                class="course-save-btn"
                                :class="{ 'is-saved': course.is_favorited }"
                                @click.stop.prevent="toggleFavorite(course)"
                                :title="course.is_favorited ? 'Saved' : 'Save'"
                                aria-label="Save course"
                            >
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path
                                        d="M7 3h10a1 1 0 011 1v17l-6-3-6 3V4a1 1 0 011-1Z"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                            </button>
                            <Link :href="route('courses.show', { course: course.id })" class="my-program-thumb-wrap">
                                <img :src="course.first_video_thumbnail_url || '/images/skill_section_thumbnail.svg'" class="my-program-thumb" alt="" />
                            </Link>
                            <div class="my-program-details">
                                <h3 class="my-program-title">{{ course.title }}</h3>
                                <p v-if="course.description" class="my-program-desc">{{ course.description }}</p>
                                <div class="my-program-actions">
                                    <!-- If user has already purchased this course, show Start Now -->
                                    <Link
                                        v-if="course.is_purchased"
                                        :href="route('courses.show', { course: course.id })"
                                        class="my-program-btn-start"
                                    >
                                        Start Now
                                    </Link>

                                    <!-- If not purchased, show Buy button with lock icon that goes to purchase page -->
                                    <Link
                                        v-else
                                        :href="route('purchase-course.show', { course_id: course.id })"
                                        class="my-program-btn-start my-program-btn-locked"
                                    >
                                        <span class="lock-icon" aria-hidden="true">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M7 10V7C7 4.791 8.791 3 11 3C13.209 3 15 4.791 15 7V10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <rect x="5" y="10" width="12" height="10" rx="2" stroke="currentColor" stroke-width="1.5"/>
                                                <circle cx="11" cy="15" r="1.5" fill="currentColor"/>
                                            </svg>
                                        </span>
                                        Buy
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Pagination -->
                    <div v-if="skillPager && skillPager.links" class="pagination mt-6">
                        <Link
                            v-for="(link, idx) in skillPager.links"
                            :key="`p-${idx}-${link.label}`"
                            :href="link.url || '#'"
                            preserve-scroll
                            preserve-state
                            :class="['page-link', { 'is-active': link.active, 'is-disabled': !link.url }]"
                            v-html="link.label"
                        />
                    </div>
                </div>

                <!-- <div class=" dark:text-white f-direction" style="display:flex; justify-content:space-around; gap:15px;">
                    <div class="course-card dark:bg-[#0F212E] dark:text-white">
                        <div class="course-card-text">
                            Leadership & Management
                            <a href="/content" class="course-card-button dark:bg-[#1C355E] dark:text-white">Explore</a>
                        </div>
                        <img src="/images/leadership_management_image.svg" alt="Leadership & Management"
                            class="course-card-image" />
                    </div>

                    <div class="course-card dark:bg-[#0F212E] dark:text-white">
                        <div class="course-card-text">
                            Diversity & Equity
                            <a href="/content" class="course-card-button dark:bg-[#1C355E] dark:text-white">Explore</a>
                        </div>
                        <img src="/images/diversity_image.svg" alt="Diversity & Equity" class="course-card-image" />
                    </div>

                    <div class="course-card dark:bg-[#0F212E] dark:text-white">
                        <div class="course-card-text">
                            Productivity
                            <a href="/content" class="course-card-button dark:bg-[#1C355E] dark:text-white">Explore</a>
                        </div>
                        <img src="/images/productivity_image.svg" alt="Productivity" class="course-card-image" />
                    </div>
                </div> -->


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
import SequenceCanvas from '@/Components/SequenceCanvas.vue'
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
// Define props to receive data from the controller
const props = defineProps({
    courseTypes: Array,
    topics: Array,
    courseCertificates: Array,
    courseIndustries: Array,
    skillBasedCourses: Array,
    allNewReleaseCourses: Array,
});

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

// Helper computed properties for rendering individual filter tags
const selectedTopicFilters = computed(() => {
    return selectedTopics.value
        .map(topicId => topicOptions.value.find(opt => opt.value === topicId))
        .filter(Boolean);
});

const selectedCourseTypeFilters = computed(() => {
    return selectedCourseTypes.value
        .map(ctId => courseTypeOptions.value.find(opt => opt.value === ctId))
        .filter(Boolean);
});

const selectedCertificateFilters = computed(() => {
    return selectedCertificates.value
        .map(certId => certificateOptions.value.find(opt => opt.value === certId))
        .filter(Boolean);
});

const selectedCourseIndustryFilters = computed(() => {
    return selectedCourseIndustries.value
        .map(indId => courseIndustryOptions.value.find(opt => opt.value === indId))
        .filter(Boolean);
});

// Methods to clear filters
const clearSelectedTopic = () => {
    selectedTopics.value = [];
    isTopicDropdownOpen.value = false;
};
const removeSelectedTopic = (topicId) => {
    const index = selectedTopics.value.indexOf(topicId);
    if (index > -1) {
        selectedTopics.value.splice(index, 1);
    }
};

const clearSelectedCourseTypeFilter = () => {
    selectedCourseTypes.value = [];
    isCourseTypeDropdownOpen.value = false;
};
const removeSelectedCourseType = (courseTypeId) => {
    const index = selectedCourseTypes.value.indexOf(courseTypeId);
    if (index > -1) {
        selectedCourseTypes.value.splice(index, 1);
    }
};

const clearSelectedCertificate = () => {
    selectedCertificates.value = [];
    isCertificateDropdownOpen.value = false;
};
const removeSelectedCertificate = (certificateId) => {
    const index = selectedCertificates.value.indexOf(certificateId);
    if (index > -1) {
        selectedCertificates.value.splice(index, 1);
    }
};

const clearSelectedCourseIndustry = () => {
    selectedCourseIndustries.value = [];
    isCourseIndustryDropdownOpen.value = false;
};
const removeSelectedCourseIndustry = (courseIndustryId) => {
    const index = selectedCourseIndustries.value.indexOf(courseIndustryId);
    if (index > -1) {
        selectedCourseIndustries.value.splice(index, 1);
    }
};

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
    if (topic.value === '') {
        selectedTopics.value = [];
        return;
    }
    const index = selectedTopics.value.indexOf(topic.value);
    if (index === -1) {
        selectedTopics.value.push(topic.value);
    } else {
        selectedTopics.value.splice(index, 1);
    }
};

const handleCourseTypeSelect = (option) => {
    if (option.value === '') {
        selectedCourseTypes.value = [];
        return;
    }
    const index = selectedCourseTypes.value.indexOf(option.value);
    if (index === -1) {
        selectedCourseTypes.value.push(option.value);
    } else {
        selectedCourseTypes.value.splice(index, 1);
    }
};

const handleCertificateSelect = (option) => {
    if (option.value === '') {
        selectedCertificates.value = [];
        return;
    }
    const index = selectedCertificates.value.indexOf(option.value);
    if (index === -1) {
        selectedCertificates.value.push(option.value);
    } else {
        selectedCertificates.value.splice(index, 1);
    }
};

const handleCourseIndustrySelect = (option) => {
    if (option.value === '') {
        selectedCourseIndustries.value = [];
        return;
    }
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

// Computed property that no longer applies filters, just returns the prop
const skillPager = computed(() => props.skillBasedCourses || null);
const displayedCourses = computed(() => {
    return (skillPager.value && skillPager.value.data) ? skillPager.value.data : (props.skillBasedCourses || []);
});

const debounce = (fn, delay) => {
    let timeoutId;
    return (...args) => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => fn(...args), delay);
    };
};

watch(
    [searchQuery, selectedTopics, selectedCourseTypes, selectedCertificates, selectedCourseIndustries],
    debounce(() => {
        router.get(
            route('dashboard'),
            {
                search: searchQuery.value,
                topics: selectedTopics.value,
                course_types: selectedCourseTypes.value,
                certificates: selectedCertificates.value,
                course_industries: selectedCourseIndustries.value,
                page: 1,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            }
        );
    }, 500),
    { deep: true }
);

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

<style>
.dashboard-surface {
    width: 100%;
    max-width: 1200px;
    background: #ffffff;
    border-radius: 24px;
    padding: 32px;
    text-align: start;
    box-shadow: 0 24px 54px rgba(28, 46, 58, 0.12);
    transition: background 0.4s ease, box-shadow 0.4s ease, border-color 0.4s ease;
    border: 1px solid rgba(12, 39, 53, 0.04);
    position: relative;
    overflow: hidden;
}

.dashboard-surface::after {
    content: '';
    position: absolute;
    inset: -35% 55% 35% -15%;
    background: radial-gradient(55% 55% at 50% 50%, rgba(34, 197, 94, 0.15) 0%, rgba(34, 197, 94, 0) 100%);
    opacity: 0.4;
    filter: blur(0px);
    transition: opacity 0.6s ease;
    pointer-events: none;
}

.dark .dashboard-surface {
    background: radial-gradient(circle at top right, rgba(30, 64, 82, 0.65), rgba(15, 33, 46, 0.92));
    border: 1px solid rgba(135, 199, 255, 0.08);
    box-shadow: 0 32px 80px rgba(6, 14, 23, 0.55);
}

.dark .dashboard-surface::after {
    opacity: 0.9;
    background: radial-gradient(60% 65% at 40% 40%, rgba(76, 202, 255, 0.18) 0%, rgba(26, 44, 56, 0) 100%);
}

.filter-panel {
    display: grid;
    grid-template-columns: 1fr;
    gap: 18px;
    padding: 24px 28px;
    border-radius: 20px;
    background: #ffffff;
    box-shadow: 0 18px 45px rgba(22, 37, 48, 0.06);
    border: 1px solid rgba(12, 39, 53, 0.04);
    transition: background 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
}

.filter-search-row {
    width: 100%;
}

.dark .filter-panel {
    background: linear-gradient(145deg, rgba(26, 44, 56, 0.94), rgba(26, 44, 56, 0.78));
    border: 1px solid rgba(109, 173, 231, 0.12);
    box-shadow: inset 0 0 0 1px rgba(148, 199, 255, 0.05), 0 18px 40px rgba(6, 14, 23, 0.45);
}

.filter-search {
    position: relative;
    flex: 1;
    max-width: 100%;
    display: flex;
    align-items: center;
}

.filter-search__icon {
    position: absolute;
    left: 16px;
    width: 18px;
    height: 18px;
    opacity: 0.75;
    pointer-events: none;
}

.filter-search__input {
    width: 100%;
    border-radius: 12px;
    border: 1px solid rgba(17, 51, 68, 0.24);
    padding: 12px 16px 12px 44px;
    background: rgba(255, 255, 255, 0.9);
    color: #1f2d3a;
    transition: border 0.3s ease, box-shadow 0.3s ease, background 0.3s ease, color 0.3s ease;
}

.filter-search__input:focus {
    outline: none;
    border-color: rgba(76, 202, 255, 0.6);
    box-shadow: 0 0 0 4px rgba(76, 202, 255, 0.16);
}

.dark .filter-search__input {
    background: rgba(17, 36, 49, 0.88);
    border: 1px solid rgba(120, 184, 240, 0.18);
    color: #e5f2ff;
}

.filter-select-group {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
    justify-content: flex-start;
}

.filter-select {
    position: relative;
    min-width: 180px;
    z-index: 10;
}

.filter-select__trigger {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    border-radius: 12px;
    border: 1px solid rgba(17, 51, 68, 0.24);
    background: rgba(255, 255, 255, 0.92);
    padding: 10px 14px;
    color: #1f2d3a;
    transition: border 0.3s ease, background 0.3s ease, box-shadow 0.3s ease;
}

.filter-select__trigger:hover {
    border-color: rgba(76, 202, 255, 0.35);
}

.filter-select__label {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    flex: 1;
}

.dark .filter-select__trigger {
    background: rgba(19, 38, 52, 0.85);
    border: 1px solid rgba(109, 173, 231, 0.22);
    color: #e5f2ff;
    box-shadow: inset 0 0 0 1px rgba(110, 184, 240, 0.04);
}

.dark .filter-select__trigger:hover {
    border-color: rgba(148, 199, 255, 0.4);
}

.filter-select .dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    max-height: 220px;
    overflow-y: auto;
    border-radius: 12px;
    border: 1px solid rgba(17, 51, 68, 0.24);
    margin-top: 6px;
    padding: 6px 0;
    background: #ffffff;
    box-shadow: 0 18px 40px rgba(17, 43, 60, 0.18);
    scrollbar-width: thin;
    z-index: 30;
}

.dark .filter-select .dropdown-menu {
    background: rgba(21, 41, 56, 0.96);
    border: 1px solid rgba(109, 173, 231, 0.18);
    box-shadow: 0 20px 48px rgba(6, 14, 23, 0.6);
}

.dark .dropdown-menu .dropdown-item:hover {
    background-color: rgba(76, 202, 255, 0.18) !important;
    color: #f3fbff !important;
}

.pagination {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}
.page-link {
    padding: 8px 12px;
    border-radius: 10px;
    border: 1px solid rgba(17, 51, 68, 0.24);
    background: #ffffff;
    color: #1f2d3a;
    text-decoration: none;
    font-size: 14px;
}
.page-link.is-active {
    background: rgba(76, 202, 255, 0.18);
    border-color: rgba(76, 202, 255, 0.35);
    color: #0b2440;
}
.page-link.is-disabled {
    opacity: 0.5;
    pointer-events: none;
}
.dark .page-link {
    background: rgba(19, 38, 52, 0.85);
    border: 1px solid rgba(109, 173, 231, 0.22);
    color: #e5f2ff;
}
.dark .page-link.is-active {
    background: rgba(104, 198, 255, 0.22);
    border-color: rgba(104, 198, 255, 0.35);
    color: #ffffff;
}

.section_box {
    background-color: white;
    padding: 20px;
    border-radius: 16px;
    text-align: start;
}

.dark .section_box {
    background: linear-gradient(145deg, rgba(26, 44, 56, 0.95), rgba(26, 44, 56, 0.82));
    border: 1px solid rgba(109, 173, 231, 0.15);
    box-shadow: 0 22px 55px rgba(6, 14, 23, 0.45);
    backdrop-filter: blur(6px);
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
    overflow: visible;
}

.swiper-slide {
    margin-right: 24px !important;
    width: auto !important;
}

.course-card-slide {
    width: 320px !important;
    height: auto;
    display: flex;
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
    /* flex-wrap: wrap; */
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

.dark .course-card {
    border: 1px solid rgba(109, 173, 231, 0.16);
    background: linear-gradient(160deg, rgba(26, 44, 56, 0.92), rgba(26, 44, 56, 0.78));
    box-shadow: 0 18px 40px rgba(6, 14, 23, 0.45);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.dark .course-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 26px 60px rgba(6, 14, 23, 0.58);
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

.dark .course-card-button {
    border: 1px solid rgba(109, 173, 231, 0.35);
    background: rgba(76, 202, 255, 0.16);
    color: #dff5ff;
}

.dark .course-card-button:hover {
    border-color: rgba(148, 199, 255, 0.6);
    background: rgba(76, 202, 255, 0.28);
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
        text-align: center;
        justify-content: center;
    }
}
@media (max-width: 1024px) {
    .course-card{
        flex-direction: row;
        height: 200px;
       
    }
    .course-card-text{
        align-items: flex-start;
        text-align: left;
        justify-content: flex-start;
    }
}
.f-direction {
    flex-direction: row;
}
@media (max-width: 1110px) {
    .f-direction {
        flex-wrap: wrap;
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

.dark .selected-filter-tag {
    background-color: rgba(76, 202, 255, 0.14);
    color: #dff5ff;
    border: 1px solid rgba(148, 199, 255, 0.16);
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

.dark .filter-container select option:hover,
.dark .filter-container select option:focus {
    background-color: rgba(76, 202, 255, 0.28) !important;
    color: #f3fbff !important;
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

.dark .dropdown_home_dropdown {
    background-color: rgba(21, 41, 56, 0.96) !important;
}

.dark .dark_home_dropdown{
    background-color: rgba(21, 41, 56, 0.96) !important;
    border: 1px solid rgba(109, 173, 231, 0.18) !important;
}

.section-title {
    max-width: 70%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.course_listing_home_page{
    height: 14rem;
}
@media (max-width: 1500px){
    .course_listing_home_page{
    height: 12rem;
}
}
@media (max-width: 1370px){
    .course_listing_home_page{
    height: 10rem;
}
}
@media (max-width: 1200px){
    .course_listing_home_page{
    height: 8rem;
}
}
@media (max-width: 1024px){
    .course_listing_home_page{
    height: 7rem;
}
}
@media (max-width: 768px){
    .course_listing_home_page{
    height: 13rem;
}
}
@media (max-width: 425px){
    .course_listing_home_page{
    height: 12rem;
}
}
@media (max-width: 375px){
    .course_listing_home_page{
    height: 10rem;
}
}
@media (max-width: 320px){
    .course_listing_home_page{
    height: 8rem;
}
}
@media (max-width: 425px){
    .dropdown_dashboard{
        width: 100%;
    }
}

.dark .dropdown_arrow{
    filter: invert(1);
}
.dark .search_icon{
    filter: invert(1);
}
.dark .dark_save_button{
    filter: invert(1);
}
.dark .dark_home_dropdown{
    background-color: rgba(21, 41, 56, 0.96) !important;
}

/* My Programs - single column row layout (image style) */
.my-programs-section {
    margin-top: 2rem;
}

.my-programs-heading {
    font-size: 2.5rem;
    font-weight: 700;
    color: #111827;
    margin: 0 0 1.25rem;
    text-align: left;
}

.dark .my-programs-heading {
    color: #f9fafb;
}

.my-programs-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.my-program-row {
    display: flex;
    flex-direction: row;
    align-items: stretch;
    background: #ffffff;
    height: 200px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
    transition: box-shadow 0.2s;
    position: relative;
}

.my-program-row:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.dark .my-program-row {
    background: #1f2937;
    border-color: #374151;
}

.course-save-btn{
    position: absolute;
    top: 12px;
    right: 12px;
    width: 38px;
    height: 38px;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.92);
    border: 1px solid rgba(15, 23, 42, 0.12);
    color: #1C355E;
    box-shadow: 0 8px 18px rgba(15, 32, 45, 0.12);
    transition: transform 0.15s ease, background-color 0.15s ease, border-color 0.15s ease;
    z-index: 5;
}

.course-save-btn svg{
    width: 18px;
    height: 18px;
}

.course-save-btn:hover{
    transform: translateY(-1px);
    background: #ffffff;
    border-color: rgba(28, 53, 94, 0.22);
}

.course-save-btn.is-saved{
    background: rgba(28, 53, 94, 0.10);
    border-color: rgba(28, 53, 94, 0.22);
}

.dark .course-save-btn{
    background: rgba(15, 33, 46, 0.92);
    border-color: rgba(229, 242, 255, 0.16);
    color: #58cfff;
}

.dark .course-save-btn.is-saved{
    background: rgba(88, 207, 255, 0.12);
    border-color: rgba(88, 207, 255, 0.25);
}

.my-program-thumb-wrap {
    flex: 0 0 27%;
    min-width: 0;
    height: 200px;
    display: block;
    overflow: hidden;
}

.my-program-thumb {
    width: 100%;
    height: 100%;
    min-height: 180px;
    object-fit: cover;
    display: block;
}

.my-program-details {
    flex: 1;
    padding: 1.25rem 1.5rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
    min-width: 0;
}

.my-program-title {
    font-size: 2rem;
    font-weight: 700;
    color: #111827;
    margin: 0 0 0.5rem;
    line-height: 1.3;
}

.dark .my-program-title {
    color: #f9fafb;
}

.my-program-desc {
    font-size: 1.2rem;
    color: #4b5563;
    margin: 0 0 1rem;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

.dark .my-program-desc {
    color: #d1d5db;
}

.my-program-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    margin-top: auto;
}

.my-program-btn-start {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem 1.25rem;
    font-size: 0.9375rem;
    font-weight: 600;
    color: #1C355E;
    background: #ffffff;
    border: 2px solid #1C355E;
    border-radius: 6px;
    text-decoration: none;
    transition: background 0.2s, color 0.2s;
}

.my-program-btn-start:hover {
    background: #1C355E;
    color: #ffffff;
}

.dark .my-program-btn-start {
    color: #4ade80;
    border-color: #4ade80;
    background: transparent;
}

.dark .my-program-btn-start:hover {
    background: #1C355E;
    color: #ffffff;
    border-color: #1C355E;
}

@media (max-width: 640px) {
    .my-program-row {
        flex-direction: column;
    }
    .my-program-thumb-wrap {
        flex: 0 0 auto;
        width: 100%;
    }
    .my-program-thumb {
        min-height: 160px;
    }
}

.course-card-container {
    display: flex; /* Ensures the card within takes up the full space */
}

.linkedin-card-img-wrap {
    width: 55%;
    height: 100px;

    /* background: #ffffff; */
    display: flex;
    align-items: center;
    justify-content: center;
}
@media (max-width: 768px) {
    .dashboard_main_container{
        padding-left: 0px !important;
        padding-right: 0px !important;
    }
    .section_box{
        padding-left: 0px !important;
        padding-right: 0px !important;
    }
}
</style>