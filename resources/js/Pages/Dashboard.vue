<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="bg-gray-100" style="padding: 0px;margin: 0px; justify-content: center; display: flex;background-color: #acacac;">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-10" style="padding: 0px;margin: 0px;  width: 100%; background-color: #acacac;">

                <!-- Skills Section -->
                <div class="section_box">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-xl font-bold">Because of Skills you Follow</h3>
                        <div class="space-x-2 main_left_right_button">
                            <button class="swiper-button-prev1 left_right_button"><img src="/images/left_side_icon.svg" /></button>
                            <button class="swiper-button-next1 left_right_button"><img src="/images/right_side_icon.svg" /></button>
                        </div>
                    </div>

                    <div class="flex gap-3 mb-4 main_skill_buttons">
                        <button
                            @click="selectedCourseType = 'java'"
                            :class="[
                                'px-4 py-1 border rounded-full skill_buttons',
                                selectedCourseType === 'java' ? 'bg-[#4E4747] text-white border-none' : ''
                            ]"
                        >
                            Java
                        </button>
                        <button
                            @click="selectedCourseType = 'database'"
                            :class="[
                                'px-4 py-1 border rounded-full skill_buttons',
                                selectedCourseType === 'database' ? 'bg-[#4E4747] text-white border-none' : ''
                            ]"
                        >
                            Database Development
                        </button>
                    </div>

                    <swiper
                        :modules="modules"
                        :navigation="{
                            nextEl: '.swiper-button-next1',
                            prevEl: '.swiper-button-prev1',
                        }"
                        :slides-per-view="'auto'"
                        :space-between="16"
                        class="mySwiper"
                    >
                        <swiper-slide v-for="(course, index) in displayedCourses" :key="`skill-${index}`" class="w-40">
                            <div class="shrink-0 bg-white rounded-lg overflow-hidden">
                                <img
                                    src="/images/skill_section_thumbnail.svg"
                                    class="w-full h-24 object-cover"
                                    alt="Course thumbnail"
                                />
                                <div class="p-2">
                                    <p class="text-xs text-gray-500">{{ course.type }}</p>
                                    <p class="text-sm font-semibold leading-tight title_hidden">{{ course.title }}</p>
                                    <p class="text-xs text-gray-500 mt-1">By: {{ course.author }}</p>
                                </div>
                            </div>
                        </swiper-slide>
                    </swiper>
                </div>

                <!-- New releases Section -->
                <div class="section_box">
                    <div class="flex justify-between items-center mb-2 release_top_section">
                        <h3 class="text-xl font-bold">New Releases</h3>
                        <div class="space-x-2 main_left_right_button">
                            <button class="swiper-button-prev2 left_right_button"><img src="/images/left_side_icon.svg" /></button>
                            <button class="swiper-button-next2 left_right_button"><img src="/images/right_side_icon.svg" /></button>
                        </div>
                    </div>

                    <swiper
                        :modules="modules"
                        :navigation="{
                            nextEl: '.swiper-button-next2',
                            prevEl: '.swiper-button-prev2',
                        }"
                        :slides-per-view="'auto'"
                        :space-between="16"
                        class="mySwiper"
                    >
                        <swiper-slide v-for="(course, index) in displayedNewRelease" :key="`release-${index}`" class="w-40">
                            <div class="shrink-0 bg-white rounded-lg overflow-hidden">
                                <img
                                    src="/images/release_thumbnail.svg"
                                    class="w-full h-24 object-cover"
                                    alt="Course thumbnail"
                                />
                                <div class="p-2">
                                    <p class="text-xs text-gray-500">{{ course.type }}</p>
                                    <p class="text-sm font-semibold leading-tight title_hidden">{{ course.title }}</p>
                                    <p class="text-xs text-gray-500 mt-1">By: {{ course.author }}</p>
                                </div>
                            </div>
                        </swiper-slide>
                    </swiper>
                </div>

                <!-- 30min or Less Section -->
                <div class="section_box">
                    <div class="flex justify-between items-center mb-2 release_top_section">
                        <h3 class="text-xl font-bold">30min or Less</h3>
                        <div class="space-x-2 main_left_right_button">
                            <button class="swiper-button-prev3 left_right_button"><img src="/images/left_side_icon.svg" /></button>
                            <button class="swiper-button-next3 left_right_button"><img src="/images/right_side_icon.svg" /></button>
                        </div>
                    </div>

                    <swiper
                        :modules="modules"
                        :navigation="{
                            nextEl: '.swiper-button-next3',
                            prevEl: '.swiper-button-prev3',
                        }"
                        :slides-per-view="'auto'"
                        :space-between="16"
                        class="mySwiper"
                    >
                        <swiper-slide v-for="(newRelease, index) in displayedNewRelease" :key="`min-${index}`" class="w-40">
                            <div class="shrink-0 bg-white rounded-lg overflow-hidden">
                                <img
                                    src="/images/release_thumbnail.svg"
                                    class="w-full h-24 object-cover"
                                    alt="Course thumbnail"
                                />
                                <div class="p-2">
                                    <p class="text-xs text-gray-500">{{ newRelease.type }}</p>
                                    <p class="text-sm font-semibold leading-tight title_hidden">{{ newRelease.title }}</p>
                                    <p class="text-xs text-gray-500 mt-1">By: {{ newRelease.author }}</p>
                                </div>
                            </div>
                        </swiper-slide>
                    </swiper>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import 'swiper/css/navigation';
import { Navigation } from 'swiper/modules';

export default {
    components: {
        AuthenticatedLayout,
        Head,
        Swiper,
        SwiperSlide
    },
    data() {
        return {
            modules: [Navigation],
            newRelease: [
                {
                    title: 'Fall Boost: Test-Driven Development',
                    author: 'Syed Usman',
                    type: 'Course'
                },
                {
                    title: 'Fall for Beginners',
                    author: 'Ayesha Nadeem',
                    type: 'Course'
                },
                {
                    title: 'Fall Boost: Test-Driven Development',
                    author: 'Syed Usman',
                    type: 'Course'
                },
                {
                    title: 'Fall for Beginners',
                    author: 'Ayesha Nadeem',
                    type: 'Course'
                },
                {
                    title: 'Fall Boost: Test-Driven Development',
                    author: 'Syed Usman',
                    type: 'Course'
                },
                {
                    title: 'Fall for Beginners',
                    author: 'Ayesha Nadeem',
                    type: 'Course'
                },
                {
                    title: 'Fall Boost: Test-Driven Development',
                    author: 'Syed Usman',
                    type: 'Course'
                },
                {
                    title: 'Fall for Beginners',
                    author: 'Ayesha Nadeem',
                    type: 'Course'
                }
            ],
            selectedCourseType: 'java', // default selected
            courseJavaList: [
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
            ],
            courseDatabaseList: [
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
            ]
        };
    },
    computed: {
        displayedCourses() {
            return this.selectedCourseType === 'java'
                ? this.courseJavaList
                : this.courseDatabaseList;
        },
        displayedNewRelease() {
            return this.newRelease;
        }
    },
    methods: {
        showCourses(type) {
            this.selectedCourseType = type;
        }
    }
};
</script>

<style>
.section_box {
    background-color: white;
    padding: 20px;
    border-radius: 16px;
}
.home_page_style {
    background-color: #acacac;
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
    width: 150px;
    margin-right: 24px !important;
}
.title_hidden{
        height: 18px;
    overflow: hidden;
}
</style>