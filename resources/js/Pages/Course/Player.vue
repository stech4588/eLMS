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
                    <video v-if="currentVideo && currentVideo.video_url"
                        ref="videoPlayer" 
                        :key="currentVideo.id"
                        :src="currentVideo.video_url" controls autoplay 
                        @timeupdate="handleTimeUpdate"
                        @pause="handlePause"
                        @ended="() => { handleEnded(); playNextVideo(); }"
                        @loadedmetadata="handleLoadedMetadata"
                        class="w-full h-[60vh] object-contain player_video" 
                        @play="() => { lastProgressSaveTime = Date.now(); /* Reset timer when play starts/resumes */ }"
                        >
                        <!-- <source :src="currentVideo.video_url" type="video/mp4"> -->
                        Your browser does not support the video tag.
                    </video>
                    <div v-else class="w-full h-[60vh] bg-black flex items-center justify-center text-white">
                        <p v-if="!course.videos || course.videos.length === 0">No videos available for this course.</p>
                        <p v-else>Select a video to play.</p>
                    </div>
                </div>

                <!-- Video Details -->
                <div class="p-6 -auto bg-white flex-1 dark:bg-dark-bg-secondary dark:text-white">

                    <div v-if="currentVideo">
                        <h1 class="text-2xl font-bold mb-2 dark:text-white">{{ currentVideo.title }}</h1>
                        <div>
                            <div class="dark:text-white player_dark_text" style="font-size: 16px; font-weight: 600; color: #7E7E7E">
                                Instructor
                            </div>
                            <div class="flex items-center mt-2" style="gap: 14px;">
                                <img :src="course.user.profile_photo_url ? course.user.profile_photo_url : '/images/profile_photo.jpg'"
                                    style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;" />
                                <span class="ml-2 dark:text-white"
                                    style="font-size: 13px; font-weight: 400; display: flex; flex-direction: column; gap: 8px;">{{
                                        course.user.name }} <button class="text-[#2C15F5] text-xs"
                                        style="font-size: 14px; font-weight: 400; border: 1px solid #2C15F5; border-radius: 20px; padding: 4px 19px;">+
                                        Follow</button></span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div style="font-size: 16px; font-weight: 600;" class="dark:text-white">
                                Video Discription
                                <p class="text-black-400 whitespace-pre-wrap dark:text-white" style="font-size: 14px; line-height: 16px;">{{
                            currentVideo.description || 'No description available.' }}</p>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div style="font-size: 16px; font-weight: 600; " class="dark:text-white">
                                Course Details
                                <div class="flex items-center mt-1 player_dark_text" style="gap: 16px; color: #7E7E7E">
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
                                <div v-for="comment in displayedComments" :key="comment.id" class="p-4 bg-gray-50 border border-[#7E7E7E] dark:bg-dark-bg-secondary dark:text-white">
                                    <div class="flex items-center mb-2 dark:bg-dark-bg-secondary">
                                        <img :src="comment.user.profile_photo_url ? comment.user.profile_photo_url : '/images/profile_photo.jpg'" alt="User avatar" class="w-8 h-8 rounded-full mr-3" style="object-fit: cover;"/>
                                        <span class="dark:text-white" style="font-size: 13px; font-weight: 400; color: #000000;">{{ comment.user.name }}</span>
                                        <span class="text-xs text-gray-500 ml-auto dark:text-white" style="font-size: 12px; font-weight: 400; color: black;">{{ new Date(comment.created_at).toLocaleString() }}</span>
                                    </div>
                                    <p class="text-gray-700 text-sm " style="font-size: 13px; font-weight: 400; color: #000000;">{{ comment.body }}</p>
                                </div>
                            </div>
                            <div v-else-if="course.comments && course.comments.length === 0" class="text-gray-500 mb-6 player_dark_text">
                                No comments yet. Be the first to comment!
                            </div>
                            <!-- Loading/placeholder can be added here if props.course.comments is initially undefined -->

                            <!-- Show More / Show Less Buttons -->
                            <div class="mt-4 mb-6 dark:bg-dark-bg-secondary" style="display: flex; justify-content: center; align-items: center; ">
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
                                <textarea v-model="newComment" rows="3" placeholder="Add a comment..." class="w-full p-2 border dark:bg-dark-bg-secondary border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
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
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { ref, computed, onMounted, watch, onUnmounted } from 'vue';
import axios from 'axios'; // Import axios

const page = usePage();
page.props.meta = { ...page.props.meta, disableLoader: true };

const props = defineProps({
    course: Object, // Contains course details and an array of its videos
    initialVideoId: [String, Number, null], // Optional ID of the video to play first
});

const currentVideo = ref(null);
const isLargeScreen = ref(window.innerWidth > 770); // Reactive variable for screen size
const newComment = ref(''); // For the new comment textarea
const videoPlayer = ref(null); // Ref for the video element
const { props: pageProps } = usePage();
const authUser = computed(() => usePage().props.value.auth.user);
const currentVideoSavedProgress = ref(null); // To store fetched progress
const initialTimeApplied = ref(false); // New ref to track if initial time has been set

let lastProgressSaveTime = 0;
const progressSaveInterval = 5000; // Save progress every 5 seconds

const progressForm = useForm({
    user_id: null,
    video_id: null,
    watched_duration: 0,
    completed: false,
    last_watched_at: null,
});

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
                'Accept': 'application/json',
            }
        })
            .then(response => {
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
            }
        });
    }
};

const handleTimeUpdate = () => {
    if (!videoPlayer.value || !currentVideo.value) return;
    const now = Date.now();
    if (now - lastProgressSaveTime > progressSaveInterval) {
        if (!videoPlayer.value.paused && videoPlayer.value.duration > 0) { 
            console.log("handleTimeUpdate: Interval reached, attempting background save.");
            saveProgress(false, true); // Call with isBackgroundSave = true
        }
    }
};

const handlePause = () => {
    if (videoPlayer.value && videoPlayer.value.readyState >= 2 && !videoPlayer.value.ended && videoPlayer.value.duration > 0) {
         console.log("handlePause: Video paused, attempting foreground save.");
         saveProgress(false, false); // Explicitly false, or rely on default
    }
};

const handleEnded = () => {
    console.log('Video ended (handleEnded triggered). Attempting foreground save as complete.');
    saveProgress(true, false); // Mark as completed, foreground save
    // playNextVideo(); // playNextVideo is already bound to @ended on the video element directly in the template for now
    // If we keep it here, we might remove the direct binding. For now, let playNextVideo be handled by its direct binding.
};

const fetchVideoProgress = async (videoId) => {
    if (!videoId) return;
    console.log(`Fetching progress for video ID: ${videoId}`);
    try {
        const response = await axios.get(route('progress.getUserVideoProgress', { video: videoId }));
        if (response.data) {
            currentVideoSavedProgress.value = response.data;
            console.log(`Fetched progress for video ID ${videoId}:`, response.data);
            
            // If we already have the video element, try to apply the progress immediately
            if (videoPlayer.value && currentVideo.value?.id === videoId) {
                applySavedProgress();
            }
        } else {
            currentVideoSavedProgress.value = null;
            console.log('No progress found for video ID:', videoId);
        }
    } catch (error) {
        console.error('Error fetching video progress:', error.response ? error.response.data : error.message);
        currentVideoSavedProgress.value = null;
    }
};

const applySavedProgress = () => {
    if (!videoPlayer.value || !currentVideoSavedProgress.value || initialTimeApplied.value) {
        return;
    }

    const progress = currentVideoSavedProgress.value;
    
    // Only apply if:
    // 1. We have progress for this video
    // 2. The video has some watch time saved
    // 3. The video wasn't completed
    if (progress.video_id === currentVideo.value.id && 
        progress.watched_duration > 0 && 
        !progress.completed) {
        
        console.log(`Applying saved progress: setting currentTime to ${progress.watched_duration}`);
        
        // Wait for video to be ready
        const checkReady = () => {
            if (videoPlayer.value.readyState > 0) {
                videoPlayer.value.currentTime = progress.watched_duration;
                initialTimeApplied.value = true;
                console.log('Progress applied successfully');
            } else {
                setTimeout(checkReady, 100);
            }
        };
        
        checkReady();
    }
};


const handleLoadedMetadata = () => {

    console.log('Video metadata loaded');
    
    console.log(`[[LOADEDMETADATA]] Fired for video ID: ${currentVideo.value ? currentVideo.value.id : 'N/A'}. Current player time: ${videoPlayer.value?.currentTime}. Initial time applied: ${initialTimeApplied.value}`);
    console.log(`[[LOADEDMETADATA]] currentVideoSavedProgress:`, currentVideoSavedProgress.value ? JSON.parse(JSON.stringify(currentVideoSavedProgress.value)) : null);

    if (videoPlayer.value && currentVideoSavedProgress.value && !initialTimeApplied.value) { // Check initialTimeApplied
        const progress = currentVideoSavedProgress.value;
        console.log('[[LOADEDMETADATA]] Conditions check:');
        console.log(`  - progress.watched_duration > 0: ${progress.watched_duration > 0} (value: ${progress.watched_duration})`);
        console.log(`  - !progress.completed: ${!progress.completed} (value: ${progress.completed})`);
        // We remove the videoPlayer.value.currentTime < 1 check for now, relying on initialTimeApplied flag

        if (currentVideo.value && progress.video_id === currentVideo.value.id) {
            console.log('[[LOADEDMETADATA]] Saved progress video_id matches currentVideo.value.id.');
            if (progress.watched_duration > 0 && !progress.completed) { // Simplified condition
                console.log(`[[LOADEDMETADATA]] Applying saved progress: setting currentTime to ${progress.watched_duration} for video ID ${currentVideo.value.id}`);
                videoPlayer.value.currentTime = progress.watched_duration;
                initialTimeApplied.value = true; // Mark that we've applied it
            } else {
                console.log('[[LOADEDMETADATA]] Conditions to apply progress not fully met or already applied.');
            }
        } else {
            console.warn('[[LOADEDMETADATA]] Mismatch: currentVideoSavedProgress.video_id does not match currentVideo.value.id.', 
                { progressVideoId: progress.video_id, currentVideoId: currentVideo.value?.id });
        }
    } else {
        console.log('[[LOADEDMETADATA]] No videoPlayer or no currentVideoSavedProgress.');
    }
    lastProgressSaveTime = Date.now(); // Reset save timer as video metadata is loaded/reloaded
    applySavedProgress();
};

onMounted(() => {
    window.addEventListener('resize', updateScreenSize);
    updateScreenSize();

    let videoToPlayInitially = null;
    if (props.initialVideoId && sortedVideos.value.length > 0) {
        videoToPlayInitially = sortedVideos.value.find(v => v.id == props.initialVideoId);
        if (!videoToPlayInitially && sortedVideos.value.length > 0) {
            videoToPlayInitially = sortedVideos.value[0]; // Fallback to first video if initialVideoId is invalid
        }
    } else if (sortedVideos.value.length > 0) {
        videoToPlayInitially = sortedVideos.value[0]; // Play the first video if no initialVideoId is provided
    }

    if (videoToPlayInitially) {
        // Select video without triggering its own progress save for outgoing video (as there isn't one yet)
        currentVideo.value = videoToPlayInitially; // Directly set currentVideo
        lastProgressSaveTime = 0;
        currentVideoSavedProgress.value = null;
        initialTimeApplied.value = false; // Reset flag for initial video
        fetchVideoProgress(videoToPlayInitially.id); // Fetch progress for the initial video
        console.log("onMounted: Initial video selected:", videoToPlayInitially.id);
    } else {
        console.log("onMounted: No initial video to play.");
    }
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

watch(currentVideo, (newVideo, oldVideo) => {
    if (oldVideo && videoPlayer.value) {
        // Save progress for the old video if it was playing and had progress
        // This is somewhat covered by selectVideo, but good for robustness if video changes externally
        if (!videoPlayer.value.paused && videoPlayer.value.currentTime > 0) {
             saveProgress(videoPlayer.value.currentTime >= videoPlayer.value.duration - 2);
        }
    }
    if (newVideo && videoPlayer.value) {
        // If we need to load initial progress for newVideo, this is where it would go.
        // For now, we just reset the save timer.
        lastProgressSaveTime = 0;
    }
    // Reset visible comments when video changes
    visibleCommentsCount.value = COMMENTS_TO_SHOW_INCREMENT;
});

onUnmounted(() => {
    window.removeEventListener('resize', updateScreenSize);
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
.home_page_style{
 padding:0px !important;
}
.dark .player_dark_text{
    color: white !important;
}
/* Ensure video player does not exceed viewport height, adjust h-[60vh] as needed */
</style>