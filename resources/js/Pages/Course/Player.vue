<template>

    <Head :title="course ? `Playing: ${course.title}` : 'Course Player'" />

    <AuthenticatedLayout v-slot="{ isSidebarOpen, isPlayerPage }">
        <FeedbackPopup :show="showFeedbackPopup" :course="completedCourse" @close="closeFeedbackPopup" />
        <NotesPopup :show="showNotesPopup" :video="notesForVideo" @close="handleCloseNotesPopup" />
        <QuizPopup :show="showQuizPopup" :quiz="activeQuiz" @close="closeQuizPopup" @completed="onQuizCompleted" />
        <QuizResultPopup :show="showQuizResultPopup" :attempt="quizAttemptResult" @close="onQuizResultClosed" />
        <div class="flex h-screen bg-gray-100 dark:bg-gray-900 relative">
            <!-- AI Chatbot -->
            <AiChatbot 
                :show="showChatbot" 
                :chat-context="chatbotContext" 
                :welcome-message="chatbotWelcomeMessage"
                :placeholder="chatbotPlaceholder"
                @close="showChatbot = false" 
            />

            <!-- Floating AI Chat Button -->
            <div class="fixed bottom-4 right-4 z-40">
                <button @click="showChatbot = true" class="bg-blue-600 text-white rounded-full p-4 shadow-lg hover:bg-blue-700 transition">
                    <svg xmlns="http://www.w.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                </button>
            </div>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col overflow-y-auto no-scrollbar">
                <!-- Video Player -->
                <div class="bg-black flex-shrink-0 relative group">
                    <video v-if="currentVideo && currentVideo.video_url" ref="videoPlayer" :key="currentVideo.id"
                        :src="currentVideo.video_url" controls controlslist="nodownload" @contextmenu.prevent
                        autoplay @pause="onPause"
                        @ended="handleEnded" @loadedmetadata="handleLoadedMetadata"
                        class="w-full h-[60vh] object-contain player_video" @play="onPlay">
                        <!-- <source :src="currentVideo.video_url" type="video/mp4"> -->
                        Your browser does not support the video tag.
                    </video>
                    <div v-else class="w-full h-[60vh] bg-black flex items-center justify-center text-white">
                        <p v-if="!course.videos || course.videos.length === 0">No videos available for this course.</p>
                        <p v-else>Select a video to play.</p>
                    </div>

                    <!-- Custom Controls Overlay -->
                    <div v-if="currentVideo && currentVideo.video_url"
                        class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="flex items-center justify-center space-x-12 pointer-events-auto">
                            <button @click="skipBackward(10)"
                                class="text-white p-2 rounded-full focus:outline-none transition-transform transform hover:scale-110">
                                <svg width="60" height="60" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M10.0003 4.16666L9.08033 2.87916C8.73866 2.39916 8.56616 2.15916 8.67449 1.93749C8.78283 1.71416 9.05783 1.70166 9.60783 1.67582C9.73783 1.66971 9.86866 1.66666 10.0003 1.66666C14.6028 1.66666 18.3337 5.39749 18.3337 9.99999C18.3337 14.6025 14.6028 18.3333 10.0003 18.3333C5.39783 18.3333 1.66699 14.6025 1.66699 9.99999C1.66635 8.70617 1.96727 7.43 2.54589 6.27277C3.1245 5.11554 3.96488 4.1091 5.00033 3.33332"
                                        stroke="white" stroke-width="1.25" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M6.66016 9.16998C7.10016 8.81998 7.50016 8.24165 7.75016 8.34998C8.00016 8.45665 7.92016 8.80998 7.92016 9.35998V13.34M13.3352 10.5C13.3352 9.34998 13.3902 9.03998 13.1702 8.66998C12.9502 8.29998 12.4002 8.33165 11.8502 8.33165C11.3002 8.33165 10.9002 8.29998 10.6352 8.59998C10.3102 8.94998 10.4502 9.59998 10.4102 10.5C10.5002 11.7 10.2552 12.65 10.6302 13.05C10.9002 13.38 11.3802 13.33 11.9502 13.34C12.5168 13.3333 12.8602 13.36 13.1402 13.04C13.4502 12.76 13.3002 11.65 13.3352 10.5Z"
                                        stroke="white" stroke-width="1.25" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </button>

                            <button @click="togglePlayPause"
                                class="text-white p-2 rounded-full focus:outline-none transition-transform transform hover:scale-110">
                                <svg v-if="!isPlaying" xmlns="http://www.w3.org/2000/svg" width="64" height="64"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" class="w-16 h-16">
                                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                </svg>
                                <svg v-else xmlns="http://www.w3.org/2000/svg" width="64" height="64"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" class="w-16 h-16">
                                    <rect x="6" y="4" width="4" height="16"></rect>
                                    <rect x="14" y="4" width="4" height="16"></rect>
                                </svg>
                            </button>

                            <button @click="skipForward(10)"
                                class="text-white p-2 rounded-full focus:outline-none transition-transform transform hover:scale-110">
                                <svg width="60" height="60" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M10.0003 4.16666L10.9203 2.87916C11.262 2.39916 11.4345 2.15916 11.3262 1.93749C11.2178 1.71416 10.9428 1.70166 10.3928 1.67582C10.2628 1.66971 10.132 1.66666 10.0003 1.66666C5.39783 1.66666 1.66699 5.39749 1.66699 9.99999C1.66699 14.6025 5.39783 18.3333 10.0003 18.3333C14.6028 18.3333 18.3337 14.6025 18.3337 9.99999C18.3343 8.70617 18.0334 7.43 17.4548 6.27277C16.8762 5.11554 16.0358 4.1091 15.0003 3.33332"
                                        stroke="white" stroke-width="1.25" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M6.66016 9.16998C7.10016 8.81998 7.50016 8.24165 7.75016 8.34998C8.00016 8.45665 7.92016 8.80998 7.92016 9.35998V13.34M13.3352 10.5C13.3352 9.34998 13.3902 9.03998 13.1702 8.66998C12.9502 8.29998 12.4002 8.33165 11.8502 8.33165C11.3002 8.33165 10.9002 8.29998 10.6352 8.59998C10.3102 8.94998 10.4502 9.59998 10.4102 10.5C10.5002 11.7 10.2552 12.65 10.6302 13.05C10.9002 13.38 11.3802 13.33 11.9502 13.34C12.5168 13.3333 12.8602 13.36 13.1402 13.04C13.4502 12.76 13.3002 11.65 13.3352 10.5Z"
                                        stroke="white" stroke-width="1.25" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Prev/Next Video Buttons -->
                <div v-if="currentVideo && sortedVideos.length > 1"
                    class="flex items-center justify-between px-6 py-4 bg-white dark:bg-dark-bg-secondary">
                    <button v-if="currentVideoIndex > 0" @click="playPreviousVideo"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-[#3b82f6] border border-transparent rounded-md shadow-sm hover:bg-[#2563eb] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <!-- Heroicon name: solid/chevron-left -->
                        <svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        Previous
                    </button>
                    <div v-else>&nbsp;</div> <!-- Placeholder to maintain layout -->

                    <button v-if="currentVideoIndex < sortedVideos.length - 1" @click="playNextVideo"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-[#3b82f6] border border-transparent rounded-md shadow-sm hover:bg-[#2563eb] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Next
                        <!-- Heroicon name: solid/chevron-right -->
                        <svg class="w-5 h-5 ml-2 -mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <!-- Video Details -->
                <div class="p-6 lg:p-8 bg-white dark:bg-dark-bg-secondary dark:text-white flex-1">
                    <div v-if="currentVideo">
                        <h1 class="text-3xl font-bold mb-3 dark:text-white">{{ currentVideo.title }}</h1>
                        
                        <!-- Instructor Section -->
                        <div class="mt-6">
                            <h2 class="text-xl font-semibold mb-3 dark:text-white">Instructor</h2>
                            <div class="flex items-center">
                                <img :src="course.user.profile_photo_url ? course.user.profile_photo_url : '/images/profile_photo.jpg'" class="w-16 h-16 rounded-full object-cover" />
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold dark:text-white">{{ course.user.name }}</h3>
                                    <button class="mt-1 text-sm text-blue-600 dark:text-blue-400 border border-blue-600 dark:border-blue-400 rounded-full px-4 py-1 hover:bg-blue-50 dark:hover:bg-gray-700 transition">
                                        + Follow
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Video Description -->
                        <div class="mt-8 prose dark:prose-invert max-w-none">
                            <h2 class="text-xl font-semibold mb-3 dark:text-white">Video Description</h2>
                            <TruncatedText :text="currentVideo.description || 'No description available.'" />
                        </div>

                        <!-- Takeaway Notes - Conditional -->
                        <div v-if="currentVideo.takeaway_notes && currentVideoSavedProgress?.completed" class="mt-8 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <div class="flex justify-between items-center mb-3">
                                <h2 class="text-xl font-semibold dark:text-white">Takeaway Notes</h2>
                            </div>
                            <TruncatedText :text="currentVideo.takeaway_notes" />
                        </div>

                        <!-- Course Details -->
                        <div class="mt-8">
                            <h2 class="text-xl font-semibold mb-3 dark:text-white">Course Details</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 text-gray-600 dark:text-gray-300">
                                <!-- <div class="flex items-center space-x-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span>{{ course.total_duration }}</span>
                                </div> -->
                                <div class="flex items-center space-x-3">
                                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                                    <span>{{ course.type }}</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span>Updated: {{ course.updated_at }}</span>
                                </div>
                                <div v-if="course.reviews_count > 0" class="flex items-center space-x-2">
                                    <span class="font-bold text-lg text-gray-800 dark:text-white">{{ course.average_rating }}</span>
                                    <StarRating :rating="course.average_rating" />
                                    <span>({{ course.reviews_count.toLocaleString() }} ratings)</span>
                                </div>
                                <p v-else class="text-sm">No ratings yet.</p>
                            </div>

                            <div class="mt-6 prose dark:prose-invert max-w-none">
                                <h3 class="font-semibold">Course Description</h3>
                                <TruncatedText :text="course.description || 'No description available.'" />
                                <h3 class="font-semibold mt-4">Additional Information</h3>
                                <TruncatedText :text="course.additional_description || 'No additional description available.'" />
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
                                            class="hover:text-blue-500 dark:hover:text-blue-400">
                                        <h4 class="text-lg font-semibold truncate dark:text-white">{{ relatedCourse.title
                                        }}</h4>
                                        </Link>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{
                                            relatedCourse.learners_count }} learners</p>
                                    </div>

                                    <!-- Bookmark Icon -->
                                    <div class="flex-shrink-0">
                                        <button @click.prevent="toggleFavorite(relatedCourse)" class="p-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                                :class="relatedCourse.is_favorited ? 'text-blue-500 fill-current' : 'text-gray-400'"
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
                            <h2 class="text-2xl font-semibold mb-4 dark:text-white">Comments ({{ totalCommentsCount }})</h2>

                            <!-- New comment form -->
                            <div class="flex items-start space-x-4 mb-8">
                                <img :src="authUser && authUser.profile_photo_url ? authUser.profile_photo_url : '/images/profile_photo.jpg'"
                                    alt="Your avatar" class="w-10 h-10 rounded-full object-cover">
                                <div class="flex-1 relative">
                                    <textarea v-model="newComment" rows="1" placeholder="Add a comment..."
                                        @focus="isCommentFocused = true"
                                        class="w-full p-3 bg-transparent border-b border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-0 transition resize-none"
                                        style="outline: none;"></textarea>
                                    <div v-if="isCommentFocused" class="flex justify-between items-center mt-2">
                                        <button @click="showEmojiPicker = !showEmojiPicker" class="p-2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                        <div class="space-x-2">
                                            <button @click="cancelComment"
                                                class="px-4 py-2 text-sm font-semibold text-gray-600 dark:text-gray-300 bg-transparent hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                                Cancel
                                            </button>
                                            <button @click="submitComment" :disabled="!newComment.trim()"
                                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:bg-gray-400 dark:disabled:bg-gray-600 transition-colors text-sm font-semibold">
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
                                <div v-for="comment in displayedComments" :key="comment.id" class="flex items-start space-x-4">
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
                                    class="text-sm font-semibold text-blue-600 dark:text-blue-400 hover:underline">
                                    Show More Comments
                                </button>
                                <button v-else @click="showLessComments"
                                    class="text-sm font-semibold text-blue-600 dark:text-blue-400 hover:underline">
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

            <!-- Sidebar for Videos -->
            <div v-if="isPlayerPage && (isSidebarOpen || isLargeScreen)"
                class="w-80 bg-gray-800 text-white flex-shrink-0 player_sidebar flex flex-col h-screen">
                <div class="p-4 border-b border-gray-700">
                    <h2 class="text-xl font-semibold">{{ course.title }}</h2>
                </div>

                <div class="overflow-y-auto flex-grow">
                    <ul class="p-4 space-y-2">
                        <li v-for="video in sortedVideos" :key="video.id">
                            <button @click="selectVideo(video)"
                                :class="['w-full text-left px-3 py-2 rounded-md text-sm transition-colors flex items-center space-x-3',
                                    currentVideo && currentVideo.id === video.id ? 'bg-gradient-to-r from-gray-600 to-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white']">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>{{ video.title }}</span>
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="p-4 mt-auto border-t border-gray-700">
                    <Link :href="route('courses.show', { course: course.id })"
                        class="block w-full text-center px-3 py-2 rounded-md text-sm bg-gray-600 hover:bg-gray-500 transition-colors">
                    Back to Course Details
                    </Link>
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
const isLargeScreen = ref(window.innerWidth > 770); // Reactive variable for screen size
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
    if (videoPlayer.value) {
        videoPlayer.value.currentTime += seconds;
    }
};

const skipBackward = (seconds) => {
    if (videoPlayer.value) {
        videoPlayer.value.currentTime -= seconds;
    }
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
    lastProgressSaveTime = 0; // Reset for the new video
    currentVideoSavedProgress.value = null; // Reset saved progress for the new video
    initialTimeApplied.value = false; // Reset flag for new video

    if (video) {
        fetchVideoProgress(video.id); // Fetch progress for the newly selected video
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
    isLargeScreen.value = window.innerWidth > 770;
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
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;  /* Firefox */
}
</style>