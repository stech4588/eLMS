<template>
    <div class="dark:text-gray-200 p-4 border-t dark:border-gray-600">
        <div class="flex items-start mb-2">
            <img :src="post.user.profile_photo_url" alt="User profile picture" class="w-10 h-10 rounded-full mr-3">
            <div class="flex-1">
                <div class="flex items-center mb-1">
                    <span class="font-bold">{{ post.user.name }}</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-2" v-if="post.user.type === 'instructor'">(Creator)</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-2" v-else-if="post.user.type">({{ post.user.type.charAt(0).toUpperCase() + post.user.type.slice(1) }})</span>
                    <span class="text-gray-500 dark:text-gray-400 text-sm ml-2"> {{ formatTimeAgo(post.created_at) }}</span>
                </div>
                <p>{{ post.content }}</p>

                <!-- Replies Section -->
                <div class="mt-2">
                    <button
                        @click="toggleReplies"
                        class="text-blue-500 dark:text-blue-400 text-sm font-semibold flex items-center space-x-2"
                    >
                        <svg v-if="showReplies" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd" />
                        </svg>
                        <span>
                            {{ replyToggleText }}
                        </span>
                    </button>

                    <div v-if="showReplies" class="mt-2">
                        <div v-for="reply in replies" :key="reply.id" class="flex items-start mb-2">
                            <img :src="reply.user.profile_photo_url" alt="User profile picture" class="w-8 h-8 rounded-full mr-3 mt-1">
                            <div class="flex-1">
                                <div class="flex items-center mb-1">
                                    <span class="font-bold">{{ reply.user.name }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-2" v-if="reply.user.type === 'instructor'">(Creator)</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-2" v-else-if="reply.user.type">({{ reply.user.type.charAt(0).toUpperCase() + reply.user.type.slice(1) }})</span>
                                    <span class="text-gray-500 text-sm ml-2">{{ formatTimeAgo(reply.created_at) }}</span>
                                </div>
                                <p>{{ reply.content }}</p>
                            </div>
                        </div>
                        <button v-if="currentPage < lastPage" @click="fetchReplies" class="text-blue-500 text-sm mt-2">
                            Load more replies
                        </button>
                    </div>

                    <!-- Reply Form -->
                    <div v-if="showReplies" class="mt-2 flex items-start">
                        <img :src="$page.props.auth.user.profile_photo_url" alt="My profile picture" class="w-8 h-8 rounded-full mr-3 mt-1">
                        <div class="flex-1 flex">
                            <input
                                v-model="newReplyContent"
                                class="w-full p-2 border rounded-l-md text-sm dark:bg-dark-bg dark:text-gray-200 dark:border-gray-600 dark:bg-dark-bg-secondary"
                                placeholder="Add a reply..."
                                @keyup.enter="submitReply"
                            />
                             <button @click="submitReply" class="p-2.5 rounded-r-md text-black dark:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                        <path d="M3.105 2.289a.75.75 0 00-.826.95l1.414 4.925A1.5 1.5 0 005.135 9.25h6.115a.75.75 0 010 1.5H5.135a1.5 1.5 0 00-1.442 1.086L2.279 16.76a.75.75 0 00.826.95l14.433-6.414a.75.75 0 000-1.308L3.105 2.289z" />
                    </svg>
                </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, defineProps, computed } from 'vue';
import axios from 'axios';
import { formatDistanceToNow } from 'date-fns';

const props = defineProps({
    post: Object,
});

const showReplies = ref(false);
const replies = ref([]);
const newReplyContent = ref('');
const currentPage = ref(1);
const lastPage = ref(1);
const loadedReplies = ref(false);

const formatTimeAgo = (date) => {
    return formatDistanceToNow(new Date(date), { addSuffix: true });
};

const replyToggleText = computed(() => {
    if (showReplies.value) {
        return 'Hide Replies';
    }
    if (props.post.replies_count > 0) {
        return `Show ${props.post.replies_count} replies`;
    }
    return 'Reply';
});

const toggleReplies = () => {
    showReplies.value = !showReplies.value;
    if (showReplies.value && !loadedReplies.value && props.post.replies_count > 0) {
        fetchReplies();
    }
};

const fetchReplies = async () => {
    if (currentPage.value > lastPage.value && loadedReplies.value) return;

    try {
        const response = await axios.get(`/api/community-posts/${props.post.id}?page=${currentPage.value}`);
        replies.value = [...replies.value, ...response.data.data];
        lastPage.value = response.data.last_page;
        currentPage.value++;
        loadedReplies.value = true;
    } catch (error) {
        console.error('Error fetching replies:', error);
    }
};

const submitReply = async () => {
    if (!newReplyContent.value.trim()) return;

    try {
        const response = await axios.post('/api/community-posts', {
            content: newReplyContent.value,
            parent_id: props.post.id,
        });
        replies.value.push(response.data);
        newReplyContent.value = '';
        props.post.replies_count++;
         if (!showReplies.value) {
            showReplies.value = true;
        }
    } catch (error) {
        console.error('Error submitting reply:', error);
    }
};
</script>
