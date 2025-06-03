<template>

    <Head :title="course ? `Playing: ${course.title}` : 'Course Player'" />

    <AuthenticatedLayout v-slot="{ isSidebarOpen, isPlayerPage }">
        <div class="flex bg-gray-100" style="height: 100%">
            <!-- Sidebar for Videos -->
            <div v-if="isPlayerPage && (isSidebarOpen || isLargeScreen)"
                class="w-80 bg-gray-800 text-white p-4 space-y-4 overflow-y-auto flex-shrink-0 player_sidebar">
                <h2 class="text-xl font-semibold mb-4">{{ course.title }}</h2>
                <ul class="space-y-2">
                    <li v-for="video in sortedVideos" :key="video.id">
                        <button @click="selectVideo(video)"
                            :class="['w-full text-left px-3 py-2 rounded-md text-sm transition-colors',
                                currentVideo && currentVideo.id === video.id ? 'bg-blue-500 text-white' : 'hover:bg-gray-700']">
                            {{ video.title }}
                        </button>
                    </li>
                </ul>
                <div class="mt-auto pt-4">
                    <Link :href="route('courses.show', { course: course.id })"
                        class="block w-full text-center px-3 py-2 rounded-md text-sm bg-gray-600 hover:bg-gray-500 transition-colors">
                    Back to Course Details
                    </Link>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col ">
                <!-- Video Player -->
                <div class="bg-black flex-shrink-0">
                    <video v-if="currentVideo && currentVideo.video_url" :key="currentVideo.id"
                        :src="currentVideo.video_url" controls autoplay
                        class="w-full h-[60vh] object-contain player_video" @ended="playNextVideo">
                        Your browser does not support the video tag.
                    </video>
                    <div v-else class="w-full h-[60vh] bg-black flex items-center justify-center text-white">
                        <p v-if="!course.videos || course.videos.length === 0">No videos available for this course.</p>
                        <p v-else>Select a video to play.</p>
                    </div>
                </div>

                <!-- Video Details -->
                <div class="p-6 -auto bg-white flex-1">

                    <div v-if="currentVideo">
                        <h1 class="text-2xl font-bold mb-2">{{ currentVideo.title }}</h1>
                        <div>
                            <div style="font-size: 16px; font-weight: 600; color: #7E7E7E">
                                Instructor
                            </div>
                            <div class="flex items-center mt-2" style="gap: 14px;">
                                <img :src="course.user.profile_photo_url ? course.user.profile_photo_url : '/images/profile_photo.jpg'"
                                    style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;" />
                                <span class="ml-2 "
                                    style="font-size: 13px; font-weight: 400; display: flex; flex-direction: column; gap: 8px;">{{
                                        course.user.name }} <button class="text-[#2C15F5] text-xs"
                                        style="font-size: 14px; font-weight: 400; border: 1px solid #2C15F5; border-radius: 20px; padding: 4px 19px;">+
                                        Follow</button></span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div style="font-size: 16px; font-weight: 600; color:black">
                                Video Discription
                                <p class="text-black-400 whitespace-pre-wrap" style="font-size: 14px; line-height: 16px;">{{
                            currentVideo.description || 'No description available.' }}</p>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div style="font-size: 16px; font-weight: 600; color:black">
                                Course Details
                                <div class="flex items-center mt-1" style="gap: 16px; color: #7E7E7E">
                                    <p>{{course.type}}</p>
                                    <p>Updated: {{ course.updated_at }}</p>
                                </div>
                                <div class="mt-2">Course Description
                                    <p class="text-black-400 whitespace-pre-wrap mt-1" style="font-size: 14px;">{{
                            course.description || 'No description available.' }}</p>
                                </div>
                                <div class="mt-2">Course Additional Description
                                    <p class="text-black-400 whitespace-pre-wrap mt-1" style="font-size: 14px; line-height: 16px;">{{
                            course.additional_description || 'No additional description available.' }}</p>
                                </div>
                                <div class="mt-2">Course Recommendations
                                    <p class="text-black-400 whitespace-pre-wrap mt-1" style="font-size: 14px; line-height: 16px;">{{
                                course.recommendations || 'No recommendations available.' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Comments Section -->
                        <div class="mt-8">
                            <h3 class="text-xl font-semibold mb-4">Comments ({{ totalCommentsCount }})</h3>
                            <!-- Display existing comments -->
                            <div v-if="displayedComments.length > 0" class="space-y-4 mb-6">
                                <div v-for="comment in displayedComments" :key="comment.id" class="p-4 bg-gray-50 border border-[#7E7E7E]">
                                    <div class="flex items-center mb-2">
                                        <img :src="comment.user.profile_photo_url ? comment.user.profile_photo_url : '/images/profile_photo.jpg'" alt="User avatar" class="w-8 h-8 rounded-full mr-3" style="object-fit: cover;"/>
                                        <span class="" style="font-size: 13px; font-weight: 400; color: #000000;">{{ comment.user.name }}</span>
                                        <span class="text-xs text-gray-500 ml-auto" style="font-size: 12px; font-weight: 400; color: black;">{{ new Date(comment.created_at).toLocaleString() }}</span>
                                    </div>
                                    <p class="text-gray-700 text-sm" style="font-size: 13px; font-weight: 400; color: #000000;">{{ comment.body }}</p>
                                </div>
                            </div>
                            <div v-else-if="course.comments && course.comments.length === 0" class="text-gray-500 mb-6">
                                No comments yet. Be the first to comment!
                            </div>
                            <!-- Loading/placeholder can be added here if props.course.comments is initially undefined -->

                            <!-- Show More / Show Less Buttons -->
                            <div class="mt-4 mb-6" style="display: flex; justify-content: center; align-items: center; ">
                                <button v-if="hasMoreComments"
                                        @click="showMoreComments"
                                        class="text-sm text-[#2C15F5] hover:text-[#5f4fed]" style="font-size: 18px; font-weight: 600; ">
                                    Show More Comments 
                                    <!-- ({{ totalCommentsCount - visibleCommentsCount }} remaining) -->
                                </button>
                                <button v-if="!hasMoreComments && visibleCommentsCount > COMMENTS_TO_SHOW_INCREMENT && totalCommentsCount > COMMENTS_TO_SHOW_INCREMENT"
                                        @click="showLessComments"
                                        class="text-sm text-[#2C15F5] hover:text-[#5f4fed] font-semibold" style="font-size: 18px; font-weight: 600; ">
                                    Show Less Comments
                                </button>
                            </div>

                            <!-- New comment form -->
                            <div>
                                <textarea v-model="newComment" rows="3" placeholder="Add a comment..." class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
                                <button @click="submitComment" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors text-sm">Post Comment</button>
                            </div>
                        </div>
                       
                    </div>

                    <div v-else>
                        <p class="text-gray-600">Video details will appear here once a video is selected.</p>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed, onMounted, watch, onUnmounted } from 'vue';

const props = defineProps({
    course: Object, // Contains course details and an array of its videos
    initialVideoId: [String, Number, null], // Optional ID of the video to play first
});

const currentVideo = ref(null);
const isLargeScreen = ref(window.innerWidth > 770); // Reactive variable for screen size
const newComment = ref(''); // For the new comment textarea

const updateScreenSize = () => {
    isLargeScreen.value = window.innerWidth > 770;
};

const COMMENTS_TO_SHOW_INCREMENT = 3;
const visibleCommentsCount = ref(COMMENTS_TO_SHOW_INCREMENT);

const sortedVideos = computed(() => {
    if (!props.course || !props.course.videos) {
        return [];
    }
    // Ensure videos are sorted by their 'order' property
    return [...props.course.videos].sort((a, b) => a.order - b.order);
});

const selectVideo = (video) => {
    currentVideo.value = video;
};

const playNextVideo = () => {
    if (!currentVideo.value || !sortedVideos.value.length) return;

    const currentIndex = sortedVideos.value.findIndex(v => v.id === currentVideo.value.id);
    if (currentIndex !== -1 && currentIndex < sortedVideos.value.length - 1) {
        selectVideo(sortedVideos.value[currentIndex + 1]);
    } else {
        // Optionally, handle what happens when the last video ends (e.g., show a message, loop, etc.)
        console.log("Last video finished.");
    }
};

// Function to handle comment submission
const submitComment = () => {
    if (!newComment.value.trim()) return;

    const form = useForm({
        course_id: props.course.id,
        body: newComment.value,
    });

    form.post(route('comments.store'), {
        preserveScroll: true,
        onSuccess: () => {
            newComment.value = '';
            // Reset visible comments count if you want to show the latest comment on top
            // or adjust based on how comments are reloaded/sorted.
            // For now, we assume comments are reloaded and sorted, so new ones appear.
            // If not, you might need Inertia.reload or manual update of props.course.comments
        },
        onError: (errors) => {
            console.error('Error posting comment:', errors);
        }
    });
};

const displayedComments = computed(() => {
    if (!props.course || !props.course.comments) {
        return [];
    }
    return props.course.comments.slice(0, visibleCommentsCount.value);
});

const totalCommentsCount = computed(() => {
    return props.course && props.course.comments ? props.course.comments.length : 0;
});

const hasMoreComments = computed(() => {
    return visibleCommentsCount.value < totalCommentsCount.value;
});

const showMoreComments = () => {
    visibleCommentsCount.value += COMMENTS_TO_SHOW_INCREMENT;
};

const showLessComments = () => {
    visibleCommentsCount.value = COMMENTS_TO_SHOW_INCREMENT;
};

onMounted(() => {
    window.addEventListener('resize', updateScreenSize); // Add resize listener
    updateScreenSize(); // Initial check

    if (props.initialVideoId && sortedVideos.value.length > 0) {
        const videoToPlay = sortedVideos.value.find(v => v.id == props.initialVideoId);
        if (videoToPlay) {
            selectVideo(videoToPlay);
        } else if (sortedVideos.value.length > 0) {
            selectVideo(sortedVideos.value[0]); // Fallback to first video if initialVideoId is invalid
        }
    } else if (sortedVideos.value.length > 0) {
        selectVideo(sortedVideos.value[0]); // Play the first video if no initialVideoId is provided
    }
});

// Watch for changes in initialVideoId if the page is reloaded with a different video in the URL
watch(() => props.initialVideoId, (newId) => {
    if (newId && sortedVideos.value.length > 0) {
        const videoToPlay = sortedVideos.value.find(v => v.id == newId);
        if (videoToPlay) {
            selectVideo(videoToPlay);
        }
    } else if (!newId && currentVideo.value && sortedVideos.value.length > 0) {
        // If initialVideoId is removed (e.g. navigating to base player URL), perhaps keep current video or reset
        // For now, let's stick to the first video if no specific one is requested
        if (!currentVideo.value && sortedVideos.value.length > 0) {
            selectVideo(sortedVideos.value[0]);
        }
    }
});

onUnmounted(() => {
    window.removeEventListener('resize', updateScreenSize); // Remove resize listener
});

</script>

<style>
/* Ensure full height for layout */
.h-screen {
    height: 100vh;
}

.home_page_style {
    padding: 0px !important;
}

/* .player_sidebar{
    position: relative;
} */
@media (max-width: 1024px) {
    .player_video {
        height: 40vh !important;
    }
}

@media (max-width: 770px) {
    .player_sidebar {
        position: absolute;
        height: 100%;
        z-index: 2;

    }

    .player_video {
        height: 40vh !important;
    }
}

@media (min-width: 771px) {
    .player_sidebar {
        position: relative !important;
    }
}

@media (max-width: 450px) {
    .player_video {
        height: 30vh !important;
    }
}

/* Ensure video player does not exceed viewport height, adjust h-[60vh] as needed */
</style>