<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="dark:bg-dark-bg-primary p-6 dashboard_main_container"
            style="margin: 13px; margin-top: 0px; justify-content: center; display: flex;">



            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-10 bg-white dark:bg-dark-bg-secondary p-6"
                style="margin: 0px; width: 100%; max-width:1730px; border-radius: 16px; text-align: start;">
                <div class="main_filter_container bg-white dark:bg-dark-bg-secondary" style="display: flex; justify-content: space-between; padding: 10px; border-radius: 16px; padding-top: 25px;">
                    <div style=" display: flex; align-items: start; ">
                        <img src="/images/search_icon.svg" alt="Search" class="search_icon" style="position: absolute; margin-left: 10px;  padding: 15px 0px;"/>
                        <input class="search_input dark:bg-gray-700 dark:text-white dark:border-gray-600" type="text" placeholder="Search Courses..." v-model="searchQuery"
                            style="border-radius: 10px; border: 1px solid #7E7E7E; padding: 10px; padding-left: 30px; " />
                    </div>
                    <div class="filter-container">
                        <div class="filter_select_container" style="display: flex; gap: 10px; flex-wrap: wrap;justify-content: end;">
                            <!-- Topics Dropdown -->
                            <div class="dropdown_dashboard" ref="topicDropdownRef" style="position: relative; ">
                                <button @click="toggleTopicDropdown" class="btn btn-outline-secondary dropdown-toggle dark:bg-gray-700 dark:text-white dark:border-gray-600" type="button" style="width: 100%; display: flex; justify-content: space-between; align-items: center; border-radius: 4px; border: 1px solid #7E7E7E; padding: 0.375rem 0.75rem;">
                                    <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ selectedTopicText || 'Topics' }}</span>
                                    <img src="/images/dropdown_arrow.svg" alt="Dropdown Arrow" class="dropdown_arrow" />
                                </button>
                                <ul v-if="isTopicDropdownOpen" class="dropdown-menu show dark:bg-gray-800 dark:text-white dark_home_dropdown" style="position: absolute; top: 100%;background-color: #dedede; overflow-y: auto; max-height: 200px; left: 0; width: 100%; z-index: 1000; min-width: auto; padding: 0.5rem 0; margin: 0.125rem 0 0; font-size: 1rem; color: #212529; text-align: left; list-style: none; background-clip: padding-box; border: 1px solid rgba(0,0,0,.15); border-radius: 0.25rem; scrollbar-width: none;">
                                    <li><a class="dropdown-item dark:text-white" href="#" @click.prevent="handleTopicSelect({ value: '', text: 'Topics' })" style="display: block; width: 100%; padding: 0.25rem 1.5rem; clear: both; font-weight: 400; text-align: inherit; white-space: nowrap; background-color: transparent; border: 0;">Topics (All)</a></li>
                                    <li v-for="option in topicOptions" :key="option.value">
                                        <a class="dropdown-item dark:text-white" href="#" @click.prevent="handleTopicSelect(option)" style="display: block; width: 100%; padding: 0.25rem 1.5rem; clear: both; font-weight: 400; text-align: inherit; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; background-color: transparent; border: 0;">
                                            {{ option.text }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!-- Course Type Dropdown -->
                            <div class="dropdown_dashboard" ref="courseTypeDropdownRef" style="position: relative; ">
                                <button @click="toggleCourseTypeDropdown" class="btn btn-outline-secondary dropdown-toggle dark:bg-gray-700 dark:text-white dark:border-gray-600" type="button" style="width: 100%; display: flex; justify-content: space-between; align-items: center; border-radius: 4px; border: 1px solid #7E7E7E; padding: 0.375rem 0.75rem;">
                                    <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ selectedCourseTypeText || 'Course Type' }}</span>
                                    <img src="/images/dropdown_arrow.svg" alt="Dropdown Arrow" class="dropdown_arrow" />
                                </button>
                                <ul v-if="isCourseTypeDropdownOpen" class="dropdown-menu show dark:bg-gray-800 dark:text-white dark_home_dropdown" style="position: absolute;overflow: hidden; top: 100%; background-color: #dedede; left: 0; width: 100%; z-index: 1000; min-width: auto; padding: 0.5rem 0; margin: 0.125rem 0 0; font-size: 1rem; color: #212529; text-align: left; list-style: none; background-clip: padding-box;border: 1px solid rgba(0,0,0,.15); border-radius: 0.25rem; scrollbar-width: none;">
                                    <li><a class="dropdown-item dark:text-white" href="#" @click.prevent="handleCourseTypeSelect({ value: '', text: 'Course Type' })" style="display: block; width: 100%; padding: 0.25rem 1.5rem; clear: both; font-weight: 400; text-align: inherit; white-space: nowrap; background-color: transparent; border: 0;">Course Type (All)</a></li>
                                    <li v-for="option in courseTypeOptions" :key="option.value">
                                        <a class="dropdown-item dark:text-white" href="#" @click.prevent="handleCourseTypeSelect(option)" style="display: block; width: 100%; padding: 0.25rem 1.5rem; clear: both; font-weight: 400; text-align: inherit; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; background-color: transparent; border: 0;">
                                            {{ option.text }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!-- Certificate Dropdown -->
                            <div class="dropdown_dashboard" ref="certificateDropdownRef" style="position: relative; ;">
                                <button @click="toggleCertificateDropdown" class="btn btn-outline-secondary dropdown-toggle dark:bg-gray-700 dark:text-white dark:border-gray-600" type="button" style="width: 100%; display: flex; justify-content: space-between; align-items: center; border-radius: 4px; border: 1px solid #7E7E7E; padding: 0.375rem 0.75rem;">
                                    <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ selectedCertificateText || 'Certificate' }}</span>
                                    <img src="/images/dropdown_arrow.svg" alt="Dropdown Arrow" class="dropdown_arrow" />
                                </button>
                                <ul v-if="isCertificateDropdownOpen" class="dropdown-menu show dark:bg-gray-800 dark:text-white dark_home_dropdown" style="position: absolute; overflow: hidden; top: 100%; background-color: #dedede; left: 0; width: 100%; z-index: 1000; min-width: auto; padding: 0.5rem 0; margin: 0.125rem 0 0; font-size: 1rem; color: #212529; text-align: left; list-style: none; background-clip: padding-box;border: 1px solid rgba(0,0,0,.15); border-radius: 0.25rem; scrollbar-width: none;">
                                    <li><a class="dropdown-item dark:text-white" href="#" @click.prevent="handleCertificateSelect({ value: '', text: 'Certificate' })" style="display: block; width: 100%; padding: 0.25rem 1.5rem; clear: both; font-weight: 400; text-align: inherit; white-space: nowrap; background-color: transparent; border: 0;">Certificate (All)</a></li>
                                    <li v-for="option in certificateOptions" :key="option.value">
                                        <a class="dropdown-item dark:text-white" href="#" @click.prevent="handleCertificateSelect(option)" style="display: block; width: 100%; padding: 0.25rem 1.5rem; clear: both; font-weight: 400; text-align: inherit; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; background-color: transparent; border: 0;">
                                            {{ option.text }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!-- Course Industry Dropdown -->
                            <div class="dropdown_dashboard" ref="courseIndustryDropdownRef" style="position: relative; ">
                                <button @click="toggleCourseIndustryDropdown" class="btn btn-outline-secondary dropdown-toggle dark:bg-gray-700 dark:text-white dark:border-gray-600" type="button" style="width: 100%; display: flex; justify-content: space-between; align-items: center; border-radius: 4px; border: 1px solid #7E7E7E; padding: 0.375rem 0.75rem;">
                                    <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ selectedCourseIndustryText || 'Course Industry' }}</span>
                                    <img src="/images/dropdown_arrow.svg" alt="Dropdown Arrow" class="dropdown_arrow" />
                                </button>
                                <ul v-if="isCourseIndustryDropdownOpen" class="dropdown-menu show dark:bg-gray-800 dark:text-white dark_home_dropdown" style="position: absolute; overflow: hidden; background-color: #dedede; top: 100%; left: 0; width: 100%; z-index: 1000; min-width: auto; padding: 0.5rem 0; margin: 0.125rem 0 0; font-size: 1rem; color: #212529; text-align: left; list-style: none; background-clip: padding-box;border: 1px solid rgba(0,0,0,.15); border-radius: 0.25rem; scrollbar-width: none;">
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


                </div>
                <!-- Skills Section -->
                <div class="section_box dark:bg-dark-bg-secondary dark:text-white">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-xl font-bold section-title">Because of Skills you Follow</h3>
                    </div>
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

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-6 mt-6">
                        <div v-for="(course, index) in displayedCourses" :key="`skill-${index}-${course.id}`" class="course-card-container">
                            <CourseCard :course="course" @toggle-favorite="toggleFavorite" />
                        </div>
                    </div>
                </div>

                <div class="dark:bg-dark-bg-secondary dark:text-white f-direction" style="display:flex; justify-content:space-around; gap:15px;">
                    <div class="course-card dark:bg-gray-800 dark:text-white">
                        <div class="course-card-text">
                            Leadership & Management
                            <a href="/content" class="course-card-button dark:bg-blue-700 dark:text-white">Explore</a>
                        </div>
                        <img src="/images/leadership_management_image.svg" alt="Leadership & Management"
                            class="course-card-image" />
                    </div>

                    <div class="course-card dark:bg-gray-800 dark:text-white">
                        <div class="course-card-text">
                            Diversity & Equity
                            <a href="/content" class="course-card-button dark:bg-blue-700 dark:text-white">Explore</a>
                        </div>
                        <img src="/images/diversity_image.svg" alt="Diversity & Equity" class="course-card-image" />
                    </div>

                    <div class="course-card dark:bg-gray-800 dark:text-white">
                        <div class="course-card-text">
                            Productivity
                            <a href="/content" class="course-card-button dark:bg-blue-700 dark:text-white">Explore</a>
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
import SequenceCanvas from '@/Components/SequenceCanvas.vue'
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import CourseCard from '@/Components/CourseCard.vue';

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
const displayedCourses = computed(() => {
    return props.skillBasedCourses || [];
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
.section_box {
    background-color: white;
    padding: 20px;
    border-radius: 16px;
    text-align: start;
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

@media(max-width:1435px){
    .course-card{
        flex-direction: column;
        max-width: 100%;
        align-items: center;
        text-align: center;
        height: 300px;
    }
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
.dark .course-card-button:hover {
    border: 1px solid #fff;
    transform: scale(1.05);
    transition: all 0.3s ease;

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
    background-color: #2d2d2d !important;
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