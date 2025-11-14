<template>
    <div class="linkedin-card dark:bg-gray-800">
        <div class="flex flex-row">
            <Link :href="route('courses.show', { course: course.id })" class="linkedin-card-img-wrap">
                <img :src="getThumbnailSrc(course)" class="linkedin-card-img" alt="Course thumbnail" />
            </Link>
            <div style="width: 100%; padding: 10px;">
                <p class="linkedin-card-type dark:text-[#d1d5db]">{{ course.type }}</p>
                <p class="linkedin-card-title dark:text-white">{{ course.title }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 h-10 overflow-hidden text-ellipsis dark:text-[#d1d5db]">{{ course.description || 'No description available.' }}</p>
            </div>
        </div>
        <div class="linkedin-card-body dark:text-white">
            <div class="w-full bg-gray-200 rounded-full h-1.5 mb-4 dark:bg-gray-700">
                <div class="bg-blue-600 h-1.5 rounded-full" :style="{ width: course.progress + '%' }"></div>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-300 mb-4">{{ Math.round(course.progress) }}% complete</p>

            <div class="linkedin-card-footer mt-auto">
                <p class="linkedin-card-author dark:text-[#d1d5db]">By: {{ course.author || 'Placeholder' }}</p>
                <button @click.stop.prevent="emitToggleFavorite" class="linkedin-card-fav-btn">
                    <svg v-if="course.is_favorited" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-red-500">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-600 dark:text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                    </svg>
                </button>
            </div>
            <div class="flex items-center justify-between mt-4 space-x-2">
                <Link :href="route('courses.show', { course: course.id })" class="course-action-btn-details dark:bg-gray-700 dark:text-white dark:border-gray-600">
                    Details
                </Link>
                
                <!-- <div v-if="course.is_purchased" class="flex-grow"> -->
                    <Link :href="course.first_video_id ? route('courses.play', { course: course.id, video: course.first_video_id }) : '#'" 
                          class="course-action-btn-play dark:bg-blue-600 dark:text-white w-full">
                        Play Course
                    </Link>
                <!-- </div> -->
                <!-- <div v-else class="flex-grow">
                    <Link :href="route('cart', { course_id: course.id })" class="course-action-btn-buy dark:bg-gray-700 dark:text-white dark:border-gray-600 w-full">
                        <span>Buy Now</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </Link>
                </div> -->
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    course: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['toggle-favorite']);

const getThumbnailSrc = (course) => {
    return course.first_video_thumbnail_url ? course.first_video_thumbnail_url : '/images/skill_section_thumbnail.svg';
};

const emitToggleFavorite = () => {
    emit('toggle-favorite', props.course);
};
</script>

<style scoped>
.linkedin-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: box-shadow 0.2s, transform 0.2s;
    border: 1px solid #e6e6e6;
    width: 100%;
}
.linkedin-card:hover {
    box-shadow: 0 8px 24px rgba(0,0,0,0.16);
    transform: translateY(-2px);
}
.linkedin-card-img-wrap {
    width: 120px;
    height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px;
    padding-right: 0px;
}
.linkedin-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    background-color: white;   
    border-radius: 10px;
}
.linkedin-card-body {
    padding: 16px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}
.linkedin-card-type {
    font-size: 12px;
    color: #6b7280;
    margin-bottom: 4px;
}
.linkedin-card-title {
    font-size: 16px;
    font-weight: 600;
    color: #222;
    height: 23px;
    overflow: hidden;
}
.linkedin-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.linkedin-card-author {
    font-size: 12px;
    color: #6b7280;
}
.linkedin-card-fav-btn {
    background: none;
    border: none;
    padding: 4px;
    cursor: pointer;
    border-radius: 50%;
}

.course-action-btn-details, .course-action-btn-play, .course-action-btn-buy {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
    text-align: center;
    transition: background-color 0.3s, color 0.3s;
    flex-grow: 1;
}

.course-action-btn-details {
    background-color: transparent;
    border: 1px solid #7E7E7E;
    color: #333;
}

.course-action-btn-play {
    background-color: #0073b1;
    color: white;
    border: 1px solid transparent;
}

.course-action-btn-buy {
    background-color: transparent;
    border: 1px solid #7E7E7E;
    color: #333;
}

.course-action-btn-details:hover, .course-action-btn-buy:hover {
    background-color: #f0f0f0;
}

.course-action-btn-play:hover {
    background-color: #005a8c;
}

.dark .course-action-btn-details:hover, .dark .course-action-btn-buy:hover{
    background-color: #202020 !important;
}

.dark .linkedin-card {
    background-color: #0F212D;
    border-color: #444;
}
.dark .linkedin-card-type, .dark .linkedin-card-author {
    color: #d1d5db;
}
.dark .linkedin-card-title {
    color: #fff;
}
.dark .text-sm {
    color: #d1d5db;
}
.dark .bg-gray-200 {
    background-color: #4a4a4a;
}
.dark .text-gray-500 {
    color: #a0aec0;
}
.dark .text-gray-600 {
    color: #a0aec0;
}
.dark .course-action-btn-details, .dark .course-action-btn-buy {
    background-color: #3f3f3f;
    color: #fff;
    border-color: #555;
}
.dark .course-action-btn-play {
    background-color: #0073b1;
}
</style> 