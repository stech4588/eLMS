<template>

    <Head :title="course ? `Playing: ${course.title}` : 'Course Player'" />

    <AuthenticatedLayout v-slot="{ isSidebarOpen, isPlayerPage }">
        <FeedbackPopup :show="showFeedbackPopup" :course="completedCourse" @close="closeFeedbackPopup" />
        <NotesPopup :show="showNotesPopup" :video="notesForVideo" @close="handleCloseNotesPopup" />
        <QuizPopup :show="showQuizPopup" :quiz="activeQuiz" @close="closeQuizPopup" @completed="onQuizCompleted" />
        <QuizResultPopup :show="showQuizResultPopup" :attempt="quizAttemptResult" @close="onQuizResultClosed" />
        <div class="player-page-wrap bg-gray-100 dark:bg-gray-900 min-h-screen relative">
            <AiChatbot :show="showChatbot" :chat-context="chatbotContext" :welcome-message="chatbotWelcomeMessage"
                :placeholder="chatbotPlaceholder" @close="showChatbot = false" />
            <div class="fixed bottom-4 right-4 z-40">
                <button @click="showChatbot = true"
                    class="bg-[#1C355E] text-white rounded-full p-4 shadow-lg hover:bg-[#254a7a] transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </button>
            </div>

            <div class="player-contained max-w-7xl mx-auto px-4 py-6">
                <div class="player-grid">
                    <!-- Left: Video + details -->
                    <div class="player-left">
                        <div v-if="!isLargeScreen" class="flex justify-end mb-2">
                            <button @click="isVideoSidebarOpen = !isVideoSidebarOpen" class="player-mobile-menu-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                        </div>

                        <div class="player-video-box bg-black rounded-lg overflow-hidden relative group">
                            <video v-if="currentVideo && currentVideo.video_url" ref="videoPlayer"
                                :key="currentVideo.id" :src="currentVideo.video_url" controls controlslist="nodownload"
                                @contextmenu.prevent autoplay @pause="onPause" @ended="handleEnded"
                                @loadedmetadata="handleLoadedMetadata" @play="onPlay" class="player-video-el">
                                Your browser does not support the video tag.
                            </video>

                            <!-- Custom overlay controls (play/pause + 10s skip) -->
                            <div v-if="currentVideo && currentVideo.video_url" class="player-overlay-controls">
                                <button type="button" class="player-overlay-button player-overlay-skip"
                                    @click.stop="skipBackward(10)">
                                    <!-- Back 10s icon -->
                                    <svg width="100" height="100" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
  <circle cx="12" cy="12" r="10" fill="black" fill-opacity="0.6"/>
  <path d="M12 5V2L8 6L12 10V7C15.31 7 18 9.69 18 13C18 16.31 15.31 19 12 19C8.69 19 6 16.31 6 13H4C4 17.42 7.58 21 12 21C16.42 21 20 17.42 20 13C20 8.58 16.42 5 12 5Z" fill="white"/>
  <text x="12" y="14.5" font-family="Arial, sans-serif" font-size="5" font-weight="bold" fill="white" text-anchor="middle">10</text>
</svg>
                                </button>

                                <button type="button" class="player-overlay-button player-overlay-play"
                                    @click.stop="togglePlayPause">
                                    <svg v-if="isPlaying" viewBox="0 0 24 24" fill="currentColor">
                                        <rect x="6" y="4" width="4" height="16" rx="1" />
                                        <rect x="14" y="4" width="4" height="16" rx="1" />
                                    </svg>
                                    <svg v-else viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M8 5v14l11-7z" />
                                    </svg>
                                </button>

                                <button type="button" class="player-overlay-button player-overlay-skip"
                                    @click.stop="skipForward(10)">
                                    <!-- Forward 10s icon -->
                                    
                                    <svg width="100" height="100" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
  <circle cx="12" cy="12" r="10" fill="black" fill-opacity="0.6"/>
  <path d="M12 5V2L16 6L12 10V7C8.69 7 6 9.69 6 13C6 16.31 8.69 19 12 19C15.31 19 18 16.31 18 13H20C20 17.42 16.42 21 12 21C7.58 21 4 17.42 4 13C4 8.58 7.58 5 12 5Z" fill="white"/>
  <text x="12" y="14.5" font-family="Arial, sans-serif" font-size="5" font-weight="bold" fill="white" text-anchor="middle">10</text>
</svg>
                                </button>
                            </div>


                            <div v-else
                                class="w-full aspect-video bg-black flex items-center justify-center text-white">
                                <p v-if="!course.videos || course.videos.length === 0">No videos available.</p>
                                <p v-else>Select a video to play.</p>
                            </div>
                        </div>

                        <!-- <div v-if="currentVideo && sortedVideos.length > 1" class="player-prev-next">
                            <button v-if="currentVideoIndex > 0" @click="playPreviousVideo"
                                class="player-nav-btn">Previous</button>
                            <div v-else></div>
                            <button v-if="currentVideoIndex < sortedVideos.length - 1" @click="playNextVideo"
                                class="player-nav-btn">Next</button>
                        </div> -->

                        <div v-if="currentVideo"
                            class="player-below-video bg-white dark:bg-dark-bg-secondary rounded-lg border border-gray-200 dark:border-gray-700 p-4 mt-4">
                            <div class="player-below-left">
                                <button @click="saveProgress(true, false)" class="player-mark-complete">
                                    Mark As Complete
                                </button>
                                <h1 class="player-video-title">{{ currentVideo.title }}</h1>
                                <p v-if="currentVideoSection" class="player-section-label">
                                    {{(currentVideoSection.order != null ? currentVideoSection.order :
                                        (courseSections.findIndex(s => s.id === currentVideoSection.id) + 1)) }}.
                                    {{ currentVideoSection.title }}
                                </p>
                            </div>


                        </div>
                    </div>
                    <div class="pr-div"><!-- Right: One section + Next Category -->
                        <div :class="['player-right', { 'open': isVideoSidebarOpen || isLargeScreen }]">
                            <div class="player-sidebar-inner no-scrollbar">
                                <div v-if="!isLargeScreen" class="flex justify-end mb-2">
                                    <button @click="isVideoSidebarOpen = false"
                                        class="text-gray-400 hover:text-white p-1">✕</button>
                                </div>
                                <template v-if="courseSections.length > 0 && currentSidebarSection">
                                    <h3 class="player-sidebar-heading">{{ romanNumeral(currentSectionIndex + 1) }}. {{
                                        currentSidebarSection.title }}</h3>
                                    <p class="player-sidebar-lessons">{{ (currentSidebarSection.videos || []).length }}
                                        Lessons</p>
                                    <ul class="player-lesson-list">
                                        <li v-for="vid in (currentSidebarSection.videos || [])" :key="vid.id">
                                            <button v-if="getVideoById(vid.id)"
                                                @click="selectVideo(getVideoById(vid.id))"
                                                :class="['player-lesson-item', currentVideo && currentVideo.id === vid.id ? 'player-lesson-item-active' : '']">
                                                <svg v-if="currentVideo && currentVideo.id === vid.id"
                                                    class="player-lesson-play-icon" fill="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path d="M8 5v14l11-7z" />
                                                </svg>
                                                <img v-if="getVideoById(vid.id).thumbnail_url"
                                                    :src="getVideoById(vid.id).thumbnail_url" :alt="vid.title"
                                                    class="player-lesson-thumb" />
                                                <div v-else class="player-lesson-thumb player-lesson-thumb-placeholder">
                                                </div>
                                                <span class="player-lesson-title">{{ vid.title }}</span>

                                            </button>
                                        </li>
                                    </ul>
                                    <div class="player-category-nav">
                                        <button v-if="hasPrevCategory" @click="prevCategory"
                                            class="player-category-btn">Previous Category</button>
                                        <button v-if="hasNextCategory" @click="nextCategory"
                                            class="player-category-btn player-category-btn-primary">Next
                                            Category</button>
                                    </div>
                                </template>
                                <template v-else>
                                    <h3 class="player-sidebar-heading">Lessons</h3>
                                    <ul class="player-lesson-list">
                                        <li v-for="video in sortedVideos" :key="video.id">
                                            <button @click="selectVideo(video)"
                                                :class="['player-lesson-item', currentVideo && currentVideo.id === video.id ? 'player-lesson-item-active' : '']">
                                                <img v-if="video.thumbnail_url" :src="video.thumbnail_url"
                                                    :alt="video.title" class="player-lesson-thumb" />
                                                <div v-else class="player-lesson-thumb player-lesson-thumb-placeholder">
                                                </div>
                                                <span class="player-lesson-title">{{ video.title }}</span>
                                                <svg v-if="currentVideo && currentVideo.id === video.id"
                                                    class="player-lesson-play-icon" fill="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path d="M8 5v14l11-7z" />
                                                </svg>
                                            </button>
                                        </li>
                                    </ul>
                                </template>

                                <Link :href="route('courses.show', { course: course.id })" class="player-back-link">Back
                                    to Course Details</Link>
                            </div>
                        </div>
                        <!-- Instructor card directly under right sidebar -->
                        <div class="max-w-7xl mx-auto px-4" v-if="course && course.user">
                            <div class="flex justify-end">
                                <div class="player-instructor-card-details" style="margin-top: 1rem;">
                                    <h3 class="player-instructor-heading-details">Instructor</h3>
                                    <div class="flex items-start gap-3">
                                        <div class="player-instructor-avatar-details">
                                            {{ getInitials(course.user.name) }}
                                        </div>
                                        <div>
                                            <p class="player-instructor-name-details">
                                                {{ course.user.name }}
                                            </p>
                                            <p class="player-instructor-role-details">
                                                Instructor
                                            </p>
                                            <p class="player-instructor-bio-details">
                                                Course creator and instructor.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


            <!-- Expanded details (description, etc.) below the grid -->
            <div class="max-w-7xl mx-auto px-4 pb-8">
                <div
                    class="p-6 lg:p-8 bg-white dark:bg-dark-bg-secondary dark:text-white rounded-lg border border-gray-200 dark:border-gray-700 mt-6">
                    <div v-if="currentVideo">
                        <h1 class="text-3xl font-bold mb-3 dark:text-white">{{ currentVideo.title }}</h1>

                        <!-- Video Description -->
                        <div class="mt-8 prose dark:prose-invert max-w-none">
                            <h2 class="text-xl font-semibold mb-3 dark:text-white">Video Description</h2>
                            <TruncatedText :text="currentVideo.description || 'No description available.'" />
                        </div>

                        <!-- Takeaway Notes - Conditional -->
                        <div v-if="currentVideo.takeaway_notes && currentVideoSavedProgress?.completed"
                            class="mt-8 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <div class="flex justify-between items-center mb-3">
                                <h2 class="text-xl font-semibold dark:text-white">Takeaway Notes</h2>
                            </div>
                            <TruncatedText :text="currentVideo.takeaway_notes" />
                        </div>

                        <!-- Course Details -->
                        <div class="mt-8">
                            <h2 class="text-xl font-semibold mb-3 dark:text-white">Course Details</h2>
                            <div
                                class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 text-gray-600 dark:text-gray-300">
                                <!-- <div class="flex items-center space-x-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span>{{ course.total_duration }}</span>
                                </div> -->
                                <div class="flex items-center space-x-3">
                                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01">
                                        </path>
                                    </svg>
                                    <span>{{ course.type }}</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span>Updated: {{ course.updated_at }}</span>
                                </div>
                                <div v-if="course.reviews_count > 0" class="flex items-center space-x-2">
                                    <span class="font-bold text-lg text-gray-800 dark:text-white">{{
                                        course.average_rating }}</span>
                                    <StarRating :rating="course.average_rating" />
                                    <span>({{ course.reviews_count.toLocaleString() }} ratings)</span>
                                </div>
                                <p v-else class="text-sm">No ratings yet.</p>
                            </div>

                            <div class="mt-6 prose dark:prose-invert max-w-none">
                                <h3 class="font-semibold">Course Description</h3>
                                <TruncatedText :text="course.description || 'No description available.'" />
                                <h3 class="font-semibold mt-4">Additional Information</h3>
                                <TruncatedText
                                    :text="course.additional_description || 'No additional description available.'" />
                                <h3 class="font-semibold mt-4">Recommendations</h3>
                                <TruncatedText :text="course.recommendations || 'No recommendations available.'" />
                            </div>
                        </div>

                        <!-- Related Courses -->
                        <div class="mt-8">
                            <h3 class="text-xl font-semibold mb-4 dark:text-white">Related Courses</h3>
                            <div v-if="isLoadingRelatedCourses">
                                <p class="text-gray-500 dark:text-gray-400">Loading related courses...</p>
                            </div>
                            <div v-else-if="relatedCourses.length > 0" class="space-y-4">
                                <div v-for="relatedCourse in relatedCourses" :key="relatedCourse.id"
                                    class="flex items-center space-x-4 border-b border-gray-200 dark:border-gray-700 pb-4 last:border-b-0 last:pb-0">

                                    <!-- Thumbnail -->
                                    <Link :href="route('courses.show', { course: relatedCourse.id })"
                                        class="flex-shrink-0 relative w-40 h-24">
                                        <img :src="relatedCourse.thumbnail_url ? relatedCourse.thumbnail_url : '/images/default_course_thumbnail.jpg'"
                                            alt="Course Thumbnail" class="w-full h-full object-cover rounded-lg">
                                        <div v-if="relatedCourse.total_duration"
                                            class="absolute bottom-1 right-1 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">
                                            {{ relatedCourse.total_duration }}
                                        </div>
                                        <div v-if="relatedCourse.is_popular"
                                            class="absolute top-1 left-1 bg-white text-gray-800 text-xs font-semibold px-2 py-1 rounded shadow">
                                            Popular
                                        </div>
                                    </Link>

                                    <!-- Course Info -->
                                    <div class="flex-grow">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Course</p>
                                        <Link :href="route('courses.show', { course: relatedCourse.id })"
                                            class="hover:text-gray-900 dark:hover:text-gray-200">
                                            <h4 class="text-lg font-semibold truncate dark:text-white">{{
                                                relatedCourse.title
                                                }}</h4>
                                        </Link>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{
                                            relatedCourse.learners_count }} learners</p>
                                    </div>

                                    <!-- Bookmark Icon -->
                                    <div class="flex-shrink-0">
                                        <button @click.prevent="toggleFavorite(relatedCourse)" class="p-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                                :class="relatedCourse.is_favorited ? 'text-green-600 fill-current' : 'text-gray-400'"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                <p class="text-gray-500 dark:text-gray-400">No related courses found.</p>
                            </div>
                        </div>

                        <!-- Comments Section -->
                        <div class="mt-8">
                            <h2 class="text-2xl font-semibold mb-4 dark:text-white">Comments ({{ totalCommentsCount }})
                            </h2>

                            <!-- New comment form -->
                            <div class="flex items-start space-x-4 mb-8">
                                <div
                                    class="w-10 h-10 rounded-full bg-[#1C355E] text-white flex items-center justify-center font-semibold text-sm">
                                    {{ getInitials(authUser && authUser.name ? authUser.name : 'You') }}
                                </div>
                                <div class="flex-1 relative">
                                    <textarea v-model="newComment" rows="1" placeholder="Add a comment..."
                                        @focus="isCommentFocused = true"
                                        class="w-full p-3 bg-transparent border-b border-gray-300 dark:border-gray-600 focus:border-green-500 focus:ring-0 transition resize-none"
                                        style="outline: none;"></textarea>
                                    <div v-if="isCommentFocused" class="flex justify-between items-center mt-2">
                                        <button @click="showEmojiPicker = !showEmojiPicker"
                                            class="p-2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                        <div class="space-x-2">
                                            <button @click="cancelComment"
                                                class="px-4 py-2 text-sm font-semibold text-gray-600 dark:text-gray-300 bg-transparent hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                                Cancel
                                            </button>
                                            <button @click="submitComment" :disabled="!newComment.trim()"
                                                class="px-6 py-2 bg-[#1C355E] text-white rounded-lg hover:bg-[#254a7a] disabled:bg-gray-400 dark:disabled:bg-gray-600 transition-colors text-sm font-semibold">
                                                Comment
                                            </button>
                                        </div>
                                    </div>
                                    <div v-if="showEmojiPicker" class="absolute z-10 mt-2">
                                        <EmojiPicker :native="true" @select="onSelectEmoji" />
                                    </div>
                                </div>
                            </div>

                            <!-- Display existing comments -->
                            <div v-if="displayedComments.length > 0" class="space-y-6">
                                <div v-for="comment in displayedComments" :key="comment.id"
                                    class="flex items-start space-x-4">
                                    <img :src="comment.user.profile_photo_url ? comment.user.profile_photo_url : '/images/profile_photo.jpg'"
                                        alt="User avatar" class="w-10 h-10 rounded-full object-cover" />
                                    <div class="flex-1">
                                        <div class="flex items-baseline space-x-2">
                                            <span class="font-semibold dark:text-white">@{{ comment.user.name }}</span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ new
                                                Date(comment.created_at).toLocaleString() }}</span>
                                        </div>
                                        <p class="text-gray-800 dark:text-gray-300 mt-1">{{ comment.body }}</p>
                                    </div>
                                </div>
                            </div>
                            <div v-else-if="course.comments && course.comments.length === 0"
                                class="text-gray-500 py-8 text-center">
                                Be the first to comment!
                            </div>

                            <!-- Show More / Show Less Buttons -->
                            <div class="mt-6 text-center" v-if="totalCommentsCount > COMMENTS_TO_SHOW_INCREMENT">
                                <button v-if="hasMoreComments" @click="showMoreComments"
                                    class="text-sm font-semibold text-gray-900 dark:text-gray-200 hover:underline">
                                    Show More Comments
                                </button>
                                <button v-else @click="showLessComments"
                                    class="text-sm font-semibold text-gray-900 dark:text-gray-200 hover:underline">
                                    Show Less Comments
                                </button>
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
import { Head, Link, useForm, usePage, router } from '@inertiajs/vue3';
import { ref, computed, onMounted, watch, onUnmounted } from 'vue';
import axios from 'axios'; // Import axios
import FeedbackPopup from '@/Components/FeedbackPopup.vue';
import EmojiPicker from 'vue3-emoji-picker';
import 'vue3-emoji-picker/css';
import StarRating from '@/Components/StarRating.vue';
import AiChatbot from '@/Components/AiChatbot.vue';
import TruncatedText from '@/Components/TruncatedText.vue';
import NotesPopup from '@/Components/NotesPopup.vue';
import QuizPopup from '@/Components/QuizPopup.vue';
import QuizResultPopup from '@/Components/QuizResultPopup.vue';

const page = usePage();
page.props.meta = { ...page.props.meta, disableLoader: true };

const props = defineProps({
    course: Object, // Contains course details and an array of its videos
    initialVideoId: [String, Number, null], // Optional ID of the video to play first
});

const getInitials = (name) => {
    if (!name || typeof name !== 'string') return '';
    const parts = name.trim().split(/\s+/).filter(Boolean);
    if (!parts.length) return '';
    return parts
        .slice(0, 2)
        .map((p) => p.charAt(0).toUpperCase())
        .join('');
};

const showFeedbackPopup = ref(false);
const completedCourse = ref(null);
const showChatbot = ref(false);
const showNotesPopup = ref(false);
const notesForVideo = ref(null);
const showQuizPopup = ref(false);
const activeQuiz = ref(null);
const showQuizResultPopup = ref(false);
const quizAttemptResult = ref(null);

const chatbotContext = computed(() => {
    if (currentVideo.value) {
        return {
            title: `Course: ${props.course.title} | Video: ${currentVideo.value.title}`,
            description: `The user is watching a video titled "${currentVideo.value.title}" within the course "${props.course.title}".\n\nVideo Description: ${currentVideo.value.description || 'No description available.'}\n\nCourse Description: ${props.course.description}`
        };
    }
    return {
        title: `Course: ${props.course.title}`,
        description: `The user is viewing the course "${props.course.title}".\n\nCourse Description: ${props.course.description}`
    };
});

const chatbotWelcomeMessage = computed(() => {
    return currentVideo.value
        ? "Hello! How can I help you with this video?"
        : "Hello! How can I help you with this course?";
});

const chatbotPlaceholder = computed(() => {
    return currentVideo.value
        ? "Ask me anything about this video..."
        : "Ask me anything about this course...";
});

function handleCourseCompletion() {
    showFeedbackPopup.value = true;
    completedCourse.value = props.course;
}

function closeFeedbackPopup() {
    showFeedbackPopup.value = false;
    completedCourse.value = null;
    if (props.course.quizzes && props.course.quizzes.length > 0) {
        activeQuiz.value = props.course.quizzes[0];
        showQuizPopup.value = true;
    }
}

function closeQuizPopup() {
    showQuizPopup.value = false;
    activeQuiz.value = null;
}

const currentVideo = ref(null);
const currentSectionIndex = ref(0); // Which section is visible in right sidebar (one section at a time)
const isLargeScreen = ref(window.innerWidth > 770);
const isVideoSidebarOpen = ref(false);
const newComment = ref(''); // For the new comment textarea
const isCommentFocused = ref(false); // For showing comment buttons
const showEmojiPicker = ref(false); // For emoji picker visibility
const videoPlayer = ref(null); // Ref for the video element
const { props: pageProps } = usePage();
const authUser = computed(() => page.props.auth.user);
const currentVideoSavedProgress = ref(null); // To store fetched progress
const initialTimeApplied = ref(false); // New ref to track if initial time has been set
const isPlaying = ref(true); // For play/pause toggle, defaults to true due to autoplay
let lastProgressSaveTime = 0;
const progressSaveInterval = 5000; // Save progress every 5 seconds

const downloadNotes = () => {
    if (!currentVideo.value || !currentVideo.value.takeaway_notes) {
        return;
    }

    const notes = currentVideo.value.takeaway_notes;
    const title = currentVideo.value.title || 'video';
    const filename = `${title}-notes.txt`;

    const element = document.createElement('a');
    element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(notes));
    element.setAttribute('download', filename);

    element.style.display = 'none';
    document.body.appendChild(element);

    element.click();

    document.body.removeChild(element);
};

const relatedCourses = ref([]);
const isLoadingRelatedCourses = ref(true);

const progressForm = useForm({
    user_id: null,
    video_id: null,
    watched_duration: 0,
    completed: false,
    last_watched_at: null,
});

const togglePlayPause = () => {
    if (videoPlayer.value) {
        if (videoPlayer.value.paused) {
            videoPlayer.value.play();
        } else {
            videoPlayer.value.pause();
        }
    }
};

const skipForward = (seconds) => {
    const player = videoPlayer.value;
    if (!player || player.readyState < 1) {
        return;
    }
    const duration = Number.isFinite(player.duration) ? player.duration : 0;
    const current = Number.isFinite(player.currentTime) ? player.currentTime : 0;
    const rawTarget = current + seconds;
    const maxTarget = duration > 0 ? Math.min(rawTarget, duration - 0.5) : rawTarget;
    const safeTarget = Math.max(0, maxTarget);
    player.currentTime = safeTarget;
};

const skipBackward = (seconds) => {
    const player = videoPlayer.value;
    if (!player || player.readyState < 1) {
        return;
    }
    const current = Number.isFinite(player.currentTime) ? player.currentTime : 0;
    const safeTarget = Math.max(0, current - seconds);
    player.currentTime = safeTarget;
};

const COMMENTS_TO_SHOW_INCREMENT = 3;
const visibleCommentsCount = ref(COMMENTS_TO_SHOW_INCREMENT);

const sortedVideos = computed(() => {
    if (!props.course || !props.course.videos) {
        return [];
    }
    return [...props.course.videos].sort((a, b) => a.order - b.order);
});

const getVideoById = (id) => sortedVideos.value.find(v => v.id === id);

// One section at a time in sidebar
const courseSections = computed(() => props.course?.sections || []);
const currentSidebarSection = computed(() => {
    const sections = courseSections.value;
    if (!sections.length) return null;
    const idx = Math.min(currentSectionIndex.value, sections.length - 1);
    return sections[idx];
});
const hasNextCategory = computed(() => courseSections.value.length > 0 && currentSectionIndex.value < courseSections.value.length - 1);
const hasPrevCategory = computed(() => currentSectionIndex.value > 0);
const nextCategory = () => { if (hasNextCategory.value) currentSectionIndex.value++; };
const prevCategory = () => { if (hasPrevCategory.value) currentSectionIndex.value--; };

// Section that contains the current video (for display below player)
const currentVideoSection = computed(() => {
    if (!currentVideo.value || !courseSections.value.length) return null;
    for (const section of courseSections.value) {
        if (section.videos && section.videos.some(v => v.id === currentVideo.value.id)) return section;
    }
    return null;
});
const romanNumeral = (n) => {
    const map = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII', 'XIII', 'XIV', 'XV'];
    return map[n - 1] != null ? map[n - 1] : String(n);
};

const currentVideoIndex = computed(() => {
    if (!currentVideo.value || !sortedVideos.value.length) {
        return -1;
    }
    return sortedVideos.value.findIndex(v => v.id === currentVideo.value.id);
});

const selectVideo = (video) => {
    console.log("selectVideo called for video:", video ? video.id : 'null');
    if (currentVideo.value && videoPlayer.value) {
        console.log(`selectVideo: Checking progress for outgoing video ${currentVideo.value.id}. Player state: ended=${videoPlayer.value.ended}, currentTime=${videoPlayer.value.currentTime}, duration=${videoPlayer.value.duration}`);
        if (!videoPlayer.value.ended && videoPlayer.value.currentTime > 0 && videoPlayer.value.duration > 0) {
            const isOutgoingCompleted = videoPlayer.value.currentTime >= videoPlayer.value.duration - 2;
            console.log(`selectVideo: Saving progress for outgoing video ${currentVideo.value.id}. Completed: ${isOutgoingCompleted}`);
            saveProgress(isOutgoingCompleted, false); // Foreground save
        } else {
            console.log(`selectVideo: Not saving progress for outgoing video ${currentVideo.value.id}. Ended: ${videoPlayer.value.ended}, CurrentTime: ${videoPlayer.value.currentTime}, Duration: ${videoPlayer.value.duration}`);
        }
    }

    currentVideo.value = video;
    lastProgressSaveTime = 0;
    currentVideoSavedProgress.value = null;
    initialTimeApplied.value = false;

    if (video && courseSections.value.length) {
        const idx = courseSections.value.findIndex(s => s.videos && s.videos.some(v => v.id === video.id));
        if (idx >= 0) currentSectionIndex.value = idx;
    }

    if (video) {
        fetchVideoProgress(video.id);
        console.log(`selectVideo: Switched to video ${video.id}. Player should reload due to :key change.`);
    } else {
        console.log("selectVideo: Cleared current video.");
    }
    // Autoplay is handled by the :key change on video and 'autoplay' attribute
    // If videoPlayer.value is available, we could call .load() and .play()
    // but changing the :src and :key should be sufficient for most browsers with autoplay.
};

const playPreviousVideo = () => {
    if (currentVideoIndex.value > 0) {
        selectVideo(sortedVideos.value[currentVideoIndex.value - 1]);
    }
};

const playNextVideo = () => {
    if (currentVideoIndex.value !== -1 && currentVideoIndex.value < sortedVideos.value.length - 1) {
        selectVideo(sortedVideos.value[currentVideoIndex.value + 1]);
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
            isCommentFocused.value = false;
            showEmojiPicker.value = false; // Hide emoji picker on success
            // Reset visible comments count if you want to show the latest comment on top
            // or adjust based on how comments are reloaded/sorted.
            // For now, we assume comments are reloaded and sorted, so new ones appear.
        },
        onError: (errors) => {
            console.error('Error posting comment:', errors);
        }
    });
};

const cancelComment = () => {
    newComment.value = '';
    isCommentFocused.value = false;
    showEmojiPicker.value = false;
};

const onSelectEmoji = (emoji) => {
    newComment.value += emoji.i;
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

const toggleFavorite = (course) => {
    // Optimistically update UI
    course.is_favorited = !course.is_favorited;

    router.post(route('courses.toggleFavorite', { course: course.id }), {}, {
        preserveScroll: true,
        onError: () => {
            // Revert optimistic update on error
            course.is_favorited = !course.is_favorited;
            // Optionally show an error message
        }
    });
};

const fetchRelatedCourses = async () => {
    if (!props.course) return;
    isLoadingRelatedCourses.value = true;
    try {
        // Assuming you have a route like 'courses.related' that takes a course ID
        const response = await axios.get(route('courses.related', { course: props.course.id }));
        relatedCourses.value = response.data;
    } catch (error) {
        console.error('Error fetching related courses:', error);
        relatedCourses.value = []; // Ensure it's an array on error
    } finally {
        isLoadingRelatedCourses.value = false;
    }
};

const handlePause = () => {
    if (videoPlayer.value && videoPlayer.value.readyState >= 2 && !videoPlayer.value.ended && videoPlayer.value.duration > 0) {
        saveProgress(false, false);
    }
};

const handleEnded = () => {
    saveProgress(true, false);
};

function maybeShowVideoQuizFlow() {
    if (currentVideo.value && currentVideo.value.quiz) {
        activeQuiz.value = currentVideo.value.quiz;
        showQuizPopup.value = true;
        return true;
    }
    return false;
}

const handleCloseNotesPopup = () => {
    showNotesPopup.value = false;
    notesForVideo.value = null;
    axios.get(route('courses.completionStatus', { course: props.course.id }))
        .then(response => {
            if (response.data.is_completed) {
                handleCourseCompletion();
            } else {
                playNextVideo();
            }
        })
        .catch(error => {
            console.error('Error checking completion status after closing notes:', error);
            playNextVideo();
        });
};

const saveProgress = (isExplicitlyCompleted = false, isBackgroundSave = false) => {
    // Get auth user at the start of the function
    const { props: pageProps } = usePage();
    const currentUser = pageProps.auth?.user;

    console.log('[[SAVE PROGRESS ATTEMPT]]: Function saveProgress initiated.', {
        isExplicitlyCompleted,
        videoId: currentVideo.value?.id,
        isBackgroundSave
    });

    if (!currentVideo.value || !videoPlayer.value || !currentUser) {
        console.error("saveProgress: Aborting. Missing currentVideo, videoPlayer, or currentUser.", {
            hasVideo: !!currentVideo.value,
            hasPlayer: !!videoPlayer.value,
            hasUser: !!currentUser,
            videoPlayerCurrentTime: videoPlayer.value ? videoPlayer.value.currentTime : 'N/A'
        });
        return;
    }

    const currentTime = Math.floor(videoPlayer.value.currentTime);
    const duration = Math.floor(videoPlayer.value.duration);

    // Avoid saving if video hasn't played or no significant change
    if (currentTime === 0 && !isExplicitlyCompleted && duration > 0) {
        console.log("saveProgress: Aborting. No progress (currentTime is 0) and not explicitly completed.");
        return;
    }
    if (isNaN(duration) || duration <= 0) {
        console.log("saveProgress: Aborting. Duration is not valid.");
        return;
    }

    const payload = {
        user_id: currentUser.id,
        video_id: currentVideo.value.id,
        watched_duration: currentTime,
        completed: isExplicitlyCompleted || (duration > 0 && currentTime >= duration - 2),
        last_watched_at: new Date().toISOString().slice(0, 19).replace('T', ' '),
    };

    if (isBackgroundSave) {
        console.log('Saving progress (background - axios) with data:', payload);
        axios.post(route('progress.storeUserVideoProgress'), payload, {
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
            .then(response => {
                if (response.data.course_completed) {
                    handleCourseCompletion();
                }
                // console.log('Background progress saved successfully', response.data);
                lastProgressSaveTime = Date.now(); // Still update this for throttling
            })
            .catch(error => {
                console.error('Error saving background progress:', error.response ? error.response.data : error.message);
            });
    } else {
        console.log('Saving progress (foreground - Inertia form) with data:', payload);
        // Use Inertia's form helper for foreground requests (will show progress bar)
        progressForm.reset(); // Reset form before filling
        progressForm.user_id = payload.user_id;
        progressForm.video_id = payload.video_id;
        progressForm.watched_duration = payload.watched_duration;
        progressForm.completed = payload.completed;
        progressForm.last_watched_at = payload.last_watched_at;

        progressForm.post(route('progress.storeUserVideoProgress'), {
            preserveScroll: true,
            preserveState: true,
            onError: (errors) => {
                console.error('Error saving progress (Inertia form):', errors);
            },
            onSuccess: () => {
                lastProgressSaveTime = Date.now();
                if (payload.completed) {
                    if (currentVideoSavedProgress.value) {
                        currentVideoSavedProgress.value.completed = true;
                    } else {
                        currentVideoSavedProgress.value = { completed: true };
                    }

                    // Show per-video quiz first if present
                    if (maybeShowVideoQuizFlow()) {
                        return;
                    }

                    // Otherwise, show notes if present
                    if (currentVideo.value && currentVideo.value.takeaway_notes) {
                        notesForVideo.value = currentVideo.value;
                        showNotesPopup.value = true;
                        return;
                    }

                    // Otherwise, check course completion or go next
                    axios.get(route('courses.completionStatus', { course: props.course.id }))
                        .then(response => {
                            if (response.data.is_completed) {
                                handleCourseCompletion();
                            } else {
                                playNextVideo();
                            }
                        })
                        .catch(error => {
                            console.error('Error checking course completion status:', error);
                            playNextVideo();
                        });
                }
            }
        });
    }
};

function onQuizCompleted(result) {
    // result contains attempt and summary
    quizAttemptResult.value = result.attempt || null;
    if (quizAttemptResult.value) {
        showQuizResultPopup.value = true;
    } else {
        // Fallback to next step if no attempt returned
        afterResultFlow();
    }
}

function onQuizResultClosed() {
    showQuizResultPopup.value = false;
    quizAttemptResult.value = null;
    afterResultFlow();
}

function afterResultFlow() {
    if (currentVideo.value && currentVideo.value.takeaway_notes) {
        notesForVideo.value = currentVideo.value;
        showNotesPopup.value = true;
        return;
    }
    axios.get(route('courses.completionStatus', { course: props.course.id }))
        .then(response => {
            if (response.data.is_completed) {
                handleCourseCompletion();
            } else {
                playNextVideo();
            }
        })
        .catch(error => {
            console.error('Error checking completion status after result close:', error);
            playNextVideo();
        });
}

const onPlay = () => {
    isPlaying.value = true;
    lastProgressSaveTime = Date.now();
};

const onPause = () => {
    isPlaying.value = false;
    handlePause();
};

// Add router event listeners to prevent loader
onMounted(async () => {
    router.on('start', () => {
        page.props.meta = { ...page.props.meta, disableLoader: true };
    });
    router.on('finish', () => {
        page.props.meta = { ...page.props.meta, disableLoader: true };
    });

    window.addEventListener('resize', updateScreenSize);
    updateScreenSize();

    let videoToPlayInitially = null;
    try {
        const response = await axios.get(route('progress.getCourseProgress', { course: props.course.id }));
        const courseProgress = response.data;
        const completedVideoIds = new Set(courseProgress.filter(p => p.completed).map(p => p.video_id));

        // Find the first video that is not in the completed set
        videoToPlayInitially = sortedVideos.value.find(video => !completedVideoIds.has(video.id));

    } catch (error) {
        console.error("Could not fetch course progress, defaulting to first video.", error);
    }

    // Fallback to the first video if no uncompleted video is found or if there was an error
    if (!videoToPlayInitially && sortedVideos.value.length > 0) {
        videoToPlayInitially = sortedVideos.value[0];
    }

    if (videoToPlayInitially) {
        currentVideo.value = videoToPlayInitially;
        lastProgressSaveTime = 0;
        currentVideoSavedProgress.value = null;
        initialTimeApplied.value = false;
        fetchVideoProgress(videoToPlayInitially.id);
    }
    fetchRelatedCourses();

    // Hide Tawk.to widget on this page
    const tawkInterval = setInterval(() => {
        if (window.Tawk_API && typeof window.Tawk_API.hideWidget === 'function') {
            window.Tawk_API.hideWidget();
            clearInterval(tawkInterval);
        }
    }, 100);
    setTimeout(() => clearInterval(tawkInterval), 1000); // Failsafe to stop polling
});

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
    window.removeEventListener('resize', updateScreenSize);

    // Show Tawk.to widget when leaving the page
    if (window.Tawk_API && typeof window.Tawk_API.showWidget === 'function') {
        window.Tawk_API.showWidget();
    }

    console.warn('[[PLAYER UNMOUNTING]]: Attempting to save final progress (foreground save).', {
        hasPlayer: !!videoPlayer.value,
        hasCurrentVideo: !!currentVideo.value,
        currentTime: videoPlayer.value?.currentTime,
        duration: videoPlayer.value?.duration,
        ended: videoPlayer.value?.ended
    });

    if (videoPlayer.value && currentVideo.value) {
        const currentTime = videoPlayer.value.currentTime;
        const duration = videoPlayer.value.duration;

        if (duration > 0 && currentTime > 0 && !videoPlayer.value.ended) {
            const isCompletedOnUnmount = currentTime >= duration - 2;
            console.log(`Unmount save: videoId=${currentVideo.value.id}, currentTime=${currentTime}, duration=${duration}, isCompleted=${isCompletedOnUnmount}`);
            saveProgress(isCompletedOnUnmount, false); // Foreground save
        } else {
            console.log(`Unmount save: No progress to save or video already ended for videoId=${currentVideo.value.id}. currentTime=${currentTime}, duration=${duration}, ended=${videoPlayer.value.ended}`);
        }
    } else {
        console.log("Unmount save: No current video or player instance to save progress for.");
    }
});

const updateScreenSize = () => {
    const wasLargeScreen = isLargeScreen.value;
    isLargeScreen.value = window.innerWidth > 770;

    // On large screens, always show sidebar; on small screens, close it if it was open due to large screen
    if (isLargeScreen.value && !wasLargeScreen) {
        // Just switched to large screen - sidebar will show automatically
    } else if (!isLargeScreen.value && wasLargeScreen) {
        // Just switched to small screen - close sidebar
        isVideoSidebarOpen.value = false;
    }
};

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
        position: fixed;
        top: 5rem;
        right: 0;
        height: calc(100vh - 5rem);
        z-index: 1002;
        transition: transform 0.3s ease-in-out;
        box-shadow: -2px 0 8px rgba(0, 0, 0, 0.3);
    }

    .player_sidebar:not(.open) {
        transform: translateX(100%);
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

.home_page_style {
    padding: 0px !important;
}

.dark .player_dark_text {
    color: white !important;
}

/* Ensure video player does not exceed viewport height, adjust h-[60vh] as needed */

/* Custom scrollbar hiding utility */
.no-scrollbar::-webkit-scrollbar {
    display: none;
}

.no-scrollbar {
    -ms-overflow-style: none;
    /* IE and Edge */
    scrollbar-width: none;
    /* Firefox */
}

/* Contained player layout: two columns, not full-page */
.player-contained {}

.player-grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    /* gap: 1.5rem; */
    align-items: start;
}

@media (max-width: 900px) {
    .player-grid {
        grid-template-columns: 1fr;
    }
}

.player-left {
    min-width: 0;
}

.player-video-box {
    position: relative;
    width: 100%;
    height: 60vh;
    border-radius: 0px;
    background-color: #000;
    overflow: hidden;
    /* keep blue control bar inside video frame */
}

.player-video-el {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: contain;
}

@media (max-width: 1024px) {
    .player-video-el {
        height: 100%;
    }

    /* .player-video-box {
        height: 45vh;
    } */
}

@media (max-width: 640px) {
    .player-video-el {
        height: 100%;
    }

    /* .player-video-box {
        height: 35vh;
    } */
}

/* Overlay controls (play/pause + 10s skip) */
.player-overlay-controls {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8.5rem;
    pointer-events: none;
    opacity: 0;
    transition: opacity 0.2s ease-in-out;
}

.group:hover .player-overlay-controls {
    opacity: 1;
}

.player-overlay-button {
    pointer-events: auto;
    background: #1C355E;
    color: #ffffff;
    border: none;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.35);
}

.player-overlay-play {
    width: 72px;
    height: 72px;
}

.player-overlay-skip {
    width: 54px;
    height: 54px;
}

.player-overlay-button svg {
    width: 100%;
    height: 100%;
}

.player-overlay-button:hover {
    background: #254a7a;
}

.player-prev-next {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.player-nav-btn {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: white;
    background-color: #1C355E;
    border: none;
    border-radius: 0.375rem;
    cursor: pointer;
}

.player-nav-btn:hover {
    background-color: #254a7a;
}

.player-mark-complete {
    display: inline-block;
    padding: 0.5rem 1rem;
    width: 100%;
    font-size: 0.875rem;
    font-weight: 500;
    color: #1C355E;
    background: transparent;
    border: 1px solid #1C355E;
    border-radius: 0.375rem;
    cursor: pointer;
}

.player-mark-complete:hover {
    background-color: rgba(34, 197, 94, 0.08);
}

.player-video-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin-top: 0.75rem;
    margin-bottom: 0.25rem;
    color: #111;
}

.dark .player-video-title {
    color: #fff;
}

.player-section-label {
    font-size: 0.875rem;
    color: #1C355E;
    margin: 0;
}

.dark .player-section-label {
    color: #4ade80;
}

/* Details section layout under player: main content + instructor aside */
.player-details-wrapper {
    display: flex;
    align-items: flex-start;
    gap: 1.75rem;
}

.player-details-main {
    flex: 1;
    min-width: 0;
}

.player-details-aside {
    width: 280px;
    flex-shrink: 0;
}

.player-instructor-card-details {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    padding: 1.25rem;
    box-shadow: 0 4px 10px rgba(15, 23, 42, 0.06);
}

.dark .player-instructor-card-details {
    background: #111827;
    border-color: #1f2937;
}

.player-instructor-heading-details {
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
    color: #111827;
}

.dark .player-instructor-heading-details {
    color: #e5e7eb;
}

.player-instructor-avatar-details {
    width: 48px;
    height: 48px;
    border-radius: 999px;
    background: #1C355E;
    color: #ffffff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    letter-spacing: 0.02em;
    flex-shrink: 0;
}

.player-instructor-name-details {
    font-weight: 600;
    color: #111827;
    margin: 0;
}

.dark .player-instructor-name-details {
    color: #f9fafb;
}

.player-instructor-role-details {
    font-size: 0.8rem;
    color: #2563eb;
    margin-top: 0.15rem;
}

.player-instructor-bio-details {
    font-size: 0.8rem;
    color: #4b5563;
    margin-top: 0.5rem;
    line-height: 1.4;
}

.dark .player-instructor-bio-details {
    color: #9ca3af;
}

@media (max-width: 1024px) {
    .player-details-wrapper {
        flex-direction: column;
    }

    .player-details-aside {
        width: 100%;
    }
}

/* Right column: one section + Next Category */
.player-right {
    position: relative;
    background: #252525;
    color: #fff;
    /* border-radius: 0.5rem; */
    overflow: hidden;
    height: 60vh;
    /* max-height: calc(100vh - 2rem); */
    display: flex;
    flex-direction: column;
}

.dark .player-right {
    background: #111827;
}

@media (max-width: 900px) {
    .player-right {
        position: fixed;
        top: 5rem;
        right: 0;
        width: 320px;
        max-width: calc(100vw - 2rem);
        height: calc(100vh - 5rem);
        z-index: 1002;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        box-shadow: -2px 0 12px rgba(0, 0, 0, 0.2);
    }

    .player-right.open {
        transform: translateX(0);
    }
}

.player-sidebar-inner {
    padding: 1rem;
    overflow-y: auto;
    flex: 1;
    min-height: 0;
}

.player-sidebar-heading {
    font-size: 1rem;
    font-weight: 600;
    margin: 0 0 0.25rem 0;
    color: #fff;
}

.player-sidebar-lessons {
    font-size: 0.75rem;
    color: #9ca3af;
    margin: 0 0 0.75rem 0;
}

.player-lesson-list {
    list-style: none;
    padding: 0;
    margin: 0 0 1rem 0;
}

.player-lesson-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    width: 100%;
    text-align: left;
    padding: 0.5rem 0;
    border: none;
    background: transparent;
    color: #e5e7eb;
    cursor: pointer;
    border-radius: 0.375rem;
    transition: background 0.15s;
}

.player-lesson-item:hover {
    background: rgba(255, 255, 255, 0.08);
    color: #fff;
}

.player-lesson-item-active {
    background: #4B4B4B;
    color: #1C355E;
}

.player-lesson-thumb {
    width: 88px;
    height: 50px;
    object-fit: cover;
    border-radius: 0.25rem;
    flex-shrink: 0;
}

.player-lesson-thumb-placeholder {
    background: #374151;
    display: block;
}

.player-lesson-title {
    flex: 1;
    color: #ffffff;
    font-size: 0.875rem;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.player-lesson-play-icon {
    width: 1.25rem;
    height: 1.25rem;
    flex-shrink: 0;
    color: #fff;
}

.player-category-nav {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 1rem;
}

.player-category-btn {
    padding: 0.5rem 0.75rem;
    font-size: 0.8125rem;
    font-weight: 500;
    color: #feffff;
    background: #4B4B4B;
    border: none;
    border-radius: 0.375rem;
    cursor: pointer;
}

.player-category-btn:hover {
    color: #fff;
    background: #4b5563;
}

.player-category-btn-primary {
    color: #fff;
    background: #4B4B4B;
}

.player-category-btn-primary:hover {
    background: #254a7a;
}

.player-instructor-card {
    padding: 1rem 0;
    border-top: 1px solid #374151;
}

.player-instructor-heading {
    font-size: 0.9375rem;
    font-weight: 600;
    margin: 0 0 0.75rem 0;
    color: #fff;
}

.player-instructor-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}

.player-instructor-avatar-initials {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: #4B4B4B;
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    letter-spacing: 0.03em;
}

.player-instructor-name {
    font-weight: 600;
    margin: 0;
    color: #fff;
}

.player-instructor-role {
    font-size: 0.8125rem;
    color: #ffffff;
    margin: 0.25rem 0 0 0;
}

.player-instructor-bio {
    font-size: 0.8125rem;
    color: #9ca3af;
    margin: 0.5rem 0 0 0;
    line-height: 1.4;
}

.player-back-link {
    display: block;
    text-align: center;
    padding: 0.5rem;
    font-size: 0.875rem;
    color: #fcfcfc;
    text-decoration: none;
    border-radius: 0.375rem;
    margin-top: 0.5rem;
}

.player-back-link:hover {
    color: #4ade80;
    text-decoration: underline;
}

.player-mobile-menu-btn {
    padding: 0.5rem;
    color: #374151;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 0.375rem;
}

.dark .player-mobile-menu-btn {
    color: #e5e7eb;
    background: #1f2937;
    border-color: #374151;
}
</style>layer-mobile-menu-btn { color: #e5e7eb; background: #1f2937; border-color: #374151; }
